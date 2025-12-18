<?php

use Illuminate\Database\Seeder;
use App\FAQ\Category;
use App\FAQ\Dialect;
class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['General', 'Account','Pangkalahatan','Account'];
        
        foreach($categories as $key=>$name){
            
        	$category = Category::firstOrNew(['name'=>$name]);
        	if(!$category->exists){
                $dialect_id=$key<=1 ? Dialect::where('name','English')->first()->id : Dialect::where('name','Tagalog')->first()->id;
        		$category->fill(['dialect_id'=>$dialect_id])->save();
        	}
        } 
    }
}
