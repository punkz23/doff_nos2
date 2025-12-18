<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = ['browse bookings','create booking','show bookings','edit booking','delete printer','browse contacts','create contacts','show contact','edit contact','delete contact','browse faqs','create faq','show faq','edit faq','delete faq','browse branches','create branch','show branch','edit branch','delete branch'];

        foreach($permissions as $name){
        	$permission=Permission::firstOrNew(['name'=>$name]);
        	if(!$permission->exists){
        		$permission->save();		
        	}
        }

        $admin = Role::firstOrNew(['name'=>'Admin']);

        if(!$admin->exists){
        	Role::create(['name' => 'Admin'])
            ->givePermissionTo(['browse faqs','create faq','show faq','edit faq','delete faq','browse branches','create branch','show branch','edit branch','delete branch']);
        }

        $client = Role::firstOrNew(['name'=>'Client']);

        if(!$client->exists){
        	Role::create(['name' => 'Client'])
            ->givePermissionTo(['browse bookings','create booking','show bookings','edit booking','delete printer','browse contacts','create contacts','show contact','edit contact','delete contact']);
        }
        
    }
}
