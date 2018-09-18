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

        // Add user
        $general_user = User::create([
            'name' => 'Alex Rindone',
            'email' => 'arindone1679@gmail.com',
            'password' => Hash::make('iapp@123'),
            'street' => '9 Mill Pond Rd',
            'city' => 'Northwood',
            'state' => 'NH',
            'zip' => '03261',
            'commutesmart_frequency' => 'Once or twice a month',
            'company_id' => $company->id
        ]);

        // Add Challenge

        $challenge = Challenge::create([
            'name' => 'Conquer the Cold 2018',
            'slug' => 'conquer',
            'start_date' => '2018-11-01',
            'end_date' => '2018-12-31',
            'type' => 'individual',
            'image_url' => 'http://commutesmartseacoast.org/wp-content/themes/seacoast/images/conquer-the-cold-banner.png'
        ]);
        $modes = ['Bus/Train', 'Bicycle', 'Moped', 'Multi-Modal', 'Walk/Run', 'Skateboard/Rollerblades'];
        // loop through and create 6000 trips
            $trips = [];
            for ($i=0; $i < 6000; $i++) {
                $trips[] = [
                    'mode' => $modes[array_rand($modes, 1)],
                    'date' => '2018-11-10',
                    'miles' => rand(10, 30) - 5,
                    'challenge_id' => $challenge->id,
                    'user_id' => $general_user->id
                ]; 
            }
        $trips = Trip::insert($trips);
    }
}
