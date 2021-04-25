<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function sendResponse($result, $message){
        $response = [
            "success" => true,
            "data" => $result,
            "message" => $message,
            "code" => 200
        ];

        return response()->json([$response], 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404){
        $response = [
            "success" => false,
            "error" => $error,
            "errorMessages" => $errorMessages,
            "code" => $code
        ];

        return response()->json([$response], $code);
    }
}
