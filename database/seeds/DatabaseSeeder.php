<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('members')->insert([
         'last_name' => 'admin',
         'first_name' => 'admin',
         'address' => 'CPNV',
         'zip_code' => '1450',
         'city' => 'CPNV',
         'email' => env('ADMIN_MAIL','admin'),
         'mobile_phone'=>'000000000',
         'home_phone'=>'000000000',
         'birth_date'=>'1980-01-01',
         'password' => bcrypt(env('ADMIN_PASS','admin')),
         'login' => env('ADMIN_LOGIN','admin'),
         'token' => '',
         'active' => '1',
         'to_verify' => '0',
         'validate' => '1',
         'administrator' => '1',
         'created_at' => '2017-01-10 13:58:14',
         'updated_at' => '2017-01-10 13:58:14'
       ]);
      // A REVOIR Essayer de prendre des valeurs réaliste
//      for($i=1;$i<10;$i++)
//      {
//
//          DB::table('members')->insert([
//              'last_name' => 'test'.$i,
//              'first_name' => 'Nom'.$i,
//              'address' => 'CPNV',
//              'zip_code' => '1450',
//              'city' => 'CPNV',
//              'email' => 'test.test'.$i.'@test.dev',
//              'mobile_phone'=>'000000000',
//              'home_phone'=>'000000000',
//              'birth_date'=>'1980-01-01',
//              'password' => 'test',
//              'login' => 'test'.$i,
//              'token' => '',
//              'active' => $i%2,
//              'to_verify' => '0',
//              'validate' => '1',
//              'administrator' => '0',
//              'created_at' => '2017-01-10 13:58:14',
//              'updated_at' => '2017-01-10 13:58:14'
//          ]);
//      }
       DB::table('subscriptions')->insert([
          'status' => 'member',
          'amount' => '10',
        ]);
        DB::table('subscriptions')->insert([
           'status' => 'responsible',
           'amount' => '0',
        ]);
        DB::table('seasons')->insert([
           'begin_date' => (new \DateTime())->sub(new DateInterval('P10D'))->format('Y-m-d'),
           'end_date' => (new \DateTime())->add(new DateInterval('P6M'))->format('Y-m-d'),
        ]);
    }
}
