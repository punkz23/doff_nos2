<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\FAQ\Category;
use App\FAQ\Dialect;
use App\FAQ\Platform;
use App\FAQ\Guide;
use App\FAQ\Question;
use Jenssegers\Agent\Agent;
use Auth;
use Validator;
class GuideController extends Controller
{

    public function __construct(){
        $this->middleware(['auth','role:Admin'])->except('index','show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $agent = new Agent();
        // $platform_id = ($agent->isMobile() || $agent->isTablet()) && \Config::get('app.mobile')==1 ? 2 : 1;
        // $view = ($agent->isMobile() || $agent->isTablet()) && \Config::get('app.mobile')==1 ? 'mobile.faq.index' : 'faqs.index';
        // $data = Dialect::with(['category'=>function($query) use($platform_id){ $query->with(['question'=>function($query)use($platform_id){ $query->whereHas('guide',function($query) use($platform_id) { $query->where('platform_id',$platform_id); })->with(['guide'=>function($query) use($platform_id) { $query->where('platform_id',$platform_id); }]); }]); }])->get();
        
        // return view($view,compact('data'));
        abort(404);
        
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dialects=Dialect::get();
        $platforms=Platform::get();
        $categories=Category::where('dialect_id',1)->get();
        $questions = Question::where('dialect_id',1)->get();
        return view('faqs.create',compact('dialects','platforms','categories','questions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation=Validator::make($request->all(), [
            'dialect_id'=>'required',
            'platform_id'=>'required',
            'category_id'=>'required',
            'question_id'=>'required',
            'body' => 'required',
        ]);

        $title='';$message='';$response_type='';

        if($validation->passes()){

            $detail=$request->input('body');

            $dom = new \DomDocument();

            $dom->loadHtml($detail, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);


            $images = $dom->getElementsByTagName('img');


            foreach($images as $k => $img){
                $data = $img->getAttribute('src');
                $file_headers = @get_headers($data);

                if($file_headers[0]!='HTTP/1.1 200 OK'){
                    list($type, $data) = explode(';', $data);

                    list(, $data)      = explode(',', $data);

                    $data = base64_decode($data);

                    $image_name= "/uploads/" . time().$k.'.png';

                    $path = public_path() . $image_name;

                    file_put_contents($path, $data);

                    $img->removeAttribute('src');

                    $img->setAttribute('src', asset($image_name));
                }
            }

            $detail = $dom->saveHTML();



            if($request->id!=''){

                Guide::where('id',$request->id)
                     ->update([
                                'question_id'=>$request->question_id,
                                'platform_id'=>$request->platform_id,
                                'content'=>$detail,
                                'user_id'=>Auth::user()->id
                              ]);
                $title='Success!';
                $message='Successfully updated!';
                $response_type='success';
            }else{
                Guide::create(['question_id'=>$request->question_id,'platform_id'=>$request->platform_id,'content'=>$detail,'user_id'=>Auth::user()->id]);
                $title='Success!';
                $message='Successfully saved!';
                $response_type='success';
            }




        }else{
            $title='Ooops!';
            $message='Please fill the required fields!';
            $response_type='error';
        }


        return response()->json(['title'=>$title,'message'=>$message,'type'=>$response_type]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($dialect)
    {
        return view('faqs.index',compact('dialect'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $data=Question::findOrFail($id);
        // if($data->exists){

        // }else{
        //     return redirect('/guides');
        // }
        $dialects=Dialect::get();
        $platforms=Platform::get();
        $categories=Category::get();
        $question=Question::where('id',$id)->with(['guide'])->first();
        return view('faqs.edit',compact('question','dialects','platforms','categories','id'));

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
        $validation=Validator::make($request->all(), [
            'dialect_id'=>'required',
            'platform_id'=>'required',
            'category_id'=>'required',
            'question'=>'required',
            'body' => 'required',
        ]);

        $title='';$message='';$type='';

        if($validation->passes()){
            $detail=$request->input('body');

            $dom = new \DomDocument();

            $dom->loadHtml($detail, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            $images = $dom->getElementsByTagName('img');

            foreach($images as $k => $img){

                $data = $img->getAttribute('src');

                list($type, $data) = explode(';', $data);

                list(, $data)      = explode(',', $data);

                $data = base64_decode($data);


                $image_name= "/uploads/" . time().$k.'.png';
                $path = public_path() . $image_name;
                file_put_contents($path, $data);
                $img->removeAttribute('src');
                $img->setAttribute('src', asset($image_name));

            }

            $detail = $dom->saveHTML();
            // $question = Question::where('id',$id)->update([
            //                 'dialect_id'=>$request->dialect_id,
            //                 'platform_id'=>$request->platform_id,
            //                 'category_id'=>$request->category_id,
            //                 'question'=>$request->question
            //             ]);

            // Guide::where('question_id',$id)->create(['content'=>$detail,'user_id'=>Auth::user()->id]);
            // $title='Success!';
            // $message='Successfully updated!';
            // $type='success';
        }else{
            $title='Ooops!';
            $message='Please fill the required fields!';
            $type='error';
        }


        return response()->json(['title'=>$title,'message'=>$message,'type'=>$type]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $title='';$message='';$type='';
        if($question->exists) {
            Guide::where('question_id',$id)->delete();
            $question->delete();
            $title='Success!';
            $message='Guide has been deleted!';
            $type='success';
        }else{
            $title='Ooops!';
            $message='There something wrong!';
            $type='error';
        }

        return response()->json(['title'=>$title,'message'=>$message,'type'=>$type]);
    }
    
    public function get_category_via_dialect($id){
        echo json_encode(Category::where('dialect_id',$id)->get()->toArray());
    }

    public function get_questions_via_category($id){
        echo json_encode(Question::where('category_id',$id)->get()->toArray());
    }

    public function question_store(Request $request){
        $validation=Validator::make($request->all(), [
            'dialect_id'=>'required',
            'category_id'=>'required',
            'question'=>'required',
        ]);
        $title='';$message='';$type='';$data=null;
        if($validation->fails()){
            $title='Ooops!';
            $message='Please fill the required fields!';
            $type='error';
        }else{
            $data=Question::create([
                'dialect_id'=>$request->dialect_id,
                'category_id'=>$request->category_id,
                'question'=>$request->question
            ]);
            $title='Success!';
            $message='Successfully saved!';
            $type='success';
        }

        return response()->json(['title'=>$title,'message'=>$message,'type'=>$type,'data'=>$data]);
    }

    
}
