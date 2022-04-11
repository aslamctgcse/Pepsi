<?php

namespace App\Http\Controllers\Api;

use DB;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
 
    public function app(Request $request)
    {
          $app = DB::table('tbl_web_setting')
                      ->first();
                      
        if($app)   { 
            $message = array('status'=>'1', 'message'=>'App Name & Logo', 'data'=>$app);
            return $message;
        }
        else{
            $message = array('status'=>'0', 'message'=>'data not found', 'data'=>[]);
            return $message;
        }

        return $message;
    }
    
    /**
     * get all delivery fees API
     * 
     * 
     */
    public function delivery_info(Request $request)
    {
        // $del_fee = DB::table('freedeliverycart')
        //               ->first();
        try {
            
            $del_fee = DB::table('freedeliverycart')
                          ->get();

            $data = [];
            foreach ($del_fee as $key => $delFeeValue) {
                $data[]  =      [
                                    'id'                =>  (string)$delFeeValue->id ?? '',
                                    'min_cart_value'    =>  (string)$delFeeValue->min_cart_value ?? '',
                                    'del_charge'        =>  (string)$delFeeValue->del_charge ?? '',
                                ];
            }
        
            $message = Response::json(array('status' => '1', 'message' => 'Delivery fee and cart value', 'data' => $data));

            return $message;
        } catch (\Exception $e) {
            
            $message = Response::json(array('status'=>'0', 'message'=>'data not found', 'data' => []));
            return $message;
        }

        // $del_fee = DB::table('freedeliverycart')
        //               ->get();

        // $data = [];
        // foreach ($del_fee as $key => $delFeeValue) {
        //     $data  =    [
        //                     'id'                =>  $delFeeValue['id'],
        //                     'min_cart_value'    =>  $delFeeValue['min_cart_value'],
        //                     'del_charge'        =>  $delFeeValue['del_charge'],
        //                 ];
        // }
                      
        //     $message = Response::json(array('status'=>'1', 'message'=>'Delivery fee and cart value', 'data' => $data));
        //     return $message;
        // }
        // else{
        //     $message = array('status'=>'0', 'message'=>'data not found', 'data'=>[]);
        //     return $message;
        // }

        // return $message;
    }
}
