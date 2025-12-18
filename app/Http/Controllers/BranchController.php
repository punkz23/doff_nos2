<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Branch;
use App\BranchFilter;
use App\BranchContact;
use App\BranchSchedule;
use Jenssegers\Agent\Agent;
use View;
use Validator;

class BranchController extends Controller
{
    public function __construct(){
    	$this->middleware(['auth']);
        View::share(['branches'=>Branch::with(['branch_contact','branch_schedule'])->get(),'branch_filters'=>BranchFilter::get()]);
    }

    public function index(){
    	return view('branches.index');
    }

    public function create(){
    	return view('branches.create');
    }

    public function store(Request $request){
        $title='';$message='';$type='';$data=null;
        $validation=Validator::make($request->all(),
                [
                    'branch_filter_id'=>'required',
                    'name'=>'required',
                    'address'=>'required',
                    'google_maps_api'=>'required'
                ]
        );
        if($validation->passes()){

            // $modal = $request->isMethod('put') ? Branch::findOrFail($id)

            $data=$request->id=='' ? Branch::create($request->except('id')) : Branch::where('id',$request->id)->update($request->except('id','_token'));

            $title='Success!';
            $message='Successfully saved';
            $type='success';
        }else{
            $title='Ooops!';
            $message='There something wrong';
            $type='error';
        }
        return response()->json(['title'=>$title,'message'=>$message,'type'=>$type]);
    }

    public function show($id){
        $check=Branch::firstOrNew(['id'=>$id]);
        if($check->exists){
            echo json_encode(Branch::where('id',$id)->first()->toArray());
        }else{
            abort(404);
        }
    }

    public function edit($id){
        $check=Branch::firstOrNew(['id'=>$id]);
        if($check->exists){
            $data = Branch::where('id',$id)->with(['branch_contact','branch_schedule'])->first();
            return view('branches.edit',compact('data'));

        }else{
            abort(404);
        }

    }

    public function update(Request $request, $id){

    }

    public function destroy($id){
        $branch=Branch::findOrFail($id);
        $title='';$message='';$type='';
        if($branch->exists){
            $branch->delete();
            $title='Success';
            $message='Branch has been deleted!';
            $type='success';
        }else{
            $title='Ooops!';
            $message='There something wrong!';
            $type='error';
        }
        return response()->json(['title'=>$title,'message'=>$message,'type'=>$type]);
    }

    public function list(){
        $agent = new Agent();
        $view = ($agent->isMobile() || $agent->isTablet()) && \Config::get('app.mobile')==1 ? 'mobile.branches.list' : 'branches.list';
        return view($view);
        
    }

    public function contact_no_store(Request $request){
        $title='';$message='';$type='';$data=null;

        $validation = Validator::make($request->all(),['contact_no'=>'required','branch_id'=>'required|exists:branches,id']);


        if($validation->passes()){
            $data=$request->id=='' ? BranchContact::create($request->except('id')) : BranchContact::where('id',$request->id)->update($request->except('id','_token'));
            $title='Success!';
            $message='Successfully saved';
            $type='success';
        }else{
            $title='Ooops!';
            $message='There something wrong';
            $type='error';
            $data=$validation->errors();
        }

        return response()->json(['title'=>$title,'message'=>$message,'type'=>$type,'data'=>$data]);
    }

    public function contact_no_show($id){
        echo json_encode(BranchContact::where('id',$id)->first()->toArray());
    }

    public function contact_no_delete($id){
        $branch_contact=BranchContact::findOrFail($id);
        $title='';$message='';$type='';
        if($branch_contact->exists){
            $branch_contact->delete();
            $title='Success';
            $message='Branch contact has been deleted!';
            $type='success';
        }else{
            $title='Ooops!';
            $message='There something wrong!';
            $type='error';

        }
        return response()->json(['title'=>$title,'message'=>$message,'type'=>$type]);
    }

    public function schedule_store(Request $request){
        $title='';$message='';$type='';$data=null;
        $validation=Validator::make($request->all(),
                [
                    'days_from'=>'required',
                    'days_to'=>'required',
                    'time_from'=>'required',
                    'time_to'=>'required',
                    'branch_id'=>'required'
                ]
        );
        if($validation->passes()){
            $data=$request->id=='' ? BranchSchedule::create($request->except('id')) : BranchSchedule::where('id',$request->id)->update($request->except('id','_token'));
            $title='Success!';
            $message='Successfully saved';
            $type='success';
            $data=$request->id=='' ? ['id'=>$data->id,'schedule'=>$data->days_from.'-'.$data->days_to.' '.date('h:i A',strtotime($data->time_from)).'-'.date('h:i A',strtotime($data->time_to))] : null;
        }else{
            $title='Ooops!';
            $message='There something wrong';
            $type='error';
        }

        return response()->json(['title'=>$title,'message'=>$message,'type'=>$type,'data'=>$data]);
    }

    public function schedule_show($id){
        echo json_encode(BranchSchedule::where('id',$id)->first()->toArray());
    }

    public function schedule_delete($id){
        $branch_schedule=BranchSchedule::findOrFail($id);
        $title='';$message='';$type='';
        if($branch_schedule->exists){
            $branch_schedule->delete();
            $title='Success';
            $message='Branch schedule has been deleted!';
            $type='success';
        }else{
            $title='Ooops!';
            $message='There something wrong!';
            $type='error';
        }
        return response()->json(['title'=>$title,'message'=>$message,'type'=>$type]);
    }
}
