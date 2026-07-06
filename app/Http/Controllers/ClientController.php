<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = \App\Models\Group::orderBy('name')->get();
        return view('clients.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->validated());
        
        if ($request->has('groups')) {
            $client->groups()->sync($request->groups);
        }

        // Automatically scan reminders for the new client
        \Illuminate\Support\Facades\Artisan::call('crm:scan-reminders');

        return redirect()->route('clients.index')->with('success', 'Client added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        // For Phase 3, we skip the standalone show view unless specifically requested,
        // but we'll include the method stub.
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $groups = \App\Models\Group::orderBy('name')->get();
        return view('clients.edit', compact('client', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->validated());
        
        if ($request->has('groups')) {
            $client->groups()->sync($request->groups);
        } else {
            $client->groups()->detach();
        }

        // Automatically update reminders
        \Illuminate\Support\Facades\Artisan::call('crm:scan-reminders');

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->forceDelete();

        return redirect()->route('clients.index')->with('success', 'Client deleted permanently.');
    }

    public function export()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=clients.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Name', 'Email', 'Phone', 'WhatsApp Number', 'Birthday', 'Anniversary Date', 'Premium Due Date', 'Policy Name', 'Policy Number', 'Company Name', 'Address', 'Notes', 'Status'];
        
        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            Client::chunk(100, function($clients) use($file) {
                foreach ($clients as $client) {
                    $row = [
                        $client->name,
                        $client->email,
                        $client->phone,
                        $client->whatsapp_number,
                        $client->birthday ? $client->birthday->format('Y-m-d') : '',
                        $client->anniversary_date ? $client->anniversary_date->format('Y-m-d') : '',
                        $client->premium_due_date ? $client->premium_due_date->format('Y-m-d') : '',
                        $client->policy_name,
                        $client->policy_number,
                        $client->company_name,
                        $client->address,
                        $client->notes,
                        $client->status
                    ];
                    fputcsv($file, $row);
                }
            });
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $file = fopen($path, 'r');
        
        $header = fgetcsv($file); // Assume first row is header
        $count = 0;
        
        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 13) continue; // Basic validation
            
            $policyNumber = !empty($row[8]) ? $row[8] : null;
            
            $client = null;
            if ($policyNumber) {
                $client = Client::where('policy_number', $policyNumber)->first();
            }
            if (!$client && !empty($row[2])) {
                $client = Client::where('phone', $row[2])->first();
            }
            
            $data = [
                'name' => $row[0] ?: 'Unknown',
                'email' => !empty($row[1]) ? $row[1] : null,
                'phone' => !empty($row[2]) ? $row[2] : null,
                'whatsapp_number' => !empty($row[3]) ? $row[3] : null,
                'birthday' => !empty($row[4]) ? date('Y-m-d', strtotime($row[4])) : null,
                'anniversary_date' => !empty($row[5]) ? date('Y-m-d', strtotime($row[5])) : null,
                'premium_due_date' => !empty($row[6]) ? date('Y-m-d', strtotime($row[6])) : null,
                'policy_name' => !empty($row[7]) ? $row[7] : null,
                'policy_number' => $policyNumber,
                'company_name' => !empty($row[9]) ? $row[9] : null,
                'address' => !empty($row[10]) ? $row[10] : null,
                'notes' => !empty($row[11]) ? $row[11] : null,
                'status' => !empty($row[12]) && in_array(strtolower($row[12]), ['active', 'inactive']) ? strtolower($row[12]) : 'active',
            ];
            
            if ($client) {
                $client->update($data);
            } else {
                Client::create($data);
            }
            $count++;
        }
        
        fclose($file);
        
        // Scan for reminders after bulk import
        \Illuminate\Support\Facades\Artisan::call('crm:scan-reminders');
        
        return redirect()->route('clients.index')->with('success', "$count clients imported successfully.");
    }
}
