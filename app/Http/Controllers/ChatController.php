<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Message;
use Auth;
use Validator;
use Spatie\Permission\Models\Role;
use Pusher\Pusher;
use Carbon\Carbon;
use DB;
use App\User;
use App\Anonymous;
use App\AnonymousSession;

use Illuminate\Support\Facades\Hash;
class ChatController extends Controller
{

    public function __construct(){
        $this->middleware('auth')->except(['guestRequest','startConversation','getMessages','sendMessage','create']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Message::where(['from'=>Auth::user()->id,'to'=>2])->update(['is_read'=>1,'read_at'=>date('Y-m-d H:i:s',strtotime(Carbon::now()))]);

        // $messages=Message::where(function($query) { $query->where('from',Auth::id())->where('to',2); })
        //                  ->orWhere(function($query)  { $query->where('from',2)->where('to',Auth::user()->id); })
        //                  ->get();
        // return view('messages.index',compact('messages'));
        
       
        $roles = Auth::user()->getRoleNames();
        $admin = User::role('Admin')->first();
        $query = $roles[0]=='Admin' ? "SELECT users.id,users.name,users.avatar,users.email,COUNT(is_read) AS unread,users.user_status FROM users LEFT JOIN messages ON users.id=messages.from AND is_read=0 AND messages.to=".Auth::id()." WHERE users.id!=".Auth::id()." GROUP BY users.id, users.name,users.avatar,users.email" : "SELECT users.id,users.name,users.avatar,users.email,COUNT(is_read) AS unread FROM users LEFT JOIN messages ON users.id=messages.from AND is_read=0 AND messages.to=".Auth::id()." WHERE users.id!=".Auth::id()." AND users.id=".$admin->id." GROUP BY users.id, users.name,users.avatar,users.email";
        $users = DB::select($query);
        
        return view('messages.chat',['users'=>$users]);
        


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {

        if(Auth::check()){
            Message::where(['from'=>$id,'to'=>Auth::user()->id])->update(['is_read'=>1,'read_at'=>date('Y-m-d H:i:s',strtotime(Carbon::now()))]);

            $messages=Message::with(['sender','receiver'])->where(function($query) use($id){ 
                    $query->where('from',Auth::id())->where('to',$id); 
                })
                ->orWhere(function($query) use($id){ $query->where('from',$id)->where('to',Auth::user()->id); })
                ->get();
            $name=User::where('id',$id)->first()->name;
            return view('messages.index',compact('messages','name'));    
        }else{
            
            $admin = User::role('Admin')->first();
            $data = AnonymousSession::where('session_key',$id)->first();

            $messages=Message::where(function($query) use($admin,$data){ 
                    $query->where('from',$data->anonymous_id)->where('to',$admin->id); 
                })
                ->orWhere(function($query) use($admin,$data){ $query->where('from',$admin->id)->where('to',$data->anonymous_id); })
                ->get();
            dd($messages);
            // return view('messages.guest_index',compact('messages','data'));
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
        $validations = Validator::make($request->all(),[
            // 'from'=>'required',
            'message'=>'required',
            'to'=>'required'
        ]);

        
        if($validations->passes()){
            $request->merge(['from'=>Auth::id(),'is_read'=>0]);
            Message::create($request->all());

            $options = array(
                'cluster' => 'ap2',
                'useTLS' => true
            );

            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );

            $admin = User::role('Admin')->first();
            $data= ['from'=>Auth::id(),'to'=>$request->to];
            $pusher->trigger('my-channel','my-event',$data);
            
        }else{
            print_r($validations->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Message::where(['from'=>$id,'to'=>Auth::user()->id])->update(['is_read'=>1,'read_at'=>date('Y-m-d H:is',strtotime(Carbon::now()))]);

        $messages=Message::where(function($query) use($id){ $query->where('from',Auth::id())->where('to',$id); })
                         ->orWhere(function($query) use($id) { $query->where('from',$id)->where('to',Auth::user()->id); })
                         ->get();
        return view('messages.index',compact('messages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function guestRequest(Request $request){
        $validation = Validator::make($request->all(),
                        [
                            'name'=>'required',
                            'email'=>'required|email',
                            'subject'=>'required',
                            'description'=>'required'
                        ]
                    );
        $data=null;$title='';$message='';$type;
        if($validation->passes()){
            $request->session()->regenerate();
            $session_key=$request->session()->getId();
            $request->merge(['password'=>Hash::make('anonymous'.date('Y-m-d',strtotime(Carbon::now()))),'user_status'=>'Anonymous']);
            $user=null;
            if(User::where('email',$request->email)->count()==0){
                $user = User::create($request->except(['subject','description']))->assignRole('Client');
            }else{
                $user = User::where('email',$request->email)->first();
            }
            $guest_session = AnonymousSession::firstOrNew(['session_key'=>$session_key]);
            
            if(!$guest_session->exists){
                $data = AnonymousSession::create([
                                'session_key'=>$session_key,
                                'anonymous_id'=>$user->id,
                                'subject'=>$request->subject,
                                'description'=>$request->description
                                ]);
            }    
            $title='Success!';
            $message='Tech Support has been requested';
            $type='success';
            
        }else{
            $title='Ooops!';
            $message='Please check all required fields';
            $type='error';
        }
        return ['title'=>$title,'message'=>$message,'type'=>$type,'data'=>$data];
    }

    public function startConversation($id){
        $data=AnonymousSession::where('session_key',$id)->where('is_done',0);
        if($data->count()>0){
            $admin=User::role('Admin')->first();
            return view('messages.guest_chat',['data'=>$data->first(),'admin'=>$admin]);
        }else{
            abort(404);
        }
        
    }

    

    public function sendMessage(Request $request){
        
        $validations = Validator::make($request->all(),[
            'from'=>'required',
            'message'=>'required',
            'to'=>'required'
        ]);

        
        if($validations->passes()){
            $request->merge(['is_read'=>0]);
            Message::create($request->all());

            $options = array(
                'cluster' => 'ap2',
                'useTLS' => true
            );

            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );

            $admin = User::role('Admin')->first();
            $data= ['from'=>$request->from,'to'=>$request->to];
            $pusher->trigger('my-channel','my-event',$data);

        }else{
            print_r($validations->errors());
        }
    }

    public function getMessages($id){
        $data=AnonymousSession::where('session_key',$id)->where('is_done',0);
        if($data->count()>0){
            
            $admin = User::role('Admin')->first();
            Message::where(['from'=>$admin->id,'to'=>$data->anonymous_id])->update(['is_read'=>1,'read_at'=>date('Y-m-d H:i:s',strtotime(Carbon::now()))]);
            

            $messages=Message::where(function($query) use($data,$admin){ 
                $query->where('from',$data->anonymous_id)->where('to',$admin->id); 
            })
            ->orWhere(function($query) use($data,$admin){ $query->where('from',$admin->id)->where('to',$data->anonymous_id); })
            ->get();
            return view('messages.guest_index',compact('messages'));
        }
    }


    public function setDone(Request $request,$id){
        AnonymousSession::where('anonymous_id',$id)->update(['is_done'=>1]);
        User::where('id',$id)->update(['user_status'=>'Active']);
    }


    

    
}
