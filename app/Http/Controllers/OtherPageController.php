<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FAQ\Category;
use App\FAQ\Guide;
use App\Term;
use App\DOFFConfiguration\Branch;
use App\OnlineSite\Province;
use App\Waybill\Stock;
use App\Waybill\Unit;
use App\Waybill\Stock as WStock;
use App\Waybill\Unit as WUnit;
use App\Waybill\IncidentCategory;
use App\Quotation\RequestQuotation;
use App\Quotation\RequestQuotationDetail;
use App\Quotation\RequestQuotationDetailDimension;
use App\Quotation\UnitConversion;
use Validator;
use Jenssegers\Agent\Agent;
class OtherPageController extends Controller
{
    public function faq(){
    	$categories = Category::with(['question'=>function($query){ $query->with('guide'); }])->get();
    	return view('others.faq',compact('categories'));
    }

    public function faq_create(){
    	return view('others.faq_create');
    }

    public function faq_store(Request $request){
    	$this->validate($request, [
            'body' => 'required',
        ]);

        $detail=$request->input('body');

        $dom = new \DomDocument();

        $dom->loadHtml($detail, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $images = $dom->getElementsByTagName('img');

        foreach($images as $k => $img){

            $data = $img->getAttribute('src');

            list($type, $data) = explode(';', $data);

            list(, $data)      = explode(',', $data);

            $data = base64_decode($data);


            $image_name= "\uploads\"" . time().$k.'.png';

            $path = public_path() . $image_name;

            file_put_contents($path, $data);

            $img->removeAttribute('src');

            $img->setAttribute('src', $image_name);

        }

        $detail = $dom->saveHTML();


    }

    public function terms_and_condition(){
        return view('terms.editor');
    }

    public function terms_and_condition_post(Request $request){
        $validation=Validator::make($request->all(), [
            'body'=>'required',
            'type'=>'required'
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

            
            $data = Term::where('type',$request->type);
            if($data->count()>0){
                $data->update(['content'=>$detail]);
            }else{
                Term::create(['type'=>$request->type,'content'=>$detail]);
            }
            $title='Success!';
            $message='Terms and condition has been saved';
            $response_type='success';
        }else{
            $title='Ooops!';
            $message='Please fill the required fields!';
            $response_type='error';

        }


        return response()->json(['title'=>$title,'message'=>$message,'type'=>$response_type]);


    }

    public function term($type){
        $check = Term::firstOrNew(['type'=>$type]);
        if($check->exists){
            echo json_encode(Term::where('type',$type)->first()->toArray());
        }else{

        }
    }

    public function complain(){
        
        $view = 'others.complain';
        $incident_categories = IncidentCategory::whereIn('incident_category_id',[4,11,12])->get();
        return view($view,compact('incident_categories'));
    }

    public function feedback(){
        
        $view = 'others.feedback';
        return view($view);
        
    }

    public function request_quote(){
        $branches = Branch::where('branches',1)->whereNotIn('branchoffice_no',['000','011'])->orderBy('branchoffice_description','ASC')->get();
        $ddStocks = ''; 
        $ddUnits= '';
        //$stocks = WStock::whereRaw("stock_description != '' && LEFT(stock_no,2) !='KS' && LEFT(stock_no,2) !='OL'")->orderBy('stock_description','ASC')->get()->unique('stock_description');
        $units = WUnit::orderBy('unit_description','ASC')->get();
        $provinces = Province::with('city')->orderBy('province_name','ASC')->get();
        // foreach($stocks as $key=>$row){
        //     $ddStocks.="<option value=".$row->stock_no.">".$row->stock_description."</option>";
        // }
        foreach($units as $key=>$row){
            $ddUnits.="<option value=".$row->unit_no.">".$row->unit_description."</option>";
        }
        $ddUnitConversion='';
        $unit_conversion = UnitConversion::get();
        
        foreach($unit_conversion as $key=>$row){
            $ddUnitConversion.="<option value=".$row->unit_convertion_id.">".$row->unit_name."</option>";
            
        }
        
        $view = 'others.request_quote';
        return view($view,compact('branches','provinces','ddUnits','ddStocks','ddUnitConversion'));
    }

}
