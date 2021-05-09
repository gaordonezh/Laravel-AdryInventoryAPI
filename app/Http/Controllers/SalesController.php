<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SaleDetails;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

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
        try {
            DB::beginTransaction();
            
            $validator = Validator::make($request->all(), [
                'id_user' => 'required|numeric|min:1',
                'discount' => 'required|numeric|min:0',
                'details' => 'required',
                'total' => 'required'
            ]);
    
            if($validator->fails()){
                return $this->sendError("Error de validacion", $validator->errors(), 422);
            }

            $venta = new Sales();
            $venta->id_user = $request->get("id_user");
            $venta->discount = $request->get("discount");
            $venta->total = $request->get("total");
            $venta->status = 1;
            $venta->save();
            
            $s_details = $request->get('details');
            $cont = 0;
            for ($i=0; $i < count($s_details); $i++) { 
                $sbtStock = Product::find($s_details[$i]["id"]);
                if($sbtStock->stock < $s_details[$i]["quantity"]){
                    DB::rollback();
                    return response()->json([
                        "success" => false,
                        "stock" => $sbtStock->stock,
                        "cantidad" => $s_details[$i]["quantity"],
                        "producto" => $sbtStock->name,
                        "message" => "Stock insuficiente."
                    ], 200);
                }
                $sbtStock->stock = $sbtStock->stock - $s_details[$i]["quantity"];
                $sbtStock->save();

                $newSD = new SaleDetails();
                $newSD->id_sale = $venta->id;
                $newSD->id_product = $s_details[$i]["id"];
                $newSD->quantity = $s_details[$i]["quantity"];
                $newSD->save();

                $cont++;
            }

            $data = [];
            $data["VENTA"] = $venta;
            $data["COUNT"] = $cont;

            DB::commit();
            return $this->sendResponse($data, "Venta realizada correctamente.");
        }catch(\Exception $error){
            DB::rollback();
            return $this->sendError("Errores", [$error], 400);
        }
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
    public function anularVenta(Request $request){
        $id_sale = $request->get("id_sale");
        $restore_stock = $request->get("restore_stock");
        
        $venta = Sales::find($id_sale);
        if(!$venta){
            return $this->sendError("Sales not found", ["Sale details does not exists"], 400);
        }

        if($restore_stock){
            $detalle = SaleDetails::where("id_sale",$venta->id)->get();
            if($detalle){
                for ($i=0; $i < count($detalle); $i++) { 
                    $productFind = Product::find($detalle[$i]["id_product"]);
                    $productFind->stock = $productFind->stock + $detalle[$i]["quantity"];
                    $productFind->save();
                }
                $venta->status = 0;
                $venta->save();

                return response()->json([
                    "restore" => "SI",
                    "message" => "Stock no restaurado."
                ], 200);
            }else{
                return $this->sendError("Sale details not found", ["Sale details does not exists"], 400);
            }
        }else{
            $venta->status = 0;
            $venta->save();
            return response()->json([
                "restore" => "NO",
                "message" => "Stock no restaurado."
            ], 200);
        }        
    }

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
