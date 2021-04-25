<?php

namespace App\Http\Controllers;

use App\Models\MeasurementUnit;
use Illuminate\Http\Request;
use Validator;

class MeasurementUnitController extends ApiController
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
            $dataGet = MeasurementUnit::where('abbreviation','LIKE','%'.$search.'%')->orWhere('detail','LIKE','%'.$search.'%')->get();
        }else{
            $dataGet = MeasurementUnit::all();
        }
        $data["MeasurementUnits"] = $dataGet;
        return $this->sendResponse($data, "Measurement Units obtained correctly");
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
            'abbreviation' => 'required|unique:measurement_units|max:10',
            'detail' => 'required|unique:measurement_units|max:100',
        ]);

        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }

        $formData = new MeasurementUnit();
        $formData->abbreviation = $request->get('abbreviation');
        $formData->detail = $request->get('detail');
        $formData->save();

        $data["MeasurementUnit"] = $formData;
        return $this->sendResponse($data, "MeasurementUnit saved correctly");
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MeasurementUnit  $measurementUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $formData = MeasurementUnit::find($id);
        if(is_null($formData)){
            return $this->sendError("MeasurementUnit does not exists", ["MeasurementUnit not found"], 400);
        }

        $validator = Validator::make($request->all(), [
            'abbreviation' => 'required|max:10|unique:measurement_units,abbreviation,'.$id,
            'detail' => 'required|max:100|unique:measurement_units,detail,'.$id,
        ]);
        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }

        $formData->abbreviation = $request->get('abbreviation');
        $formData->detail = $request->get('detail');
        $formData->update();

        $data["MeasurementUnit"] = $formData;
        return $this->sendResponse($data, "MeasurementUnit updated correctly");
    }
}
