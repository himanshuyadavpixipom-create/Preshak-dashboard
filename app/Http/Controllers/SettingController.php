<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Load all settings into an associative array for easy use in the view
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('integrations.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            // Check if boolean logic applies based on the key name
            $type = 'string';
            if (str_contains($key, 'simulate') || str_contains($key, 'active')) {
                $type = 'boolean';
                $value = $value ? '1' : '0';
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type]
            );
        }

        // For boolean toggles that might be absent from request when unchecked:
        $expectedBooleans = [
            'email_simulate', 'sms_simulate', 'whatsapp_simulate',
            'sms_active', 'whatsapp_active'
        ];
        foreach ($expectedBooleans as $boolKey) {
            if (!$request->has($boolKey)) {
                Setting::updateOrCreate(
                    ['key' => $boolKey],
                    ['value' => '0', 'type' => 'boolean']
                );
            }
        }

        return redirect()->route('integrations.index')->with('success', 'Integrations settings saved successfully.');
    }
}
