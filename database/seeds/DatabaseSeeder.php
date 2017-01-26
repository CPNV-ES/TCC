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
      // ESO: Adding default admin
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
        $first_name = ['Franklin','Micheal','Joesph','Jérémie','Christian', 'Gabrielle','Kim','Tiffany','Lee','Emilie'];
        $last_name = ['Favre','Jeanmonot','De la Gouj', 'L\'Eplattenier','Gaille','Major','Adma','Rizz','Bergerman','Bakerman'];

        $address = ['Rue de la gare','Rue de la boulangerie','Rue des plalet','Route de l\'orge', 'route des pèlerin', 'route du château'];
        $locality = ['Provence', 'Ste-Croix','Vuitevoeuf','Baumes','Charvonnay','Yverdon'];
        for($i=1;$i<10;$i++)
        {
            $lName=$last_name[mt_rand(0,sizeof($last_name)-1)];
            $fName=$first_name[mt_rand(0,sizeof($first_name)-1)];
            $addr = $address[mt_rand(0,sizeof($address)-1)];
            $local = $locality[mt_rand(0,sizeof($address)-1)];
            DB::table('members')->insert([
                'last_name' => $lName,
                'first_name' => $fName,
                'address' => $addr,
                'zip_code' => mt_rand(1000,9999),
                'city' => $local,
                'email' => $fName.$i.'@test.dev',
                'mobile_phone'=>mt_rand(1000000000,9999999999),
                'home_phone'=>mt_rand(1000000000,9999999999),
                'birth_date'=>date("Y-M-d",mt_rand('1',time()-72*30)),//'1980-01-01',
                'password' => bcrypt('test'),
                'login' => $fName.$i,
                'token' => '',
                'active' => mt_rand(0,1),
                'to_verify' => mt_rand(0,1),
                'validate' => mt_rand(0,1),
                'administrator' => '0',
                'created_at' => '2017-01-10 13:58:14',
                'updated_at' => '2017-01-10 13:58:14'
            ]);
        }

       // ESO: add of subscriptions and seasons
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
