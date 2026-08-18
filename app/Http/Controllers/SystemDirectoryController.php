<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemDirectoryController extends Controller
{
    public function directory()
    {
        $emergencyContacts = [
            ['service' => 'Society Main Gate Security Desk', 'phone' => '+1 (800) 555-0199', 'icon' => 'bi-shield-lock-fill', 'color' => 'primary'],
            ['service' => 'Fire Department Emergency', 'phone' => '911 / 101', 'icon' => 'bi-fire', 'color' => 'danger'],
            ['service' => 'Ambulance & Medical Emergency', 'phone' => '911 / 102', 'icon' => 'bi-hospital-fill', 'color' => 'danger'],
            ['service' => 'Police Station Precinct', 'phone' => '911 / 100', 'icon' => 'bi-shield-shaded', 'color' => 'warning'],
            ['service' => '24/7 Plumbing Emergency Line', 'phone' => '+1 (800) 555-0144', 'icon' => 'bi-droplet-fill', 'color' => 'info'],
            ['service' => 'Electrical Grid & Transformer Faults', 'phone' => '+1 (800) 555-0177', 'icon' => 'bi-lightning-charge-fill', 'color' => 'warning'],
            ['service' => 'Elevator Breakdown Support', 'phone' => '+1 (800) 555-0188', 'icon' => 'bi-building-fill-gear', 'color' => 'secondary'],
            ['service' => 'Society Management Office', 'phone' => '+1 (800) 555-0100', 'icon' => 'bi-briefcase-fill', 'color' => 'dark'],
        ];

        $guidelines = [
            'Noise Control' => 'Quiet hours are observed strictly between 10:00 PM and 7:00 AM daily. Loud music or renovation noise during these hours is prohibited.',
            'Visitor Security & Gate Passes' => 'All visitors must be pre-approved via digital gate pass code or logged manually by gate guards upon arrival.',
            'Parking Regulations' => 'Vehicles must be parked strictly in designated assigned parking slots. Visitor parking is restricted to marked visitor bays.',
            'Amenity Usage Rules' => 'Clubhouse, Swimming Pool, and Sports Courts require prior reservation via the Resident Portal. Please adhere to booked time slots.',
            'Maintenance Dues' => 'Monthly maintenance bills are due by the 25th of each month. Late payments incur a fixed $25.00 overdue penalty fee.',
            'Waste Management' => 'Segregate dry and wet waste before disposing of it in tower collection chutes. Heavy furniture waste must be scheduled separately.',
        ];

        return view('directory_guidelines', compact('emergencyContacts', 'guidelines'));
    }
}
