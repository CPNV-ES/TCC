<?php

// Author: S. Forsyth
// Date: 17.02.2017
// Modified by : ...
// Last Modif.: 20.01.17
// Description : Reads a modified version of the Excel file given to us (.csv) and
//                imports it into the database.

use Illuminate\Database\Seeder;
use App\Locality;
use App\PersonalInformation;
use App\User;

class PersonalInformationAndLocalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $firstRun = true;

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
          if ($firstRun) {
            $firstRun = false;
            DB::table('personal_informations')->insert([
                'firstname' => 'Jesuis',
                'lastname' => 'Ladmin',
                'street' => 'Chemin des Codeurs',
                'streetNbr' => '777',
                'telephone' => '0236549875',
                'birthDate' => '1973-05-15',
                'email' => 'jesuis.ladmin@tcc.ch',
                'toVerify' => 0,
                'fkLocality' => $locality_id
            ]);

            DB::table('users')->insert([
                'username' => env('ADMIN_LOGIN','admin'),
                'password' => bcrypt(env('ADMIN_PASS','admin')),
                'active' => 1,
                'invitRight' => 1,
                'validated' => 1,
                'isAdmin' => 1,
                'isTrainer' => 0,
                'isMember' => 1,
                'fkPersonalInformation' => 1
            ]);
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

          $firstname = trim(ucfirst(strtolower($value['PRENOM'])));
          $lastname = trim(ucfirst(strtolower($value['NOM'])));

          $newPI = PersonalInformation::create([
              'firstname' => $firstname,
              'lastname' => $lastname,
              'street' => trim($value['Rue']),
              'streetNbr' => trim($value['NumRue']),
              'telephone' => $phone,
              'birthDate' => $birthday,
              'email' => str_replace(' ', '', trim($value['E-mail'])),
              'toVerify' => rand(0,1),
              'fkLocality' => $locality_id
          ]);

          $firstname = self::normalize($firstname);
          $lastname = strtoupper($lastname);
          $middleLetter = substr($lastname, (strlen($lastname) / 2), 1);
          $login = strtolower($firstname) . substr($lastname, 0, 1) . $middleLetter . substr($lastname, -1, 1);
          $password = password_hash('1234', PASSWORD_BCRYPT);
          $personID = $newPI->id;

          $newUser = User::create([
            'username' => $login,
            'password' => $password,
            'active' => rand(0,1),
            'invitRight' => rand(0,1),
            'validated' => rand(0,1),
            'isAdmin' => rand(0,1),
            'isMember' => rand(0,1),
            'isTrainer' => rand(0,1),
            'fkPersonalInformation' => $personID,
          ]);

        }
      }
    }

    function normalize ($string) {
        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
        );

        return strtr($string, $table);
    }

}
