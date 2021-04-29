<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getData = DB::table('sales as s')
                     ->join('users as u','s.id_user','=','u.id')
                     ->select('s.id','u.name as worker','s.discount','s.total','s.status', 's.created_at')
                     ->get();
        $data["sales"] = $getData;
        return $this->sendResponse($data, "Sales recovered correctly");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getData = DB::table('sale_details as sd')
                    ->join('products as p','sd.id_product','=','p.id')
                    ->join('measurement_units as m','p.id_measurement_unit','=','m.id')
                    ->select('sd.id_sale','p.name as product_name','m.abbreviation','p.unit_sale_price','sd.quantity')
                    ->where('sd.id_sale',$id)
                    ->get();
        
        $worker = DB::table('sale_details as sd')
                    ->join('sales as s','sd.id_sale','=','s.id')
                    ->join('users as u','s.id_user','=','u.id')
                    ->select('u.id','u.name','u.email','u.type','u.status')
                    ->where('s.id',$id)
                    ->first();

        $total = DB::table('sale_details as sd')
                    ->join('sales as s','sd.id_sale','=','s.id')
                    ->select('s.discount','s.total','s.status')
                    ->where('s.id',$id)
                    ->first();

        if(!$getData){
            return $this->sendError("Sales not found", ["Sale details does not exists"], 400);
        }

        $data["worker"] = $worker;
        $data["total"] = $total;
        $data["details"] = $getData;
        return $this->sendResponse($data, "Sale detail recovered correctly");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sales $sales)
    {
        //
    }
}
