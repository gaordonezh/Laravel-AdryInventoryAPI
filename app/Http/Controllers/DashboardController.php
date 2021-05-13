<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\User;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalCategories = Category::where('status',1)->get();
        $totalMeasurementUnit = MeasurementUnit::all();
        $totalProducts = Product::where('status',1)->get();
        $totalUsers = User::where('status',1)->get();
        $lastProducts = Product::where('status',1)->orderBy('id', 'desc')->limit(5)->get();

        $dateOne = Sales::select(DB::raw('SUM(total - discount) as total'))->where('status',1)->where('created_at','LIKE',Carbon::now()->format('Y-m-d').'%')->first();
        $dateTwo = Sales::select(DB::raw('SUM(total - discount) as total'))->where('status',1)->where('created_at','LIKE',Carbon::now()->subDays(1)->format('Y-m-d').'%')->first();
        $dateThree = Sales::select(DB::raw('SUM(total - discount) as total'))->where('status',1)->where('created_at','LIKE',Carbon::now()->subDays(2)->format('Y-m-d').'%')->first();
        $dateFour = Sales::select(DB::raw('SUM(total - discount) as total'))->where('status',1)->where('created_at','LIKE',Carbon::now()->subDays(3)->format('Y-m-d').'%')->first();
        $dateFive = Sales::select(DB::raw('SUM(total - discount) as total'))->where('status',1)->where('created_at','LIKE',Carbon::now()->subDays(4)->format('Y-m-d').'%')->first();
        $dateSix = Sales::select(DB::raw('SUM(total - discount) as total'))->where('status',1)->where('created_at','LIKE',Carbon::now()->subDays(5)->format('Y-m-d').'%')->first();
        $dateSeven = Sales::select(DB::raw('SUM(total - discount) as total'))->where('status',1)->where('created_at','LIKE',Carbon::now()->subDays(6)->format('Y-m-d').'%')->first();
        $ventasMES = Sales::select(DB::raw('SUM(total - discount) as total'))->where('status',1)->where('created_at','LIKE',Carbon::now()->subDays(6)->format('Y-m').'%')->first();

        $dataDates['uno'] = $dateOne;
        $dataDates['dos'] = $dateTwo;
        $dataDates['tres'] = $dateThree;
        $dataDates['cuatro'] = $dateFour;
        $dataDates['cinco'] = $dateFive;
        $dataDates['seis'] = $dateSix;
        $dataDates['siete'] = $dateSeven;

        $data["totalCategories"] = count($totalCategories);
        $data["totalMeasurementUnit"] = count($totalMeasurementUnit);
        $data["totalProducts"] = count($totalProducts);
        $data["totalUsers"] = count($totalUsers);
        $data["lastProducts"] = $lastProducts;
        $data["dates"] = $dataDates;
        $data["ventasMES"] = $ventasMES;

        return $this->sendResponse($data, "Dashboard data obtained correctly");
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
