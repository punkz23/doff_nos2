<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OnlineSite\UserAddress;
use App\OnlineSite\Contact;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ReferenceTrait;
use Validator;
class ContactAddressController extends Controller
{
    use ReferenceTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {     
        $profile = Contact::where('contact_id',$id)->first();
        $addresses = UserAddress::where('user_id',$id)->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"))->get();
        return response()->json(['profile'=>$profile,'addresses'=>$addresses->toArray()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
