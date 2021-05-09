<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\User;
use App\Models\Sales;
use Carbon\Carbon;

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

        $dateOne = Sales::where('created_at','LIKE',Carbon::now()->format('Y-m-d').'%')->sum('total');
        $dateTwo = Sales::where('created_at','LIKE',Carbon::now()->subDays(1)->format('Y-m-d').'%')->sum('total');
        $dateThree = Sales::where('created_at','LIKE',Carbon::now()->subDays(2)->format('Y-m-d').'%')->sum('total');
        $dateFour = Sales::where('created_at','LIKE',Carbon::now()->subDays(3)->format('Y-m-d').'%')->sum('total');
        $dateFive = Sales::where('created_at','LIKE',Carbon::now()->subDays(4)->format('Y-m-d').'%')->sum('total');
        $dateSix = Sales::where('created_at','LIKE',Carbon::now()->subDays(5)->format('Y-m-d').'%')->sum('total');
        $dateSeven = Sales::where('created_at','LIKE',Carbon::now()->subDays(6)->format('Y-m-d').'%')->sum('total');

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
