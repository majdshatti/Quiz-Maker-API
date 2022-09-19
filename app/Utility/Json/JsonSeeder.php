<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Read json files to seed the application's database.
 *
 * @param  $tableName
 * @param  $path
 *
 * @return void
 */

if (!function_exists("jsonSeeder")) {
    function jsonSeeder($tableName, $path)
    {
        if (!(Schema::hasTable($tableName))) {
            return;
        }

        $jsonFile = File::get($path);
        $jsonDecoded = json_decode($jsonFile);

        print_r($jsonDecoded);
        foreach ($jsonDecoded as $key => $value) {
            print_r($value);
            //DB::table($tableName)->insert($value);
            DB::table($tableName)->save($value);
        }
        
    }
}
