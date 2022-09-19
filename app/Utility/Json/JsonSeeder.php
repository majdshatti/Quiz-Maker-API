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

        foreach ($jsonDecoded as $key => $value) {
            $newValue = (array) $value;
            $newValue["created_at"] = date("Y-m-d H:i:s");
            $newValue["updated_at"] = date("Y-m-d H:i:s");
            DB::table($tableName)->insert($newValue);
        }
        
    }
}
