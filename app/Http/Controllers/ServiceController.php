<?php

namespace App\Http\Controllers;

use Lcobucci\JWT\Signer\Key\InMemory;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Session;

class ServiceController extends Controller
{
    public function register(Request $request){
        //unused =========
        $request->validate([            
            // 'email' => ['required' , 'regex:/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/u'],
            'email' => ['required' , 'email:rfc,dns'],
            'password' => 'required',
        ]);
        //unused ==========

        $serve = new Service();
        $response = $serve->register($request);

        if($response == 1){
            //Successful registration
            return array("message"=> "User successfully registered");
        }else if($response === "existing"){
            //Unsuccessful registration due to email is already taken
            return array("message"=> "Email already taken");
        }else{ 
            //out of bounds
        }
    }

    public function login(Request $request){
        $serve = new Service();
        $response = $serve->login($request);
        // return $response;
        if($response === "valid"){
            //Successful login

            $auth = new AuthController();
            $access_token = $auth->generate($request->email);
            $auth->access($access_token);

            return array("access_token" => $access_token);
        }else if($response === "invalid"){
            //Unsuccessful login due to invalid credentials
            return array("message"=> "Invalid credentials");
        }else{ 
            //out of bounds
        }
    }

    public function order(Request $request){
        $serve = new Service();
        $response = $serve->order($request);
        if($response === "ordered"){
            //Successfully created an order
            return array("message"=> "You have successfully ordered this product.");
        }else if($response === "out_of_stock"){
            //Unsuccessful order due to insufficient stock of a product
            return array("message"=> "Failed to order this product due to unavailability of the stock");
        }else{ 
            //out of bounds
        }
    }
}








