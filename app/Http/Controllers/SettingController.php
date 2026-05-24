<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'app_name' => 'nullable|string|max:100',
            'app_address' => 'nullable|string',
            'app_phone' => 'nullable|string|max:20',
            'app_email' => 'nullable|email',
            'late_fee_percent' => 'nullable|numeric|min:0|max:100',
            'grace_period_days' => 'nullable|integer|min:0',
            'semester_months' => 'nullable|integer|min:1',
            'deposit_months' => 'nullable|integer|min:1',
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log('update', 'System settings updated');

        return back()->with('success', 'Settings saved successfully!');
    }
}