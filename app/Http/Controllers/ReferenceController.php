<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OnlineSite\Branch;
use App\OnlineSite\Stock;
use App\OnlineSite\Unit;
use App\OnlineSite\BusinessType;
use App\OnlineSite\Province;
use App\OnlineSite\City;
use App\Waybill\Sector;
use App\Waybill\Stock as WStock;
use Crypt;

use App\Http\Resources\BranchResource;
use App\Http\Resources\StockResource;
class ReferenceController extends Controller
{
    public function __construct(){
        $this->Branch = new Branch;
    }

    public function getCity($id){
        echo json_encode(City::where('cities_id',$id)->with('province')->first()->toArray());
    }

    public function get_references(){
        $ddStocks = ''; $ddUnits= '';$ddCities='';
        $stocks = Stock::orderBy('stock_description','ASC')->get();
        $units = Unit::orderBy('unit_description','ASC')->get();
        foreach($stocks as $key=>$row){
            $ddStocks.="<option value=".$row->stock_no.">".$row->stock_description."</option>";
        }
        foreach($units as $key=>$row){
            $ddUnits.="<option value=".$row->unit_no.">".$row->unit_description."</option>";
        }

        return [
            'provinces'=>Province::with('city')->orderBy('province_name','ASC')->get()->toArray(),
            'branches'=>Branch::orderBy('branchoffice_description','ASC')->get()->toArray(),
            'business_types'=>BusinessType::with('business_type_category')->get()->toArray(),
            'stocks'=>$stocks->toArray(),
            'units'=>$units->toArray() 

            
        ];  
    }

    public function stocks(Request $request){
        if($request->item_description!=null){
            $stocks = WStock::where('stock_description','LIKE','%'.strtoupper($request->item_description).'%')->whereRaw("stock_description != '' && LEFT(stock_no,2) !='KS' && LEFT(stock_no,2) !='OL'")->orderBy('stock_description','ASC')->get(['stock_no','stock_description'])->unique('stock_description');
            return StockResource::collection($stocks);
        }else{
            return '{"data" : [] }';
        }
    }
    public function search_stocks(Request $request){
        if($request->desc!=null){
            $stocks = WStock::where('stock_description','LIKE','%'.strtoupper($request->desc).'%')->whereRaw("stock_description != '' && LEFT(stock_no,2) !='KS' && LEFT(stock_no,2) !='OL'")->orderBy('stock_description','ASC')->get(['stock_no','stock_description'])->unique('stock_description');
            return response()->json($stocks);
        }else{
            return response()->json('{"data" : [] }');
        }
    }

    public function sector($id){
        return response()->json(['data'=>Sector::where('city_id',$id)->orderBy('barangay','ASC')->get(['sectorate_no','barangay'])]);
    }


    
}
