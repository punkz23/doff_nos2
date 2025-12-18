<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\ReferenceTrait;
use App\OnlineSite\User as OnlineSiteUser;
use App\User;
class UsersTableSeeder extends Seeder
{
	use ReferenceTrait;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_users = OnlineSiteUser::get();

        foreach($old_users as $row){
        	$new_user = User::firstOrNew(['email'=>$row->user_name]);
        	if(!$new_user->exists){
        		$new_user->fill(['password'=>'','contact_id'=>$row->contact_id,'fb_id'=>$row->fb_id,'oauth_provider'=>$row->oauth_provider,'oauth_id'=>$row->oauth_id])->save();
        	}
        }
    }
}
