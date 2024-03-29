<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\MeasurementUnit;
use Illuminate\Http\Request;
use Validator;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $idCategory = $request->category;

        $data = [];
        $categories = Category::all();
        $units = MeasurementUnit::all();

        if(!is_null($search) || !is_null($idCategory)) {
            $products = Product::where('name','LIKE','%'.$search.'%')
                                ->where('id_category','LIKE','%'.$idCategory.'%')
                                ->get();
        }else{
            $products = Product::all();
            for ($i=0; $i < count($products); $i++) {
                $products[$i]["category"] = [];
                $products[$i]["measurement_unit"] = [];                
                for ($e=0; $e < count($categories); $e++) { 
                    if($products[$i]['id_category'] == $categories[$e]["id"]){
                        $products[$i]["category"] = array_merge([$categories[$e]], $products[$i]["category"]);
                    }
                }
                for ($e=0; $e < count($units); $e++) { 
                    if($products[$i]['id_measurement_unit'] == $units[$e]["id"]){
                        $products[$i]["measurement_unit"] = array_merge([$units[$e]], $products[$i]["measurement_unit"]);
                    }
                }
            }
        }
        $data["products"] = $products;
        return $this->sendResponse($data, "Products obtained correctly");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_category' => 'required|numeric|min:1',
            'id_measurement_unit' => 'required|numeric|min:1',
            'name' => 'required|unique:products|max:50',
            'description' => 'max:255',
            'unit_sale_price' => 'required|numeric|min:0',
            'unit_purchase_price' => 'required|numeric|min:0',
            'stock' => 'numeric|min:0',
        ]);

        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }

        $formData = new Product();
        $formData->id_category = $request->get('id_category');
        $formData->id_measurement_unit = $request->get('id_measurement_unit');
        $formData->name = $request->get('name');
        $formData->description = $request->get('description');
        $formData->unit_sale_price = $request->get('unit_sale_price');
        $formData->unit_purchase_price = $request->get('unit_purchase_price');
        $formData->stock = $request->get('stock');
        $formData->status = 1;
        $formData->save();

        $data["Product"] = $formData;
        return $this->sendResponse($data, "Product saved correctly");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataGet = Product::find($id);
        $data = [];
        if(!is_null($dataGet)){
            $data["product"] = $dataGet;
            return $this->sendResponse($data, "Product obtained correctly");   
        }else{
            return $this->sendResponse(["Product does not exists"], "Product not found");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_category' => 'required|numeric|min:1',
            'id_measurement_unit' => 'required|numeric|min:1',
            'name' => 'required|max:50|unique:products,name,'.$id,
            'description' => 'max:255',
            'unit_sale_price' => 'required|numeric|min:0',
            'unit_purchase_price' => 'required|numeric|min:0',
            'stock' => 'numeric|min:0',
            'status' => 'required|numeric|min:0',
        ]);

        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }

        $formData = Product::find($id);
        if(is_null($formData)){
            return $this->sendError("Products does not found", ["Product not found"], 400);
        }
        $formData->id_category = $request->get('id_category');
        $formData->id_measurement_unit = $request->get('id_measurement_unit');
        $formData->name = $request->get('name');
        $formData->description = $request->get('description');
        $formData->unit_sale_price = $request->get('unit_sale_price');
        $formData->unit_purchase_price = $request->get('unit_purchase_price');
        $formData->status = $request->get('status');
        $formData->stock = $request->get('stock');
        $formData->update();

        $data["Product"] = $formData;
        return $this->sendResponse($data, "Product updated correctly");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $formData = Product::find($id);
        if(is_null($formData)){
            return $this->sendError("Product does not exists", ["Product not found"], 400);
        }
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|numeric|min:0',
        ]);
        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }
        $formData->status = $request->get('status');
        $formData->update();

        $data["product"] = $formData;
        return $this->sendResponse($data, "Product changed status correctly");
    }

    public function addStock(Request $request, $id)
    {
        $formData = Product::find($id);
        if(is_null($formData)) {
            return $this->sendError("Product does not exists", ["Product not found"], 400);
        }
        
        $validator = Validator::make($request->all(), [
            'stock' => 'required|numeric|min:0',
        ]);
        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }
        $formData->stock = $formData->stock + $request->get('stock');
        $formData->update();

        $data["product"] = $formData;
        return $this->sendResponse($data, "Add stock correctly");
    }
}
