<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    public function getUserLogged() {
        $user = Auth::user();
        return $this->sendResponse($user, "Auth user success");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getData = User::all();
        $data["users"] = $getData;
        return $this->sendResponse($data, "Users recovered correctly");
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|max:255|',
            'type' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }

        $formData = new User();
        $formData->name = $request->get('name');
        $formData->email = $request->get('email');
        $formData->password = Hash::make($request->get('password'));
        $formData->type = $request->get('type');
        $formData->status = 1;
        $formData->save();

        $data["user"] = $formData;
        return $this->sendResponse($data, "User created correctly");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getData = User::find($id);
        if(!$getData){
            return $this->sendError("User not found", ["User does not exists"], 422);
        }
        $data["user"] = $getData;
        return $this->sendResponse($data, "User recovered correctly");
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email,'.$id,
        ]);

        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }

        $formData = User::find($id);
        if(is_null($formData)) {
            return $this->sendError("User does not found", ["User not found"], 400);
        }
        $formData->name = $request->get('name');
        $formData->email = $request->get('email');
        $formData->update();

        $data["user"] = $formData;
        return $this->sendResponse($data, "User updated correctly");
    }


    public function updateUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email,'.$id,
            'status' => 'required|numeric',
            'type' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }

        $formData = User::find($id);
        if(is_null($formData)) {
            return $this->sendError("User does not found", ["User not found"], 400);
        }
        $formData->name = $request->get('name');
        $formData->email = $request->get('email');
        $formData->status = $request->get('status');
        $formData->type = $request->get('type');
        $formData->update();

        $data["user"] = $formData;
        return $this->sendResponse($data, "User updated correctly");
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

    public function updatePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|max:25|password:api',
            'new_password' => 'required|max:25|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);
        if($validator->fails()) {
            return $this->sendError("Error de validacion", $validator->errors(), 422);
        }
        $formData = User::find($id);
        if(is_null($formData)) {
            return $this->sendError("User does not found", ["User not found"], 400);
        }
        $formData->password = Hash::make($request->get('new_password'));
        $formData->update();
        $data["passwordUpdated"] = $formData;
        return $this->sendResponse($data, "User password updated correctly");
    }
}
