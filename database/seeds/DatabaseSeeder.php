<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Challenge;
use App\Trip;
use App\Company;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // Add Company
        $company = Company::create([
            'name' => 'IAPP',
            'employee_count' => 100,
            'size' => 'Large'
        ]);

        $secondCompany = Company::create([
            'name' => 'ABC',
            'employee_count' => 20,
            'size' => 'Small'
        ]);

        // Add user
        $general_user = User::create([
            'name' => 'Seth Bridges',
            'email' => 'matvmp7@gmail.com',
            'password' => Hash::make('12345678'),
            'street' => '9 Mill Pond Rd',
            'city' => 'Northwood',
            'state' => 'NH',
            'zip' => '03261',
            'company_id' => $company->id
        ]);

        // Add Challenge
        $challenge = Challenge::create([
            'name' => 'Conquer the Cold 2018',
            'slug' => 'conquer',
            'start_date' => '2018-11-01',
            'end_date' => '2018-12-31',
            'type' => 'Individual',
            'image_url' => '/images/conquer-the-cold-banner.png'
        ]);
    }
}
