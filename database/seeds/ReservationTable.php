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
      $playerCount = [];
      foreach ($courts as $court) {
        $toAddTable = [];
        for ($x=0; $x <= 10; $x++) {
          for ($i=0; $i < 7 ; $i++) {
            $dateInsert = date_time_set(date_create(date("Y-m-d H:i:s")), 8,0,0);
            $dateInsert = $dateInsert->format('Y-m-d H:i:s');

            do {
              $who = rand(1, $nbMembers);
              $withWho = rand(1, $nbMembers);
            } while ($who == $withWho);

            if ($x <= 5) {
              $toAdd = '+'.$x.' days ';
              if (!array_key_exists($who, $playerCount)) {
                $playerCount[$who] = 1;
              }
              if (!array_key_exists($withWho, $playerCount)) {
                $playerCount[$withWho] = 1;
              }
            } else {
              $toAdd = '-'.($x - 5).' days ';
            }
            $toAdd .= '+'.rand(0,11).' hours';

            $dateInsert = date('Y-m-d H:i:s', strtotime($dateInsert . $toAdd));
            $dateInsert = date_format(date_create($dateInsert), 'Y-m-d H');

            if (!in_array($dateInsert, $toAddTable)) {
              $toAddTable[] = $dateInsert;
              if ($x > 5) {

              }
              elseif ($playerCount[$who] < 4 && $playerCount[$withWho] < 4) {
                if ($x <= 5) {
                  $playerCount[$who] += 1;
                  $playerCount[$withWho] += 1;
                }
              }

              DB::table('reservations')->insert([
                  'dateTimeStart' => $dateInsert,
                  'fkWho' => $who,
                  'fkWithWho' => $withWho,
                  'fkTypeReservation' => 1,
                  'fkCourt' => $court->id,
                  'chargeAmount' => rand(0,50) . '.' . rand(0,95),
                  'paid' => rand(0,1)
              ]);
            }
            else {
              $i--;
            }
          }
        }
      }
    }
}
