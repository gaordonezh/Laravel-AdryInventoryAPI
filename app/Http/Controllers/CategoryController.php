<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $search = $request->search;
        $data = [];
        if(!is_null($search)){
            $categories = Category::where('name','LIKE','%'.$search.'%')->get();    
        }else{
            $categories = Category::all();
        }
        $data["categories"] = $categories;
        return $this->sendResponse($data, "Categories obtained correctly");
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
            'name' => 'required|unique:categories|max:50',
            'description' => 'required|unique:categories|max:255',
        ]);

        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }

        $formData = new Category();
        $formData->name = $request->get('name');
        $formData->description = $request->get('description');
        $formData->status = 1;
        $formData->save();

        $data["category"] = $formData;
        return $this->sendResponse($data, "Category saved correctly");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if(!is_null($category)){
            $data["category"] = $category;
            return $this->sendResponse($data, "Category obtained correctly");
        }else{
            return $this->sendError("Category not foung", ["Category does not exists"], 400);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $formData = Category::find($id);
        if(is_null($formData)){
            return $this->sendError("Category does not exists", ["Category not found"], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|unique:categories,name,'.$id,
            'description' => 'required|max:255|unique:categories,description,'.$id,
            'status' => 'required|numeric',
        ]);
        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }

        $formData->name = $request->get('name');
        $formData->description = $request->get('description');
        $formData->status = $request->get('status');
        $formData->update();

        $data["category"] = $formData;
        return $this->sendResponse($data, "Category updated correctly");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $formData = Category::find($id);
        if(is_null($formData)){
            return $this->sendError("Category does not exists", ["Category not found"], 400);
        }
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|numeric',
        ]);
        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }
        $formData->status = $request->get('status');
        $formData->update();

        $data["category"] = $formData;
        return $this->sendResponse($data, "Category changed status correctly");
    }
}
