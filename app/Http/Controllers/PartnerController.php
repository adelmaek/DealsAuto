<?php

namespace App\Http\Controllers;
use App\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function getAddPartner(Request $request)
    {
        $partners = Partner::all();
        return view('partners/addPartner',['partners'=>$partners]);
    }
    public function postAddPartner(Request $request)
    {
        Partner::insert_partner($request['nameInput'], $request['intialBalance']);
        return redirect()->back();
    }
    public function getDelPartner($partner_id)
    {
        Partner::del_partner($partner_id);
        return redirect()->back();
    }

}
