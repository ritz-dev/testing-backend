<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\UniqueIDController;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $startTime = Carbon::createFromTime(10, 0, 0); // Start time: 10:00 AM
        $endTime = Carbon::createFromTime(18, 0, 0);   // End time: 8:00 PM

        $currentTime = $startTime->copy();
        $interval = CarbonInterval::minutes(15); // Time interval: 15 minutes

        while ($currentTime <= $endTime) {
            DB::table('booking_time')->insert([
                'uniqueid' => UniqueIDController::generateUniqueID('user'),
                'time_period' => $currentTime->format('H:i'), // Format: 24-hour format (e.g., 10:00, 10:15, etc.)
            ]);

            $currentTime->add($interval);
        }

        DB::table('roles')->insert([
            'name' => 'admin'
        ]);

        DB::table('roles')->insert([
            'name' => 'editor'
        ]);

        //migration for users
        DB::table('user')->insert([
            'uniqueid' => UniqueIDController::generateUniqueID('user'),
            'name' => 'Admin',
            'email' => 'superadmin@ritz.com.mm',
            'role_id' => 1,
	    'phone'=>"09123456789",
            'password' => Hash::make('12345678'),
        ]);

        //migration for time periods
        DB::table('service')->insert([
            'uniqueid' => UniqueIDController::generateUniqueID('service'),
            'service_name' => 'Hair Cut',
            'duration' => '45',
            'pricing' => '15000',
            'description' => 'Hair Styling',
        ]);

        DB::table('service')->insert([
            'uniqueid' => UniqueIDController::generateUniqueID('service'),
            'service_name' => 'Beard Trim',
            'duration' => '15',
            'pricing' => '5000',
            'description' => 'description',
        ]);

        DB::table('barber')->insert([
            'uniqueid' => UniqueIDController::generateUniqueID('barber'),
            'barber_name' => 'Kyaw Gyi',
            'barber_photo' => 'photo.jpg',
            'email' => 'kyawgyi@ritz.com.mm',
            'contact_number' => '09123456789',
            'base_salary' => '700000',
            'working_days'=>'1,2,3',
            'shop_id' => 'Yangon',
            'commission_rate' => 20,
            'join_date' => date('Y-m-d'),
        ]);

        DB::table('salary_date')->insert([
            'barber_uniqueid'=>DB::table('barber')->where('id',1)->get()->first()->uniqueid,
            'salary'=>DB::table('barber')->where('id',1)->get()->first()->base_salary,
            'updated_date' => now()->format('Y-m-d'),
        ]);

        DB::table('barber')->insert([
            'uniqueid' => UniqueIDController::generateUniqueID('barber'),
            'barber_name' => 'John Wick',
            'barber_photo' => 'photo.jpg',
            'email' => 'johnwick@ritz.com.mm',
            'contact_number' => '09987654321',
            'base_salary' => '700000',
            'working_days'=>'3,4,5,6',
            'shop_id' => 'Yangon',
            'commission_rate' => 25,
            'join_date' => date('Y-m-d'),

        ]);
        
        DB::table('salary_date')->insert([
            'barber_uniqueid'=>DB::table('barber')->where('id',2)->get()->first()->uniqueid,
            'salary'=>DB::table('barber')->where('id',2)->get()->first()->base_salary,
            'updated_date' => now()->format('Y-m-d'),
        ]);

        DB::table('customer')->insert([
            'uniqueid' => UniqueIDController::generateUniqueID('customer'),
            'name' => 'Cristiano Messi',
            'email' => 'cristianomessi@gmail.com',
            'dob' => now(),
            'contact_number' => '09934834832',
            'password' => Hash::make('12345678'),
        ]);

        DB::table('customer')->insert([
            'uniqueid' => UniqueIDController::generateUniqueID('customer'),
            'name' => 'Lionel Ronaldo',
            'email' => 'lionelronaldo@gmail.com',
            'dob' => now(),
            'contact_number' => '09934834833',
            'password' => Hash::make('12345678'),
        ]);

        DB::table('customer')->insert([
            'uniqueid' => UniqueIDController::generateUniqueID('customer'),
            'name' => 'kaungminhtet',
            'email' => 'kaungminhtet2712@gmail.com',
            'dob' => now(),
            'contact_number' => '09934834834',
            'password' => Hash::make('12345678'),
        ]);

        //seeding for Walk In Customers
        $unid=UniqueIDController::generateUniqueID('walk_in_customers');
        for ($i=0; $i < 2 ; $i++) { 
            DB::table('walk_in_customers')->insert([
                'uniqueid'=> $unid,
                'barber_id' => DB::table('barber')->get()->first()->uniqueid,
                'service_id' => DB::table('service')->where('id',$i+1)->get()->first()->uniqueid,
                'date' => date('Y-m-d'),
                'time_period_id' => DB::table('booking_time')->where('id', '=', '10')->get()->first()->uniqueid,
                'status'=>'complete',
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }
        

        //migration for sample bookings

        DB::table('booking')->insert([
            'uniqueid'=>UniqueIDController::generateUniqueID('booking'),
            'customer_id' => DB::table('customer')->get()->first()->uniqueid,
            'barber_id' => DB::table('barber')->get()->first()->uniqueid,
            'service_id' => DB::table('service')->get()->first()->uniqueid,
            'date' => date('Y-m-d'),
            'time_period_id' => DB::table('booking_time')->where('id', '=', '5')->get()->first()->uniqueid,
            'status' => 'active',
        ]);

        DB::table('booking')->insert([
            'uniqueid'=>UniqueIDController::generateUniqueID('booking'),
            'customer_id' => DB::table('customer')->skip(1)->take(1)->first()->uniqueid,
            'barber_id' => DB::table('barber')->skip(1)->take(1)->first()->uniqueid,
            'service_id' => DB::table('service')->skip(1)->take(1)->first()->uniqueid,
            'date' => date('Y-m-d'),
            'time_period_id' => DB::table('booking_time')->skip(1)->take(1)->first()->uniqueid,
            'status' => 'active',
        ]);

        DB::table('booking')->insert([
            'uniqueid'=>UniqueIDController::generateUniqueID('booking'),
            'customer_id' => DB::table('customer')->skip(1)->take(1)->first()->uniqueid,
            'barber_id' => DB::table('barber')->skip(1)->take(1)->first()->uniqueid,
            'service_id' => DB::table('service')->skip(1)->take(1)->first()->uniqueid,
            'date' => date('Y-m-d'),
            'time_period_id' => DB::table('booking_time')->skip(3)->take(1)->first()->uniqueid,
            'status' => 'active',
        ]);

        DB::table('shop_addresses')->insert([
            'uniqueid' => UniqueIDController::generateUniqueID('shop_addresses'),
            'address' => 'No.111, lorem street, lorem city, Yangon',
            'contact_number' => '+959 363 237 236'
        ]);
    }
}
