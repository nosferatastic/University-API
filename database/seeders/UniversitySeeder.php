<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use DB;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       
        $csvFile = fopen("./unis.csv", "r") or die("Could not find file");
        $i = 0;
        while (! feof($csvFile)) {
            $row = fgetcsv($csvFile, 0, ",");
            if(!empty($row) && $row[0]) {
                $uni = \App\Models\University::create([
                    'name' => $row[0],
                    'description' => "This is the description for ".$row[0].".",
                    'logo_image_path' => 'https://picsum.photos/200',
                    'is_premium' => $i % 2,
                    'enabled' => ($i % 3) % 2,
                    'phone_number' => $row[2],
                    'address' => $row[1],
                    'website' => $row[3]
                ]);
                $uni->save();
            }
            $i ++;
        }
        fclose($csvFile);
    }
}
