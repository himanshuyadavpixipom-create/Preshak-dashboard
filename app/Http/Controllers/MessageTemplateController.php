<?php

namespace App\Http\Controllers;

use App\Models\MessageTemplate;
use App\Models\Client;
use App\Models\Reminder;
use App\Http\Requests\StoreMessageTemplateRequest;
use App\Http\Requests\UpdateMessageTemplateRequest;
use App\Services\TemplateRenderingService;
use Illuminate\Http\Request;

class MessageTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = MessageTemplate::orderBy('channel')->orderBy('reminder_type')->get();
        return view('templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $placeholders = TemplateRenderingService::getAvailablePlaceholders();
        return view('templates.create', compact('placeholders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageTemplateRequest $request)
    {
        $data = $request->validated();
        $channels = $data['channels'];
        unset($data['channels']);

        $data['is_active'] = $request->boolean('is_active', false);
        $data['is_default'] = $request->boolean('is_default', false);

        foreach ($channels as $channel) {
            $templateData = $data;
            $templateData['channel'] = $channel;
            
            // Optionally, append the channel name to the template name if multiple are selected
            if (count($channels) > 1) {
                $templateData['name'] = $data['name'] . ' (' . ucfirst($channel) . ')';
            }

            MessageTemplate::create($templateData);
        }

        return redirect()->route('templates.index')->with('success', 'Template(s) created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessageTemplate $template)
    {
        $placeholders = TemplateRenderingService::getAvailablePlaceholders();
        return view('templates.edit', compact('template', 'placeholders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageTemplateRequest $request, MessageTemplate $template)
    {
        $data = $request->validated();
        $channels = $data['channels'];
        unset($data['channels']);

        $data['is_active'] = $request->boolean('is_active', false);
        $data['is_default'] = $request->boolean('is_default', false);

        // Pop the first channel to update the existing template
        $firstChannel = array_shift($channels);
        $data['channel'] = $firstChannel;
        
        $template->update($data);

        // If other channels were checked, create new templates for them
        foreach ($channels as $channel) {
            $cloneData = $data;
            $cloneData['channel'] = $channel;
            
            // Optionally rename the clone to avoid confusion
            $cloneData['name'] = $data['name'] . ' (' . ucfirst($channel) . ')';
            
            MessageTemplate::create($cloneData);
        }

        return redirect()->route('templates.index')->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageTemplate $template)
    {
        $template->delete();

        return redirect()->route('templates.index')->with('success', 'Template deleted successfully.');
    }

    /**
     * Preview the rendered template with dummy or real context.
     */
    public function preview(MessageTemplate $template, TemplateRenderingService $renderer)
    {
        // Try to find a real reminder for this type, or just grab a random client
        $reminder = Reminder::where('type', $template->reminder_type)->with('client')->inRandomOrder()->first();
        
        if ($reminder && $reminder->client) {
            $client = $reminder->client;
        } else {
            // Fallback to a random client
            $client = Client::inRandomOrder()->first();
        }

        // If no clients exist at all, we can't properly preview.
        if (!$client) {
            return redirect()->route('templates.index')->with('error', 'Cannot preview template without at least one client in the system.');
        }

        $rendered = $renderer->renderTemplate($template, $client);

        return view('templates.preview', compact('template', 'client', 'rendered', 'reminder'));
    }
}
