<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CompanyProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        return Inertia::render('Company/CompanyProfile', [
            'company' => [
                'company_name' => $user->company_name,
                'company_email' => $user->company_email,
                'company_contact_number' => $user->company_contact_number,
                'address' => implode(', ', array_filter([
                    $user->company_street_address,
                    $user->company_brgy,
                    $user->company_city,
                    $user->company_province,
                    $user->company_zip_code
                ])),
                'sector' => $user->sector->name ?? null, // assuming relation exists
                'description' => $user->description,
            ],
        ]);
    }
}
