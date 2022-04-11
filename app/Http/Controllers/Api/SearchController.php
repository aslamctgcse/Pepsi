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
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $city = $request->city;
        $pincode = $request->pincode;
        $userRange ='20';
        

        // $keyword = 'LIMCA';


        // $latitude ='25.6188491';
        // $longitude ='85.0932166';
        // $pincode ='800001';

        $prod = DB::table('product')
                    ->join('categories','categories.cat_id','=','product.cat_id')
                    ->Leftjoin('product_varient','product.product_id','=','product_varient.product_id')
                    ->Leftjoin('store','product.product_store_id','=','store.store_id')
                    ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                    //->Leftjoin('product_synonym_names','product.product_id','=','product_synonym_names.product_id')
                    ->select('product.product_id', 
                                  'product.cat_id',
                                  'product.product_name',
                                  'product.product_store_id',
                                  'product.product_image',
                                  'product.status',
                                  'product_varient.product_id',
                                  'product_varient.varient_id',
                                  'product_varient.quantity',
                                  'product_varient.unit',
                                  'product_varient.mrp',
                                  'product_varient.discount_percentage',
                                  'product_varient.price',
                                  'product_varient.description',
                                  'product_varient.varient_image',
                                  'store_products.p_id',
                                  'store_products.stock',
                                  'store_products.store_id',
                                  'product.product_store_id as store_id',
                                  'store.store_name',
                                  'store.zip_code as store_zipcode',
                                  'store.city as store_city',
                                  'store.store_state',
                                  'store.address as store_address',
                                  'store.lat as store_lat',
                                  'store.lng as store_lng',
                                  'store.store_desc as store_cat',
                                  //'product_synonym_names.name'
                                )
                    ->where('product.product_name', 'like', '%'.$keyword.'%')
                    ->where('product.product_approved_status', '=','1')
                    //->orWhere('product_synonym_names.name', 'LIKE', '%'.$keyword.'%')
                    ->where('store_products.stock', '>', '0')
                    ->groupBy('product_varient.product_id')
                    ->orderby('product.product_id','asc');

        // if($city){
        //   $prod->where('store.city','like','%' . $city . '%');
        // }

        if($latitude){
          //$prod1->where('store.city','like','%' . $city . '%');
          $prod->whereRaw("(6371*acos( cos( radians(".$latitude.") ) * cos( radians(store.lat) ) * cos( radians(store.lng) - radians(".$longitude.") ) + sin( radians(".$latitude.") ) * sin( radians(store.lat) ) ) ) <= ".$userRange);
        }             
        // if($pincode){
        //   $prod->where('store.zip_code','=',$pincode);
        // }      
        $prod=$prod->get();


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

    public function search_pincode(Request $request)
    {
        $keyword = $request->keyword;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $city = $request->city;
        $pincode = $request->pincode;
        $userRange ='20';
        

        // $latitude ='25.6188491';
        // $longitude ='85.0932166';
        
        // $pincode ='800020';

        $prod = DB::table('product')
                    ->join('categories','product.cat_id','=','categories.cat_id')
                    ->Leftjoin('product_varient','product.product_id','=','product_varient.product_id')
                    ->Leftjoin('store','product.product_store_id','=','store.store_id')
                    ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                    //->Leftjoin('product_synonym_names','product.product_id','=','product_synonym_names.product_id')
                    
                    ->select('product.product_id', 
                                  'product.cat_id',
                                  'product.product_name',
                                  'product.product_store_id',
                                  'product.product_image',
                                  'product.status',
                                  'product_varient.product_id',
                                  'product_varient.varient_id',
                                  'product_varient.quantity',
                                  'product_varient.unit',
                                  'product_varient.mrp',
                                  'product_varient.discount_percentage',
                                  'product_varient.price',
                                  'product_varient.description',
                                  'product_varient.varient_image',
                                  'store_products.p_id',
                                  'store_products.stock',
                                  'store_products.store_id',
                                  'product.product_store_id as store_id',
                                  'store.store_name',
                                  'store.city as store_city',
                                  'store.zip_code as store_zipcode',
                                  'store.store_state',
                                  'store.address as store_address',
                                  'store.lat as store_lat',
                                  'store.lng as store_lng',
                                  'store.store_desc as store_cat',
                                  //'product_synonym_names.name'
                                )
                    //->where('product.product_name', 'like', '%'.$keyword.'%')
                    //->orWhere('product_synonym_names.name', 'LIKE', '%'.$keyword.'%')
                    //->where('store.city','=',$city)
                    ->where('product.product_approved_status', '=','1')
                    ->where('store_products.stock', '>', '0')
                    ->groupBy('product_varient.product_id')
                    ->orderby('product.product_id','asc');

        // if($city){
        //   $prod->where('store.city','like','%' . $city . '%');
        // }  

        if($latitude){
          //$prod1->where('store.city','like','%' . $city . '%');
          $prod->whereRaw("(6371*acos( cos( radians(".$latitude.") ) * cos( radians(store.lat) ) * cos( radians(store.lng) - radians(".$longitude.") ) + sin( radians(".$latitude.") ) * sin( radians(store.lat) ) ) ) <= ".$userRange);
        }

        if($pincode){
          //dd($pincode);
          $prod->where('store.zip_code','=',$pincode);
        }
        $prod=$prod->get();        

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

            $message = array('status'=>'1', 'message'=>'Products found', 'data'=>$prod,'city'=>$city,'pincode'=>$pincode,'keyword'=>$keyword,'lat'=>$latitude);
            return $message;
        }
        else{
            $message = array('status'=>'0', 'message'=>'Products not found', 'data'=>[],'city'=>$city,'pincode'=>$pincode,'keyword'=>$keyword,'lat'=>$latitude);
            return $message;
        }  
    }
}
