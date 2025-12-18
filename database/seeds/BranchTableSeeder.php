<?php

use Illuminate\Database\Seeder;
use App\BranchFilter;
use App\Branch;
use App\BranchContact;
use App\BranchSchedule;
class BranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	

        $data = [
                    [
                        'filter'=>'Manila',
                        'sub_data'=>[
                                        [
                                        'name' => 'Manila',
                                        'address' => '1858 L.M. Guerrero St, Malate, Maynila, 1004 Kalakhang Maynila, Philippines',
                                        'google_maps_api' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d174859.5916822187!2d121.0473750301119!3d14.49594882670883!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x8c20c4722e31a510!2sDaily%20Overland%20Freight%20Forwarder!5e0!3m2!1sen!2sus!4v1591854196903!5m2!1sen!2sus',
                                        'contacts'=>[

                                                    ],
                                        'schedules'=>[
                                                        [
                                                            'days_from'=>'Moday',
                                                            'days_to'=>'Saturday',
                                                            'time_from'=>'08:30:00',
                                                            'time_to'=>'17:30:00'
                                                        ],
                                                        [
                                                            'days_from'=>'Sunday',
                                                            'days_to'=>'Sunday',
                                                            'time_from'=>'01:00:00',
                                                            'time_to'=>'05:00:00'
                                                        ]
                                                    ]
                                        ]
                                    ]
                    ],
                    [
                        'filter'=>'Lucena',
                        'sub_data'=>[
                                        [
                                        'name' => 'Lucena',
                                        'address' => '70 Osmeña Street, Brgy 3 Lucena City, Philippines',
                                        'google_maps_api' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3872.9295565889247!2d121.61027671440773!3d13.939523783126763!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd4b5921782b6d%3A0x93acfd563e33559f!2s70+Osme%C3%B1a+Street%2C+Lucena%2C+Quezon%2C+Philippines!5e0!3m2!1sen!2s!4v1453874820081',
                                        'contacts'=>[
                                                        ['contact_no'=>'(042) 373 4560']
                                                    ],
                                        'schedules'=>[
                                                        [
                                                            'days_from'=>'Moday',
                                                            'days_to'=>'Saturday',
                                                            'time_from'=>'08:30:00',
                                                            'time_to'=>'17:30:00'
                                                        ],
                                                        
                                                        [
                                                            'days_from'=>'Sunday',
                                                            'days_to'=>'Sunday',
                                                            'time_from'=>'08:00:00',
                                                            'time_to'=>'12:00:00'
                                                        ]
                                                    ]
                                        ]
                                    ]
                    ],
                    [
                        'filter'=>'Bicol',
                        'sub_data'=>[
                                        [
                                            'name'=>'Daet',
                                            'address'=>'Sto. Niño Commercial Center, Brgy. 8, Dasmariñas St., Daet, Camarines Norte, Philippines',
                                            'google_maps_api'=>'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2736.0549068894916!2d122.95657365380988!3d14.11456317424884!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTTCsDA2JzUzLjIiTiAxMjLCsDU3JzI3LjgiRQ!5e0!3m2!1sen!2s!4v1453875889457',
                                            'contacts'=>[
                                                            ['contact_no'=>'(054) 440 3197'],
                                                            ['contact_no'=>'(054) 885 1427']
                                                        ],
                                            'schedules'=>[
                                                            [
                                                                'days_from'=>'Moday',
                                                                'days_to'=>'Saturday',
                                                                'time_from'=>'08:30:00',
                                                                'time_to'=>'17:30:00'
                                                            ],
                                                            
                                                            [
                                                                'days_from'=>'Sunday',
                                                                'days_to'=>'Sunday',
                                                                'time_from'=>'08:00:00',
                                                                'time_to'=>'12:00:00'
                                                            ]
                                                        ]
                                        ],
                                        [
                                            'name'=>'Naga',
                                            'address'=>'Romero Bldg, Peñafrancia, Naga City, Camarines Sur, Philippines',
                                            'google_maps_api'=>'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d643653.4303897425!2d123.06491907612539!3d13.442412377397709!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb3634a2345219701!2sDaily%20Overland%20Naga!5e0!3m2!1sen!2sus!4v1591854581756!5m2!1sen!2sus',
                                            'contacts'=>[
                                                            ['contact_no'=>'(054) 473 6276'],
                                                            ['contact_no'=>'(054) 881 0188']
                                                        ],
                                            'schedules'=>[
                                                            [
                                                                'days_from'=>'Moday',
                                                                'days_to'=>'Sunday',
                                                                'time_from'=>'08:30:00',
                                                                'time_to'=>'17:30:00'
                                                            ]
                                                            
                                                        ]
                                        ],
                                        [
                                            'name'=>'Polangui',
                                            'address'=>'Centro Occidental, Polangui, Albay, Philippines',
                                            'google_maps_api'=>'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3882.918936514485!2d123.48563431440418!3d13.293004211341746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTPCsDE3JzM0LjgiTiAxMjPCsDI5JzE2LjIiRQ!5e0!3m2!1sen!2s!4v1453877294157',
                                            'contacts'=>[
                                                            
                                                        ],
                                            'schedules'=>[
                                                            [
                                                                'days_from'=>'Moday',
                                                                'days_to'=>'Sunday',
                                                                'time_from'=>'08:30:00',
                                                                'time_to'=>'16:00:00'
                                                            ]
                                                            
                                                        ]
                                        ],
                                        [
                                            'name'=>'Ligao',
                                            'address'=>'Quililans Bldg., San Jose St., Dunao, Ligao City, Albay,Philippines',
                                            'google_maps_api'=>'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2309.304152008852!2d123.53751891627444!3d13.239672529178872!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTPCsDE0JzI0LjMiTiAxMjPCsDMyJzE4LjkiRQ!5e0!3m2!1sen!2s!4v1453877378537',
                                            'contacts'=>[
                                                            ['contact_no'=>'(052) 202 1889']
                                                        
                                                        ],
                                            'schedules'=>[
                                                            [
                                                                'days_from'=>'Moday',
                                                                'days_to'=>'Saturday',
                                                                'time_from'=>'08:30:00',
                                                                'time_to'=>'17:30:00'
                                                            ],
                                                            [
                                                                'days_from'=>'Sunday',
                                                                'days_to'=>'Sunday',
                                                                'time_from'=>'13:00:00',
                                                                'time_to'=>'17:00:00'
                                                            ]
                                                            
                                                        ]
                                        ],
                                        [
                                            'name'=>'Daraga',
                                            'address'=>'JRE Building, Rizal St., Daraga, Albay, Philippines',
                                            'google_maps_api'=>'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d21978.4747427832!2d123.71050505862023!3d13.141082623718782!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x5837d108ea4a4732!2sDaily%20Overland!5e0!3m2!1sen!2sus!4v1591858248706!5m2!1sen!2sus',
                                            'contacts'=>[
                                                            ['contact_no'=>'(052) 204 9307']
                                                        
                                                        ],
                                            'schedules'=>[
                                                            [
                                                                'days_from'=>'Moday',
                                                                'days_to'=>'Saturday',
                                                                'time_from'=>'08:30:00',
                                                                'time_to'=>'17:30:00'
                                                            ],
                                                            [
                                                                'days_from'=>'Sunday',
                                                                'days_to'=>'Sunday',
                                                                'time_from'=>'13:00:00',
                                                                'time_to'=>'17:00:00'
                                                            ]
                                                            
                                                        ]
                                        ],
                                        [
                                            'name'=>'Legazpi',
                                            'address'=>'Long Se Bldg, Rizal St., Legazpi City, Albay',
                                            'google_maps_api'=>'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3885.268329853784!2d123.75058021430603!3d13.14546701459723!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a1033716fa3989%3A0xb044632718fabd5!2sDaily%20Overland!5e0!3m2!1sen!2sus!4v1591853568197!5m2!1sen!2sus',
                                            'contacts'=>[
                                                            ['contact_no'=>'(052) 201 9271']
                                                        
                                                        ],
                                            'schedules'=>[
                                                            [
                                                                'days_from'=>'Moday',
                                                                'days_to'=>'Saturday',
                                                                'time_from'=>'08:30:00',
                                                                'time_to'=>'17:30:00'
                                                            ],
                                                            [
                                                                'days_from'=>'Sunday',
                                                                'days_to'=>'Sunday',
                                                                'time_from'=>'08:00:00',
                                                                'time_to'=>'12:00:00'
                                                            ]
                                                            
                                                        ]
                                        ],

                                        [
                                            'name'=>'Tabaco',
                                            'address'=>'Long Se Bldg, Rizal St., Legazpi City, Albay',
                                            'google_maps_api'=>'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d43920.83612973256!2d123.72004512324828!3d13.344669147439866!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x2391fd552f6fe816!2sdaily%20overland%20tabaco%20branch!5e0!3m2!1sen!2sus!4v1591853675813!5m2!1sen!2sus',
                                            'contacts'=>[
                                                            ['contact_no'=>'(052) 487 5066 '],
                                                            ['contact_no'=>'(052) 431 0083']
                                                        
                                                        ],
                                            'schedules'=>[
                                                            [
                                                                'days_from'=>'Moday',
                                                                'days_to'=>'Saturday',
                                                                'time_from'=>'08:30:00',
                                                                'time_to'=>'17:30:00'
                                                            ],
                                                            [
                                                                'days_from'=>'Sunday',
                                                                'days_to'=>'Sunday',
                                                                'time_from'=>'13:00:00',
                                                                'time_to'=>'17:00:00'
                                                            ]
                                                            
                                                        ]
                                        ],

                                        

                                        [
                                            'name'=>'Sorsogon',
                                            'address'=>'No. 3781 Rizal St.,Sorsogon City',
                                            'google_maps_api'=>'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3266.8541339351696!2d123.99802219222373!3d12.969972421307544!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x57b17a624b24f2fa!2sDaily%20Overland%20Freight%20Forwarder!5e0!3m2!1sen!2sus!4v1591854325980!5m2!1sen!2sus',
                                            'contacts'=>[
                                                            ['contact_no'=>'(056) 255 9309']
                                                        
                                                        ],
                                            'schedules'=>[
                                                            [
                                                                'days_from'=>'Moday',
                                                                'days_to'=>'Saturday',
                                                                'time_from'=>'08:30:00',
                                                                'time_to'=>'17:30:00'
                                                            ],
                                                            [
                                                                'days_from'=>'Sunday',
                                                                'days_to'=>'Sunday',
                                                                'time_from'=>'13:00:00',
                                                                'time_to'=>'17:00:00'
                                                            ]
                                                            
                                                        ]
                                        ]
                                    ]
                    ],
                ];

        foreach($data as $d){
            $filter=BranchFilter::firstOrNew(['name'=>$d['filter']]);
            if(!$filter->exists){
                $filter->save();
            }

            foreach($d['sub_data'] as $r){
                $branch=Branch::firstOrNew(['name'=>$r['name']]);
                if(!$branch->exists){
                    $branch->fill(['address'=>$r['address'],'google_maps_api'=>$r['google_maps_api'],'branch_filter_id'=>$filter->id])->save();
                    foreach($r['contacts'] as $c){
                        $branch_contact=BranchContact::firstOrNew(['branch_id'=>$branch->id,'contact_no'=>$c['contact_no']]);
                        if(!$branch_contact->exists){
                            $branch_contact->save();
                        }    
                    }

                    foreach($r['schedules'] as $s){
                        $branch_schedule=BranchSchedule::firstOrNew([
                                            'branch_id'=>$branch->id,
                                            'days_from'=>$s['days_from'],
                                            'days_to'=>$s['days_to'],
                                            'time_from'=>$s['time_from'],
                                            'time_to'=>$s['time_to']
                                        ]);
                        if(!$branch_schedule->exists){
                            $branch_schedule->save();
                        }
                    }
                    
                }
            }



        }

    	// foreach($data_filter as $row){
    	// 	$filter = BranchFilter::firstOrNew(['name'=>$row]);
    	// 	if(!$filter->exists){
    	// 		$filter->save();
    	// 	}	
    	// }
    	

        
    }
}
