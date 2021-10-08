<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Service extends Model
{
    use HasFactory;
    public function register($request){
        $checkemail = DB::table('user')->where('email', $request->email);
        if ($checkemail->exists()) {
            return "existing";
        }else{
            $result = DB::insert(
                'insert into user (email, password) values (?, ?)', 
                [$request->email, Hash::make($request->password)]
            );
            return $result;
        }
    }

    public function login($request){
        date_default_timezone_set('Asia/Manila');
        $date = date("Y-m-d H:i:s");  

        $result = DB::table('user')
                ->select('email', 'password')
                ->where('email', '=', $request->email)
                ->get();
                
        $hashedpassword = $result[0]->password;

        if (Hash::check($request->password, $hashedpassword)) {
            return "valid";
        }else{
            // lock account ======================================
            // $result = DB::insert(
            //     'insert into user_login (email, status, date) values (?, ?, ?)', 
            //     [$request->email, 0, strval($date)]
            // );

            // if($result == 1){
            //     $result = DB::table('user_login')
            //     ->select('id')
            //     ->where('email', '=', $request->email)
            //     ->count();
            //     return $result;

            // }
            // lock account ======================================
            
            return "invalid";
        }
    }

    public function order($request){
        $product_id = $request->product_id;
        $quantity = $request->quantity;

        $result = DB::table('Products')
                ->select('Available Stock')
                ->where('Id', '=', $product_id)
                ->get();
        $stock_qty = $result[0]->{'Available Stock'};

        if($stock_qty >= $quantity){
            try{
                $remaining_stock_qty = $stock_qty - $quantity;

                DB::beginTransaction();
                DB::insert(
                    'insert into orders (product_id, quantity) values (?, ?)', 
                    [$product_id, $quantity]
                );
                DB::table('Products')
                    ->where('Id', $product_id)
                    ->update(['Available Stock' => $remaining_stock_qty]);
                DB::commit();

                return "ordered";
            }catch (\Exception $error){
                DB::rollback();
                return $error;
            }   
        }else{
            return "out_of_stock";
        }
    }
}
