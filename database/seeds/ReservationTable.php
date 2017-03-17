<?php

use Illuminate\Database\Seeder;
use App\Court;
use App\User;

class ReservationTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $courts = Court::all();
      $nbMembers = User::all()->count();
      foreach ($courts as $court) {
        $toAddTable = [];
        for ($i=0; $i < 300 ; $i++) {
          $dateInsert = date("Y-m-d H:i:s");
          if (rand(0,1)) {
            $toAdd = '+'.rand(0,10).' days ';
          } else {
            $toAdd = '-'.rand(0,10).' days ';
          }
          if (rand(0,1)) {
            $toAdd .= '+'.rand(0,12).' hours';
          } else {
            $toAdd .= '-'.rand(0,12).' hours';
          }

          $dateInsert = date('Y-m-d H:i:s', strtotime($dateInsert . $toAdd));
          $dateInsert = date_format(date_create($dateInsert), 'Y-m-d H');

          if (!in_array($dateInsert, $toAddTable)) {
            $toAddTable[] = $dateInsert;

            DB::table('reservations')->insert([
                'dateTimeStart' => $dateInsert,
                'fkWho' => rand(1, $nbMembers),
                'fkWithWho' => rand(1, $nbMembers),
                'fkTypeReservation' => 1,
                'fkCourt' => $court->id,
                'chargeAmount' => rand(0,50) . '.' . rand(0,95),
                'paid' => rand(0,1)
            ]);
          }
        }
      }
    }
}
