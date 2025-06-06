<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use Faker\Factory as Faker;
use Illuminate\Support\Arr;
use App\Models\Sector;
use App\Models\Category;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_PH');
        $sectors = Sector::pluck('id')->toArray(); // Fetch all valid sector IDs
        $categories = Category::pluck('id')->toArray(); // Fetch all valid category IDs

        $companies = [
            ['name' => 'Sydney Hotel', 'address' => 'PIONEER-PENDATUN AVE.', 'brgy' => 'DADIANGAS WEST'],
            ['name' => 'KCC Mall of Gensan', 'address' => 'J. CATOLICO SR. AVE.', 'brgy' => 'LAGAO'],
            ['name' => 'SM Mall of Gensan / SM PRIME HOLDINGS, INC.', 'address' => 'SM CITY, CORNER SANTIAGO BLVD. - SAN MIGUEL ST.', 'brgy' => 'LAGAO'],
            ['name' => 'SECURITY BANK CORPORATION', 'address' => 'NATIONAL HIGHWAY CORNER RECTO ST.', 'brgy' => 'DADIANGAS NORTH'],
            ['name' => '1 Cooperative Insurance System of the Philippines Life and General Insurance', 'address' => 'DOOR 1, ACHARON BUILDING, SAMPALOC STREET', 'brgy' => 'DADIANGAS WEST'],
            ['name' => 'KCD Real Estate Lessor', 'address' => 'DAMASCO, PUROK MANGGA', 'brgy' => 'CITY HEIGHTS'],
            ['name' => 'Land Bank of the Philippines', 'address' => 'NATIONAL HIGHWAY', 'brgy' => 'CITY HEIGHTS'],
            ['name' => 'SOUTH COTABATO II ELECTRIC COOPERATIVE', 'address' => 'J. CATOLICO AVENUE', 'brgy' => 'LAGAO'],
            ['name' => 'WILCON DEPOT, INC.', 'address' => 'PUROK PALEN', 'brgy' => 'LABANGAL'],
            ['name' => 'AGRIBUSINESS RURAL BANK, INC.', 'address' => 'J. CATOLICO AVENUE', 'brgy' => 'LAGAO'],
            ['name' => 'ASIA UNITED BANK CORPORATION', 'address' => 'BELINDA ENTERPRISE BLDG., I. SANTIAGO BLVD.', 'brgy' => 'DADIANGAS SOUTH'],
            ['name' => 'DOLE PHILIPPINES, INC', 'address' => 'PUROK STA. CRUZ', 'brgy' => 'CALUMPANG'],
            ['name' => 'SAN ANDRES FISHING INDUSTRIES, INC.', 'address' => 'PUROK CABU', 'brgy' => 'TAMBLER'],
            ['name' => 'SMART COMMUNICATIONS INC.', 'address' => 'CY 9-10, SM CITY, CORNER SANTIAGO BOULEVARD - SAN MIGUEL STREET', 'brgy' => 'LAGAO'],
            ['name' => 'ST. ELIZABETH HOSPITAL, INC.', 'address' => 'NATIONAL HIGHWAY', 'brgy' => 'LAGAO'],
        ];

        $jobTitlesPerCompany = [
            "Sydney Hotel" => [
                "Front Desk Officer",
                "Housekeeping Attendant",
                "Hotel Manager",
                "Room Service Staff",
                "Concierge",
                "Bellhop",
                "Night Auditor",
                "Security Personnel",
                "Kitchen Staff",
                "Event Coordinator"
            ],
            "KCC Mall of Gensan" => [
                "Sales Clerk",
                "Inventory Associate",
                "Customer Service Representative",
                "Cashier",
                "Mall Operations Assistant",
                "Store Supervisor",
                "Janitorial Staff",
                "Marketing Assistant",
                "Visual Merchandiser",
                "Security Guard"
            ],
            "SM Mall of Gensan / SM PRIME HOLDINGS, INC." => [
                "Mall Administrator",
                "Leasing Assistant",
                "Customer Service Associate",
                "Facilities Technician",
                "Security Supervisor",
                "Marketing Executive",
                "Janitor",
                "Elevator Technician",
                "Cashiering Officer",
                "Events Coordinator"
            ],
            "SECURITY BANK CORPORATION" => [
                "Bank Teller",
                "Branch Manager",
                "Loan Officer",
                "Customer Service Associate",
                "ATM Custodian",
                "Account Officer",
                "Credit Analyst",
                "Marketing Assistant",
                "Compliance Officer",
                "Financial Advisor"
            ],
            "1 Cooperative Insurance System of the Philippines Life and General Insurance" => [
                "Insurance Agent",
                "Claims Processor",
                "Policy Underwriter",
                "Member Services Officer",
                "Field Verifier",
                "Claims Adjuster",
                "Finance Officer",
                "Risk Analyst",
                "Customer Support Staff",
                "Office Clerk"
            ],
            "KCD Real Estate Lessor" => [
                "Leasing Officer",
                "Property Manager",
                "Real Estate Agent",
                "Maintenance Technician",
                "Marketing Associate",
                "Documentation Staff",
                "Front Desk Clerk",
                "Collection Officer",
                "Customer Relations Officer",
                "Building Attendant"
            ],
            "Land Bank of the Philippines" => [
                "Bank Teller",
                "Credit Officer",
                "Agricultural Loan Specialist",
                "Branch Operations Assistant",
                "IT Support Staff",
                "Risk Management Officer",
                "Loans Processor",
                "Finance Analyst",
                "Marketing Officer",
                "Audit Assistant"
            ],
            "SOUTH COTABATO II ELECTRIC COOPERATIVE" => [
                "Electrical Engineer",
                "Lineman",
                "Customer Service Staff",
                "Billing Analyst",
                "Collections Officer",
                "Meter Reader",
                "Power Systems Technician",
                "Inventory Clerk",
                "Safety Officer",
                "Maintenance Crew"
            ],
            "WILCON DEPOT, INC." => [
                "Warehouse Assistant",
                "Sales Consultant",
                "Inventory Controller",
                "Delivery Driver",
                "Cashier",
                "Product Specialist",
                "Store Supervisor",
                "Visual Merchandiser",
                "Customer Care Associate",
                "Procurement Staff"
            ],
            "AGRIBUSINESS RURAL BANK, INC." => [
                "Loans Officer",
                "Rural Banking Staff",
                "Teller",
                "Marketing Officer",
                "Field Collector",
                "Microfinance Officer",
                "Credit Investigator",
                "Compliance Assistant",
                "Finance Assistant",
                "Client Support Specialist"
            ],
            "ASIA UNITED BANK CORPORATION" => [
                "Customer Relationship Officer",
                "Teller",
                "Credit Analyst",
                "Branch Operations Supervisor",
                "Loan Processor",
                "Digital Banking Specialist",
                "Security Officer",
                "Administrative Aide",
                "Treasury Assistant",
                "Business Development Officer"
            ],
            "DOLE PHILIPPINES, INC" => [
                "Production Worker",
                "Quality Assurance Analyst",
                "Agricultural Technician",
                "Supply Chain Staff",
                "Machine Operator",
                "HR Assistant",
                "Forklift Operator",
                "Farm Supervisor",
                "Food Safety Inspector",
                "Logistics Coordinator"
            ],
            "SAN ANDRES FISHING INDUSTRIES, INC." => [
                "Factory Worker",
                "Refrigeration Technician",
                "Marine Engineer",
                "Production Supervisor",
                "Procurement Officer",
                "Quality Control Staff",
                "Inventory Assistant",
                "Net Repairman",
                "Operations Manager",
                "Logistics Staff"
            ],
            "SMART COMMUNICATIONS INC." => [
                "Retail Sales Associate",
                "Customer Support Representative",
                "Field Technician",
                "Network Engineer",
                "IT Specialist",
                "Store Supervisor",
                "Marketing Officer",
                "Data Analyst",
                "Account Executive",
                "Inventory Clerk"
            ],
            "ST. ELIZABETH HOSPITAL, INC." => [
                "Staff Nurse",
                "Medical Technologist",
                "Pharmacist",
                "Nursing Aide",
                "Billing Clerk",
                "Laboratory Assistant",
                "Radiologic Technologist",
                "Health Records Officer",
                "Hospital Admin Assistant",
                "ER Attendant"
            ]
        ];


        $jobTypes = ['full-time', 'part-time'];
        $experienceLevels = ['entry-level', 'intermediate', 'mid-level', 'senior-executive'];
        $salaryTypes = ['monthly', 'weekly', 'hourly', 'negotiable'];
        $skillsList = ['Communication', 'Teamwork', 'Problem-solving', 'Time Management', 'Customer Service', 'Leadership'];

        foreach ($companies as $company) {
            $companyName = $company['name'];
            $companyLocation = $company['address'] . ', ' . $company['brgy'] . ', General Santos City';
            $branchLocation =  $company['brgy'] . ', General Santos City';
            foreach ($jobTitlesPerCompany[$companyName] as $title) {
                $sectorId = Arr::random($sectors); // Use valid sector IDs
                $categoryId = Arr::random($categories); // Use valid category IDs

                $salaryType = Arr::random($salaryTypes);
                $minSalary = $this->getSalaryRange($salaryType, 'min');
                $maxSalary = $this->getSalaryRange($salaryType, 'max');

                Job::create([
                    'user_id' => 45,
                    'job_title' => $title,
                    'sector_id' => $sectorId,
                    'category_id' => $categoryId,
                    'job_type' => Arr::random($jobTypes),
                    'experience_level' => Arr::random($experienceLevels),
                    'salary_type' => $salaryType,
                    'min_salary' => $minSalary,
                    'max_salary' => $maxSalary,
                    'job_location' => $companyLocation,
                    'branch_location' => $branchLocation,
                    'vacancy' => random_int(1, 10),
                    'description' => "$title position at $companyName. Responsibilities include ...",
                    'job_requirements' => "Requirements for $title include ...",
                    'job_benefits' => "Health benefits, SSS, Pag-IBIG, PhilHealth",
                    'skills' => json_encode(array_rand(array_flip($skillsList), 3)),
                    'expiration_date' => now()->addDays(30)->format('Y-m-d'),
                    'application_limit' => Arr::random([null, 50, 100]),
                ]);
            }
        }
    }

    private function getSalaryRange($salaryType, $type)
    {
        switch ($salaryType) {
            case 'monthly':
                return $type === 'min' ? 5000 : 100000;
            case 'weekly':
                return $type === 'min' ? 1500 : 25000;
            case 'hourly':
                return $type === 'min' ? 100 : 2000;
            case 'negotiable':
                return null;
        }
    }
}
