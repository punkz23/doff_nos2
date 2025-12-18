<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OnlineSite\ContactGuest;
use App\OnlineSite\Incident;
use App\OnlineSite\IncidentFile;
use App\OnlineSite\IncidentWaybill;
use App\Waybill\IncidentCategory;
use App\Waybill\Incident as WaybillIncident;
use App\Waybill\IncidentFile as WaybillIncidentFile;
use App\Waybill\IncidentWaybill as WaybillIncidentWaybill;
use App\Http\Controllers\ReferenceTrait;
use Validator;
use Carbon\Carbon;
use Auth;
use Storage;
use DB;
use App\Functions\DOFF_Functions;

class IncidentController extends Controller
{

    use ReferenceTrait;

    public function store_complain(Request $request){

        $doff_functions = new DOFF_Functions();

        $title='';$message='';$type='';$data=null;$errors=null;$status_code=200;
        $request->merge([
            'logged_in'=>Auth::check()==true ? 1 : 0,
            'required_tracking'=>in_array($request->incident_category_id,[4,11]) ? 1 : 0,
            'tracking_no'=>explode(",",$request->tracking_no)
        ]);

        $validations = Validator::make($request->all(),[
            'lname'=>'required_if:logged_in,0',
            'fname'=>'required_if:logged_in,0',
            'contact_no'=>'required_if:logged_in,0',
            'email'=>'required_if:logged_in,0|email',
            'tracking_no.*'=>'required_if:required_tracking,1|exists:waybill.tblwaybills,tracking_no',
            'incident_subject'=>'required',
            'incident_category_id'=>'required|exists:waybill.tblincident_category,incident_category_id'
        ],
        [
            'lname.required_if'=>'Lastname is required',
            'fname.required_if'=>'Firstname is required',
            'contact_no.required_if'=>'Contact # is required',
            'email.required'=>'Email is required',
            'email.email'=>'The email input must be a valid email address',
            'tracking_no.*.required_if'=>'Tracking # is required',
            'tracking_no.*.exists'=>'There is invalid tracking number',
            'incident_subject.required'=>'Subject is required',
            'incident_category_id.required'=>'Category is required',
            'incident_category_id.exists'=>'The selected category is invalid'
        ]);




        if($validations->fails()){
            foreach($validations->getMessageBag()->toArray() as $key=>$messages) {
                $new_key = preg_match( '/^tracking_no.*/', $key) ? 'tracking_no' : $key;
                $errors[$new_key] = $messages;
            }
            $title='Ooops!';$message='Please fill required fields';$type='error';$errors=$validations->errors();
            $status_code = 422;
        }
        else{
            # INSERT TO OLD IR

            $incident_category = IncidentCategory::where('incident_category_name','Customer Complaints')->first();
            $contact_id = !Auth::check() ? "OL-".date('y',strtotime(Carbon::now()))."-".$this->random_num(8) : Auth::user()->contact_id;
            $fileas = Auth::check() ? Auth::user()->contact->fileas : $request->lname.', '.$request->fname.' '.$request->mname;
            $mname = Auth::check() ? Auth::user()->contact->mname : $request->mname;
            $request->merge([
                'contact_id'=>$contact_id,
                'fileas'=>$fileas,
                'mname'=>$mname,
                'incident_no'=>'WIAR'.date('y',strtotime(Carbon::now())).$this->random_alph_num(3,3),
                'incident_datetime'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                'posted_datetime'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                'incident_description'=>'CUSTOMER COMPLAINTS',
                'guest'=>Auth::check() ? 0 : 1,
                'branchoffice_no'=>'000',
                'reference_person'=>'',
                'incident_subject'=>$request->incident_subject.':'.$fileas,
                'posted_by'=>Auth::check() ? Auth::user()->contact_id : $request->contact_id
            ]);

            if(!Auth::check()){
                $contact_guest = ContactGuest::firstOrNew(['email'=>$request->email]);
                if(!$contact_guest->exists){
                    $contact_guest->fill($request->only(['contact_id','fileas','lname','fname','mname','email','contact_no']))->save();
                }
            }

            $incident = Incident::create($request->only(['incident_no','incident_datetime','incident_subject','incident_description','posted_by','posted_datetime','guest','incident_category_id','posted_by']));

            WaybillIncident::create([
                'incident_no'=>$request->incident_no,
                'incident_datetime'=>$request->incident_datetime,
                'incident_subject'=>$request->incident_subject,
                'incident_description'=>$request->incident_description,
                'posted_datetime'=>$request->posted_datetime,
                'incident_category_id'=>$request->incident_category_id,
                'branchoffice_no'=>$request->branchoffice_no,
                'compalin_category'=>$request->category
            ]);
            $data=$incident;

            if($request->has('attachments')){
                $attachments = $request->file('attachments');

                $paths  = [];
                $descriptions = [];
                foreach ($attachments as $key=>$attachment) {
                    $extension = $attachment->getClientOriginalExtension();
                    $filename  = $attachment->getClientOriginalName();
                    $desciptions[]=$filename;
                    $paths[] = Storage::disk('system_files')->put('incidents/'.$incident->incident_no, $attachment);
                }

                $incident_files = [];
                $waybill_incident_files = [];
                foreach($paths as $key=>$path){
                    $row = $desciptions[$key];
                    array_push($incident_files,[
                        'incident_no'=>$incident->incident_no,
                        'description'=>$row,
                        'tblincident_files'=>$path
                    ]);
                    array_push($waybill_incident_files,[
                        'incident_no'=>$incident->incident_no,
                        'date_uploaded'=>date('Y-m-d'),
                        'description'=>$row,
                        'file_link'=>$path
                    ]);
                }
                IncidentFile::insert($incident_files);
                WaybillIncidentFile::insert($waybill_incident_files);
            }


            if(count($request->tracking_no)>0){
                $incident_tracking = [];
                $tracking_nos=$request->tracking_no;
                foreach($tracking_nos as $tracking_no){
                    array_push($incident_tracking,[
                        'incident_no'=>$incident->incident_no,
                        'tracking_no'=>$tracking_no
                    ]);
                }
                IncidentWaybill::insert($incident_tracking);
                WaybillIncidentWaybill::insert($incident_tracking);
            }


            // if($request->tracking_no!=''){
            //     $incident_tracking = [];
            //     $tracking_nos = explode(",",$request->tracking_no);
            //     foreach($tracking_nos as $tracking_no){
            //         array_push($incident_tracking,[
            //             'incident_no'=>$incident->incident_no,
            //             'tracking_no'=>$tracking_no
            //         ]);
            //     }
            //     IncidentWaybill::insert($incident_tracking);
            //     WaybillIncidentWaybill::insert($incident_tracking);
            // }

            # INSERT TO OLD IR


            # INSERT TO NEW IR

            $report_id = $doff_functions->createUnique_REFNumber("IR", "incident_report_db.tbl_incident_report", "report_id", "");
            if($request->incident_category_id == 4){
                $IR_Subject = "CLAIMS";
                $IR_Description = "Claims By Customer:\nName: {$request->fname} {$request->mname} {$request->lname}\nContact Number: {$request->contact_no}\nEmail: {$request->email}\n\n Claims Details: {$request->incident_subject}";

                DB::table('incident_report_db.tbl_incident_report')
                ->insert([
                    "report_id" =>  $report_id,
                    "datetime" => DB::RAW('CURRENT_TIMESTAMP'),
                    "branch_department" => '000',
                    "subject" => $IR_Subject,
                    "description" => $IR_Description,
                    "category" => 76,
                    "created_datetime" => DB::RAW('CURRENT_TIMESTAMP'),
                    "updated_at" => DB::RAW('CURRENT_TIMESTAMP'),
                    "log_description" => "IR CREATED"
                ]);

                if(count($request->tracking_no)>0){
                    $tracking_nos = $request->tracking_no;
                    foreach($tracking_nos as $tracking_no){
                        DB::table('incident_report_db.tbl_tagged_waybill')
                        ->insert([
                            "waybill_no" => $tracking_no,
                            "report_id" => $report_id
                        ]);
                    }
                }
            } else if ($request->incident_category_id == 11) {
                $IR_Subject = "CUSTOMER COMPLAINT";
                $IR_Description = "Complain by:\nName: {$request->fname} {$request->mname} {$request->lname}\nContact Number: {$request->contact_no}\nEmail: {$request->email}\n\n Complain Details: {$request->incident_subject}";

                DB::table('incident_report_db.tbl_incident_report')
                ->insert([
                    "report_id" =>  $report_id,
                    "datetime" => DB::RAW('CURRENT_TIMESTAMP'),
                    "branch_department" => '000',
                    "subject" => $IR_Subject,
                    "description" => $IR_Description,
                    "category" => 101,
                    "sub_category" => 116,
                    "created_datetime" => DB::RAW('CURRENT_TIMESTAMP'),
                    "updated_at" => DB::RAW('CURRENT_TIMESTAMP'),
                    "log_description" => "IR CREATED"
                ]);

                if(count($request->tracking_no)>0){
                    $tracking_nos = $request->tracking_no;
                    foreach($tracking_nos as $tracking_no){
                        DB::table('incident_report_db.tbl_tagged_waybill')
                        ->insert([
                            "waybill_no" => $tracking_no,
                            "report_id" => $report_id
                        ]);
                    }
                }
            } else if ($request->incident_category_id == 12) {
                $IR_Subject = "CUSTOMER COMPLAINT - Shipment";
                $IR_Description = "Complain by:\nName: {$request->fname} {$request->mname} {$request->lname}\nContact Number: {$request->contact_no}\nEmail: {$request->email}\n\n Complain Details: {$request->incident_subject}";

                DB::table('incident_report_db.tbl_incident_report')
                ->insert([
                    "report_id" =>  $report_id,
                    "datetime" => DB::RAW('CURRENT_TIMESTAMP'),
                    "branch_department" => '000',
                    "subject" => $IR_Subject,
                    "description" => $IR_Description,
                    "category" => 101,
                    "sub_category" => 116,
                    "created_datetime" => DB::RAW('CURRENT_TIMESTAMP'),
                    "updated_at" => DB::RAW('CURRENT_TIMESTAMP'),
                    "log_description" => "IR CREATED"
                ]);

                if(count($request->tracking_no)>0){
                    $tracking_nos = $request->tracking_no;
                    foreach($tracking_nos as $tracking_no){
                        DB::table('incident_report_db.tbl_tagged_waybill')
                        ->insert([
                            "waybill_no" => $tracking_no,
                            "report_id" => $report_id
                        ]);
                    }
                }

            } else if ($request->incident_category_id == 13) {
                $IR_Subject = "CUSTOMER COMPLAINT - Claimed Cargoes";
                $IR_Description = "Complain by:\nName: {$request->fname} {$request->mname} {$request->lname}\nContact Number: {$request->contact_no}\nEmail: {$request->email}\n\n Complain Details: {$request->incident_subject}";

                DB::table('incident_report_db.tbl_incident_report')
                ->insert([
                    "report_id" =>  $report_id,
                    "datetime" => DB::RAW('CURRENT_TIMESTAMP'),
                    "branch_department" => '000',
                    "subject" => $IR_Subject,
                    "description" => $IR_Description,
                    "category" => 101,
                    "sub_category" => 116,
                    "created_datetime" => DB::RAW('CURRENT_TIMESTAMP'),
                    "updated_at" => DB::RAW('CURRENT_TIMESTAMP'),
                    "log_description" => "IR CREATED"
                ]);

                if(count($request->tracking_no)>0){
                    $tracking_nos = $request->tracking_no;
                    foreach($tracking_nos as $tracking_no){
                        DB::table('incident_report_db.tbl_tagged_waybill')
                        ->insert([
                            "waybill_no" => $tracking_no,
                            "report_id" => $report_id
                        ]);
                    }
                }
            }

            # INSERT TO NEW IR

            $title='Success!';$message='Successfully sent!';$type='success';$errors=null;
        }

        return response()->json(['title'=>$title,'message'=>$message,'type'=>$type,'data'=>$data,'errors'=>$errors],$status_code);

        // return ['title'=>$title,'message'=>$message,'type'=>$type,'data'=>$data,'errors'=>$errors];
    }

    public function store_feedback(Request $request){
        $title='';$message='';$type='';
        $validations = !Auth::check() ? Validator::make($request->all(),[
            'lname'=>'required',
            'fname'=>'required',
            'contact_no'=>'required',
            'email'=>'required',
            'incident_subject'=>'required'
        ]) : Validator::make($request->all(),[
            'incident_subject'=>'required'
        ]);
        if($validations->passes()){
            $incident_category = IncidentCategory::where('incident_category_name','CUSTOMER FEEDBACK')->first();
            $contact_id = !Auth::check() ? "OL-".date('y',strtotime(Carbon::now()))."-".$this->random_num(8) : Auth::user()->contact_id;
            $fileas = !Auth::check() ? $request->lname.', '.$request->fname.' '.$request->mname : Auth::user()->contact->fileas;
            $mname = Auth::check() ? Auth::user()->contact->mname : $request->mname;
            if(!Auth::check()){
                $mname=$request->mname!=null ? $request->mname : '';
            }
            $request->merge([
                'contact_id'=>$contact_id,
                'fileas'=>$fileas,
                'mname'=>$mname,
                'incident_no'=>'WIAR'.date('y',strtotime(Carbon::now())).$this->random_alph_num(3,3),
                'incident_datetime'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                'posted_datetime'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                'incident_category_id'=>$incident_category!=null ? $incident_category->incident_category_id : 0,
                'incident_description'=>$incident_category!=null ? $incident_category->incident_category_name : '',
                'guest'=>1
            ]);
            if(!Auth::check()){
                $contact_guest = ContactGuest::firstOrNew(['email'=>$request->email]);
                if(!$contact_guest->exists){
                    $contact_guest->fill($request->only(['contact_id','fileas','lname','fname','mname','email','contact_no']))->save();
                }
            }
            $request->merge(['posted_by'=>$contact_id]);
            $incident = Incident::create($request->only(['incident_no','incident_datetime','incident_subject','incident_description','posted_by','posted_datetime','guest','incident_category_id','posted_by']));
            WaybillIncident::create([
                'incident_no'=>$request->incident_no,
                'incident_datetime'=>$request->incident_datetime,
                'incident_subject'=>$request->incident_subject,
                'incident_description'=>$request->incident_description,
                'posted_datetime'=>$request->posted_datetime,
                'incident_category_id'=>$request->incident_category_id,
                'branchoffice_no'=>'000'
            ]);
            $title='Success!';$message='Feedback has been submitted';$type='success';
        }else{
            $title='Ooops!';$message='There something wrong';$type='error';

        }

        return ['title'=>$title,'message'=>$message,'type'=>$type];
    }
}
