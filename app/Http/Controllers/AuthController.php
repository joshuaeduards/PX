<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Configuration;
use Illuminate\Support\Facades\Session;
use App\Service\JWTServiceInterface;    

class AuthController extends ServiceController
{
    public function generate($email, $uid){
        $config = app()->make(Configuration::class);
        assert($config instanceof Configuration);

        $token = $config->builder()
                        // ->issuedBy('http://example.com')
                        ->withClaim('uid', $uid)
                        ->withHeader('email', $email)
                        ->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }
    public function access($value){
        Session::put('access', $value);
        return;
    }


    // public function checkemail($value){
    //     if (!preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $value)){
    //         return 0;
    //     }else{
    //         return 1;
    //     }
    // }
    // public function alpha($value){
    //     if (!preg_match("/^[a-zA-Z]+$/", $value)){
    //         return 0;
    //     }else{
    //         return 1;
    //     }
    // }

    // public function alphanumeric($value){
    //     if (!preg_match("/[^a-z_\-0-9]/i", $value)){
    //         return 0;
    //     }else{
    //         return 1;
    //     }
    // }

}

