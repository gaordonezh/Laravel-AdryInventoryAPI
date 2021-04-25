<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\User;

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

        $data["totalCategories"] = count($totalCategories);
        $data["totalMeasurementUnit"] = count($totalMeasurementUnit);
        $data["totalProducts"] = count($totalProducts);
        $data["totalUsers"] = count($totalUsers);
        $data["lastProducts"] = $lastProducts;

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
