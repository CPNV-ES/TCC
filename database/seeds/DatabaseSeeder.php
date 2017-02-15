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
//      // ESO: Adding default admin
//      DB::table('members')->insert([
//         'last_name' => 'admin',
//         'first_name' => 'admin',
//         'address' => 'CPNV',
//         'zip_code' => '1450',
//         'city' => 'CPNV',
//         'email' => env('ADMIN_MAIL','admin'),
//         'mobile_phone'=>'000000000',
//         'home_phone'=>'000000000',
//         'birth_date'=>'1980-01-01',
//         'password' => bcrypt(env('ADMIN_PASS','admin')),
//         'login' => env('ADMIN_LOGIN','admin'),
//         'token' => '',
//         'active' => '1',
//         'to_verify' => '0',
//         'validate' => '1',
//         'administrator' => '1',
//         'created_at' => '2017-01-10 13:58:14',
//         'updated_at' => '2017-01-10 13:58:14'
//       ]);
//        DB::table('subscriptions')->insert([
//            'status' => 'member',
//            'amount' => '10',
//        ]);
//        DB::table('subscriptions')->insert([
//            'status' => 'responsible',
//            'amount' => '0',
//        ]);
//        DB::table('seasons')->insert([
//            'begin_date' => (new \DateTime())->sub(new DateInterval('P10D'))->format('Y-m-d'),
//            'end_date' => (new \DateTime())->add(new DateInterval('P6M'))->format('Y-m-d'),
//        ]);
//        //IGI adding test value
//        $first_name = ['Franklin','Micheal','Joesph','Jérémie','Christian', 'Gabrielle','Kim','Tiffany','Lee','Emilie'];
//        $last_name = ['Favre','Jeanmonot','De la Gouj', 'L\'Eplattenier','Gaille','Major','Adma','Rizz','Bergerman','Bakerman'];
//
//        $address = ['Rue de la gare','Rue de la boulangerie','Rue des plalet','Route de l\'orge', 'route des pèlerin', 'route du château'];
//        $locality = ['Provence', 'Ste-Croix','Vuitevoeuf','Baumes','Charvonnay','Yverdon'];
//        for($i=1;$i<10;$i++)
//        {
//            $lName=$last_name[mt_rand(0,sizeof($last_name)-1)];
//            $fName=$first_name[mt_rand(0,sizeof($first_name)-1)];
//            $addr = $address[mt_rand(0,sizeof($address)-1)];
//            $local = $locality[mt_rand(0,sizeof($address)-1)];
//            DB::table('members')->insert([
//                'id' => '1'.$i,
//                'last_name' => $lName,
//                'first_name' => $fName,
//                'address' => $addr,
//                'zip_code' => mt_rand(1000,9999),
//                'city' => $local,
//                'email' => $fName.$i.'@test.dev',
//                'mobile_phone'=>'0'.mt_rand(100000000,999999999),
//                'home_phone'=>'0'.mt_rand(100000000,999999999),
//                'birth_date'=>date("Y-m-d",mt_rand('1',time()-72*30)),//'1980-01-01',
//                'password' => bcrypt('test'),
//                'login' => $fName.$i,
//                'token' => '',
//                'active' => mt_rand(0,1),
//                'to_verify' => mt_rand(0,1),
//                'validate' => '1',
//                'administrator' => '0',
//                'created_at' => '2017-01-10 13:58:14',
//                'updated_at' => '2017-01-10 13:58:14'
//            ]);
//            DB::table('subscriptions_per_member')->insert([
//                'fk_member' => '1'.$i,
//                'fk_season' => '1',
//                'fk_subscription' => '1'
//
//            ]);
//        }
        //*** COURT *** we define id to use it as foreign keys
        DB::table('courts')->insert([
            'id' => 1,
            'name' => 'Montagne',
            'state' => '1',
            'nbDays' => '2',
        ]);

        DB::table('courts')->insert([
            'id' => 2,
            'name' => 'Yverdon',
            'state' => '1',
            'nbDays' => '2'
        ]);

        DB::table('courts')->insert([
            'id' => 3,
            'name' => 'Indoor',
            'state' => '1',
            'nbDays' => '2'
        ]);
        // *** TYPE RESERVATION ***
        DB::table('type_reservations')->insert([
            'type' => 'aucune'
        ]);
        DB::table('type_reservations')->insert([
            'type' => 'quotidienne'
        ]);
        DB::table('type_reservations')->insert([
            'type' => 'hebdomadaire'
        ]);
        DB::table('type_reservations')->insert([
            'type' => 'mensuel'
        ]);

        // *** LOCALITY ***
        DB::table('localities')->insert([
            'id' => 1,
            'name' => 'Yverdon-Les-Bains',
            'NPA' => 1400
        ]);
        DB::table('localities')->insert([
            'id' =>2,
            'name' => 'Lausanne',
            'NPA' => 1001
        ]);
        DB::table('localities')->insert([
            'id' =>3,
            'name' => 'Ste-Croix',
            'NPA' => 1450
        ]);



        // *** SEASONS ***
        DB::table('seasons')->insert([
            'id' =>1,
            'dateStart' => '2017-02-01',
            'dateEnd' => '2017-08-01'
        ]);
        DB::table('seasons')->insert([
            'id' =>2,
            'dateStart' => '2016-06-01',
            'dateEnd' => '2016-12-01'
        ]);
        DB::table('type_subscriptions')->insert([
            'status' => 'membre',
            'amount' => 20.90
        ]);
        DB::table('type_subscriptions')->insert([
            'status' => 'responsable',
            'amount' => 5,
        ]);
        DB::table('type_subscriptions')->insert([
            'status' => 'admin',
            'amount' => 1,
        ]);

        // *** CONFIGS ***
        DB::table('configs')->insert([
            'nbDaysGracePeriod' => 10,
            'nbDaysLimitNonMember' => 5,
            'courtOpenTime' => '08:00:00',
            'courtCloseTime' => '17:00:00'
        ]);


        //*** PERSONAL INFORMATION ***
        DB::table('personal_informations')->insert([
            'id' => 1,
            'firstname' => 'Frank',
            'lastname' => 'Dero',
            'street' => 'Rue de la france',
            'streetNbr' => '2b',
            'telephone' => '0244564545',
            'email' => 'frank.dero@test.test',
            'toVerify' => 1,
            'fkLocality' => 1
        ]);
        DB::table('personal_informations')->insert([
            'id' => 2,
            'firstname' => 'Mike',
            'lastname' => 'Orok',
            'street' => 'Rue de la Suisse',
            'streetNbr' => '1',
            'telephone' => '0244123545',
            'email' => 'm.orok@test.test',
            'toVerify' => 1,
            'fkLocality' => 2
        ]);
        DB::table('personal_informations')->insert([
            'id' => 3,
            'firstname' => 'Michelle',
            'lastname' => 'Derouge',
            'street' => 'Rue de la gare',
            'streetNbr' => '4',
            'telephone' => '0244123512',
            'email' => 'm.derouge@test.test',
            'toVerify' => 1,
            'fkLocality' => 3
        ]);
        // *** RESERVATIONS ***
        DB::table('reservations')->insert([
            'id' => 1,
            'dateStart' => '2016-12-15',
            'dateEnd' => '2016-12-15',
            'hourStart' => '10:00:00',
            'hourEnd' => '11:00:00',
            'fkWho' => 2,
            'fkWithWho' => 3,
            'fkTypeReservation' => 1,
            'fkCourt' => 1,
            'chargeAmount' => 10.00,
            'paid' => 0
        ]);
        DB::table('reservations')->insert([
            'id' => 2,
            'dateStart' => '2016-12-15',
            'dateEnd' => '2016-12-15',
            'hourStart' => '09:00:00',
            'hourEnd' => '08:00:00',
            'fkWho' => 1,
            'fkWithWho' => 2,
            'fkTypeReservation' => 1,
            'fkCourt' => 1,
            'chargeAmount' => 10.00,
            'paid' => 0
        ]);
        DB::table('reservations')->insert([
            'id' => 3,
            'dateStart' => '2016-12-15',
            'dateEnd' => '2016-12-15',
            'hourStart' => '09:00:00',
            'hourEnd' => '08:00:00',
            'fkWho' => 1,
            'fkTypeReservation' => 1,
            'fkCourt' => 1,
            'chargeAmount' => 10.00,
            'paid' => 0
        ]);
        //*** INVITATIONS ***
        DB::table('users')->insert([
            'id' => 1,
            'username' => "franky",
            'password' => bcrypt('test'),
            'active' => 1,
            'invitRight' => 1,
            'validated' => 1,
            'isAdmin' => 1,
            'isTrainer' => 1,
            'isMember' => 1,
            'fkPersonalInformation' => 1
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'username' => env('ADMIN_LOGIN','admin'),
            'password' => bcrypt(env('ADMIN_PASS','admin')),
            'active' => 1,
            'invitRight' => 1,
            'validated' => 1,
            'isAdmin' => 1,
            'isTrainer' => 1,
            'isMember' => 1,
            'fkPersonalInformation' => 2
        ]);
        DB::table('users')->insert([
            'id' => 3,
            'username' => "michelle",
            'password' => bcrypt('test'),
            'active' => 1,
            'invitRight' => 1,
            'validated' => 1,
            'isAdmin' => 1,
            'isTrainer' => 1,
            'isMember' => 1,
            'fkPersonalInformation' => 3
        ]);
       //*** subscriptions
        DB::table('subscriptions')->insert([
            'paid' => 0,
            'fkSeason' => 1,
            'fkTypeSubscription' => 1,
            'fkUser' => 1
        ]);
        DB::table('subscriptions')->insert([
            'paid' => 1,
            'fkSeason' => 1,
            'fkTypeSubscription' => 2,
            'fkUser' => 2
        ]);
        DB::table('subscriptions')->insert([
            'paid' => 1,
            'fkSeason' => 1,
            'fkTypeSubscription' => 1,
            'fkUser' => 3
        ]);
        // *** USERS ***


    }
}
