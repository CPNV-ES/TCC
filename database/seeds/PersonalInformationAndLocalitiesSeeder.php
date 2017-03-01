<?php

// Author: S. Forsyth
// Date: 17.02.2017
// Modified by : ...
// Last Modif.: 20.01.17
// Description : Reads a modified version of the Excel file given to us (.csv) and
//                imports it into the database.

use Illuminate\Database\Seeder;
use App\Locality;

class PersonalInformationAndLocalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $filename = public_path().'/data/MembresSEP.csv';
      if(!file_exists($filename) || !is_readable($filename)) {
        return FALSE;
      }
      $header = NULL;
      $data = array();
      if (($handle = fopen($filename, 'r')) !== FALSE)
      {
          while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
          {
              $row = array_map("utf8_encode", $row); //added
              if(!$header)
                  $header = $row;
              else
                  $data[] = array_combine($header, $row);
          }
          fclose($handle);
      }

      foreach ($data as $value) {
        if ($value['NOM'] == '') {
          break;
        }
        else {
          $locality = Locality::whereNpa($value['NPA'])->first();

          if (!$locality && trim($value['VILLE']) != '') {
            DB::table('localities')->insert([
                'name' => trim(ucfirst(strtolower($value['VILLE']))),
                'NPA' => trim($value['NPA'])
            ]);
          }

          $locality = Locality::whereNpa($value['NPA'])->first();

          if ($locality) {
            $locality_id = $locality->id;
          }
          else {
            $locality_id = NULL;
          }

          $birthday = '';

          if ($value['BIRTHDAY'] != '') {
            $birthday = explode('.', $value['BIRTHDAY']);
            $day = trim($birthday[0]);
            $month = trim($birthday[1]);
            $year = trim($birthday[2]);
            $birthday = $year . '-' . $month . '-' . $day;
          }

          $phone = trim($value['TEL. PRIVE']);
          if (trim($value['NATEL']) != '') {
            $phone = trim($value['NATEL']);
          }


          DB::table('personal_informations')->insert([
              'firstname' => trim(ucfirst(strtolower($value['PRENOM']))),
              'lastname' => trim(ucfirst(strtolower($value['NOM']))),
              'street' => trim($value['Rue']),
              'streetNbr' => trim($value['NumRue']),
              'telephone' => $phone,
              'birthDate' => $birthday,
              'email' => trim($value['E-mail']),
              'toVerify' => 1,
              'fkLocality' => $locality_id
          ]);
        }
      }
    }
}
