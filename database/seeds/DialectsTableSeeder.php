<?php

use Illuminate\Database\Seeder;
use App\FAQ\Dialect;
class DialectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dialects = ['English','Tagalog'];

        foreach($dialects as $name){
        	$dialect = Dialect::firstOrNew(['name'=>$name]);
        	if(!$dialect->exists){
        		$dialect->save();
        	}
        }
    }
}
