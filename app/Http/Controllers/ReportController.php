<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\SaleDetails;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function reportSales($value, Request $request){
        $data = null;
        $d_start = $request->start;
        $d_end = $request->end;
        $value_search = '';
        switch ($value) {
            case '0':
                $value_search = Carbon::now()->format('Y').'%';
            break;
            case '1': 
                $value_search = Carbon::now()->format('Y').'-01%';
            break;
            case '2':
                $value_search = Carbon::now()->format('Y').'-02%';
            break;
            case '3':
                $value_search = Carbon::now()->format('Y').'-03%';
            break;
            case '4':
                $value_search = Carbon::now()->format('Y').'-04%';
            break;
            case '5':
                $value_search = Carbon::now()->format('Y').'-05%';
            break;
            case '6':
                $value_search = Carbon::now()->format('Y').'-06%';
            break;
            case '7':
                $value_search = Carbon::now()->format('Y').'-07%';
            break;
            case '8':
                $value_search = Carbon::now()->format('Y').'-08%';
            break;
            case '9':
                $value_search = Carbon::now()->format('Y').'-09%';
            break;
            case '10':
                $value_search = Carbon::now()->format('Y').'-10%';
            break;
            case '11':
                $value_search = Carbon::now()->format('Y').'-11%';
            break;
            case '12':
                $value_search = Carbon::now()->format('Y').'-12%';
            break;
            case '13':
                $value_search = [$d_start.' 00:00:00', $d_end.' 23:59:59'];
            break;
            default: $data["resume"] = "NO_DATA";
            break;
        }
        if ($value == '13') {
            $data["resume"] = [];
            $sales = Sales::whereBetween('created_at',$value_search)->get();
            if($sales){
                for ($i=0; $i < count($sales); $i++) {
                    $sales[$i]["detalle"] = [];
                    $saleDetails = SaleDetails::where('id_sale',$sales[$i]->id)->get();
                    if($saleDetails){
                        for ($x=0; $x < count($saleDetails); $x++) { 
                            $product = Product::where('id',$saleDetails[$x]->id_product)->first();
                            if($product){
                                $saleDetails[$x]["producto"] = $product;
                                $sales[$i]["detalle"] = array_merge([$saleDetails[$x]], $sales[$i]["detalle"]);
                                $data["resume"] = $sales;
                            }
                        }
                    }
                }
            }

            $data["subtotal"] = Sales::select(DB::raw('SUM(total) as value'))->whereBetween('created_at',$value_search)->first();
            $data["descuento"] = Sales::select(DB::raw('SUM(discount) as value'))->whereBetween('created_at',$value_search)->first();
            $data["total"] = Sales::select(DB::raw('SUM(total - discount) as value'))->whereBetween('created_at',$value_search)->first();
        }else{
            $data["resume"] = [];
            $sales = Sales::where('created_at','LIKE',$value_search)->get();
            if($sales){
                for ($i=0; $i < count($sales); $i++) {
                    $sales[$i]["detalle"] = [];
                    $saleDetails = SaleDetails::where('id_sale',$sales[$i]->id)->get();
                    if($saleDetails){
                        for ($x=0; $x < count($saleDetails); $x++) { 
                            $product = Product::where('id',$saleDetails[$x]->id_product)->first();
                            if($product){
                                $saleDetails[$x]["producto"] = $product;
                                $sales[$i]["detalle"] = array_merge([$saleDetails[$x]], $sales[$i]["detalle"]);
                                $data["resume"] = $sales;
                            }
                        }
                    }
                }
            }

            $data["subtotal"] = Sales::select(DB::raw('SUM(total) as value'))->where('created_at','LIKE',$value_search)->first();
            $data["descuento"] = Sales::select(DB::raw('SUM(discount) as value'))->where('created_at','LIKE',$value_search)->first();
            $data["total"] = Sales::select(DB::raw('SUM(total - discount) as value'))->where('created_at','LIKE',$value_search)->first();
        }
        return $data;
    }
}
