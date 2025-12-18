<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Newsletter\NewsletterSubscriber;
use App\Newsletter\NewsUpdate;
use App\RecordLogs\RecordLogs;
use Crypt;
use Mail;
use Auth;
use DB;
use App\Traits\ErrorLog;
use Carbon\Carbon;
use App\Class\DOFFClass;
use App\Mail\NewsletterSubscription;

class Newsletter extends Controller
{
    private $title;
    private $message;
    private $type;
    protected $function;
    use ErrorLog;

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
        $this->function = new DOFFClass();
    }

    public function subscribe(Request $request){
        $subscriber = NewsletterSubscriber::where('email_address',$request['email_subscribe'])
                        ->where('subscription_status',1)
                        ->first();

        $this->title='Ooops!';
        $this->message="You've already subscribed to our newsletter!";
        $this->type = 'info';

        if($subscriber==null){
            DB::beginTransaction();
            try{
                $token =  Crypt::encrypt(date("m-d-Y H:i:s.u"));

                Mail::send('newsletter.email_body',['token'=>$token], function($message) use($request){
                    $message->to($request['email_subscribe'],'')->subject('Newsletter Subscription');
                    $message->from('newsletter@dailyoverland.com',env('MAIL_NAME'));
                });
                // Mail::to($request['email_subscribe'])->send(new NewsletterSubscription($token));

                $new_subscriber = new NewsletterSubscriber;
                $new_subscriber->email_address = $request['email_subscribe'];
                $new_subscriber->token =$token;
                $new_subscriber->save();

                $recordlogs = new RecordLogs;
                $recordlogs->recordlogs = 'CUSTOMER SUBSCRIBED TO NEWSLETTER -- FOR CONFIRMATION (LINK SENT)';
                $recordlogs->email = $request['email_subscribe'];
                $recordlogs->save();

                $this->title='Newsletter Subscription';
                $this->message="Verification link has been sent to your email.";
                $this->type = 'success';
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();

                $this->title='Newsletter Subscription';
                $this->message="Please try again.";
                $this->type = 'error';
            }
        }
        return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type]);
    }

    public function confirm($id){

        $subscriber = NewsletterSubscriber::where('token',$id)->first();

        $id =$subscriber->idsubscribers;
        $email =$subscriber->email_address;

        $subscriber= NewsletterSubscriber::find($id);
        $subscriber->confirmed_datetime =  now();
        $subscriber->subscription_status =  1;
        $subscriber->save();

        $subscriber = NewsletterSubscriber::where('email_address',$email)
                        ->where('idsubscribers','<>',$id);
        $subscriber->delete();

        $recordlogs = new RecordLogs;
        $recordlogs->recordlogs = 'CUSTOMER HAS CONFIRMED THE SUBSCRIPTION';
        $recordlogs->email = $email;
        $recordlogs->save();

        return view('newsletter.confirm');
    }

    public function unsubscribe($id){

        $subscriber = NewsletterSubscriber::where('token',$id)->first();

        $id =$subscriber->idsubscribers;
        $email =$subscriber->email_address;

        $subscriber= NewsletterSubscriber::find($id);
        $subscriber->unsubscribe_datetime =  now();
        $subscriber->subscription_status =  2;
        $subscriber->save();

        $recordlogs = new RecordLogs;
        $recordlogs->recordlogs = 'CUSTOMER UNSUBSCRIBED TO NEWSLETTER';
        $recordlogs->email = $email;
        $recordlogs->save();

        return view('newsletter.unsubscribed');
    }

    public function subscribeCustomer(Request $request){

        $token =  Crypt::encrypt(date("m-d-Y H:i:s.u"));
        $newsletter = NewsletterSubscriber::firstOrNew(['customer_id'=>Auth::user()->contact_id]);

        if($newsletter->exists){
            $newsletter->fill([
                'subscription_status'=> $newsletter->subscription_status == 1 ? 2 : 1,
                'confirmed_datetime'=>Now(),
            ])->update();

            $recordlogs = new RecordLogs;
            $recordlogs->recordlogs = $newsletter->subscription_status == 1 ? "CUSTOMER SUBSCRIBED TO NEWSLETTER" : "CUSTOMER UNSUBSCRIBED TO NEWSLETTER" ;
            $recordlogs->email = Auth::user()->email;
            $recordlogs->customer_id = Auth::user()->contact_id;
            $recordlogs->save();

        }else{
            $newsletter->fill([
                'email_address'=>Auth::user()->email,
                'token'=>$token,
                'customer_id'=>Auth::user()->contact_id,
                'confirmed_datetime'=>Now(),
                'subscription_status'=>1
            ])->save();

            $recordlogs = new RecordLogs;
            $recordlogs->recordlogs = 'CUSTOMER SUBSCRIBED TO NEWSLETTER';
            $recordlogs->email = Auth::user()->email;
            $recordlogs->customer_id = Auth::user()->contact_id;
            $recordlogs->save();
        }

        $this->title='Newsletter Subscription';
        $this->message= $newsletter->subscription_status == 1 ? "Thank you for subscribing to our newsletter." : "You have unsubscribed to our newsletter." ;
        $this->type = 'success';

        return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type]);

    }

    public function getNewsUpdate(Request $request){
        try{
            $newsUpdate = DB::table('news_letter.tblnews_update_template AS news_update')
                ->select(
                    'news_update.news_update_id',
                    'news_update.news_update_ref_no',
                    'news_update.news_title',
                    'news_update.news_caption',
                    'news_update.news_update_attachment_id',
                    'news_update.latest_logs',
                    'news_update.prepared_by',
                    'news_update.prepared_datetime',
                    'news_update.news_post_status',
                    'news_update.date_of_posting',
                    'news_update.post_expiration_date',
                    'news_update.updated_by',
                    // 'attachment.news_file_attachment',
                    // DB::raw("CONCAT('system_files/news-update/', attachment.news_file_attachment) AS news_file_attachment"),
                    DB::raw("CONCAT('https://w1.dailyoverland.com/system_files/news-update/', attachment.news_file_attachment) AS news_file_attachment"),
                    'attachment.news_file_type'
                )
                ->join(
                    'news_letter.tblnews_update_attachment AS attachment',
                    'attachment.news_update_attachment_id','=','news_update.news_update_attachment_id'
                )
                ->where('news_post_status', 1)
                ->where('date_of_posting','<=', Carbon::now()->toDateString())
                ->where(function($query){
                    $query->where('post_expiration_date','>=', Carbon::now()->toDateString())
                            ->orWhereNull('post_expiration_date');
                })
                ->orderBy('date_of_posting','desc')
                ->get();

            return response()->json($newsUpdate);
        } catch(\Exception $e){
            $this->error_log($e, $request->url());
            return response()->json([
                'message' => session('errorId')
            ], 500);
        }
    }

    public function streamNewsUpdate($path){
        if (empty($path)) {
            return;
        }
        return $this->function->streamVideo($path);
    }
}
