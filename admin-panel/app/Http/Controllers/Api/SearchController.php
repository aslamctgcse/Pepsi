<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SearchController extends Controller
{
 
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        #get synonym data
        $prod = DB::table('product')
                    ->Leftjoin('product_varient','product.product_id','=','product_varient.product_id')
                    ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                    ->Leftjoin('product_synonym_names','product.product_id','=','product_synonym_names.product_id')
                    ->where('product.product_name', 'like', '%'.$keyword.'%')
                    ->orWhere('product_synonym_names.name', 'LIKE', '%'.$keyword.'%')
                    //->where('store_products.stock', '>', '0')
                    ->groupBy('product_varient.product_id')
                    ->get();
            //dd($prod);

        if(count($prod)>0){
            $result =array();
            $i = 0;

            foreach ($prod as $prods) {
                array_push($result, $prods);

                $app = json_decode($prods->product_id);
                $apps = array($app);
                $app = DB::table('product_varient')
                        ->whereIn('product_id', $apps)
                        ->get();
                        
                $result[$i]->varients = $app;
                $i++; 
             
            }

            $message = array('status'=>'1', 'message'=>'Products found', 'data'=>$prod);
            return $message;
        }
        else{
            $message = array('status'=>'0', 'message'=>'Products not found', 'data'=>[]);
            return $message;
        }  
    }
}
