<?php

use Illuminate\Database\Seeder;
use App\FAQ\Platform;
class PlatformsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $platforms = ['Website','Mobile'];

        foreach($platforms as $name){
        	$platform = Platform::firstOrNew(['name'=>$name]);
        	if(!$platform->exists){
        		$platform->save();
        	}
        }
    }
}
