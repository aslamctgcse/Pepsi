<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class CategoryController extends Controller
{

    public function cate(Request $request)
    {

     $cat = DB::table('categories')
          ->where('level', 0)
          ->where('status', 1)
          ->get();

        if(count($cat)>0) {

          $result = array();
          $i = 0;

          foreach ($cat as $cats) {

              array_push($result, $cats);

              $app = json_decode($cats->cat_id);
              $apps = array($app);
              $app = DB::table('categories')
                      ->whereIn('parent', $apps)
                      ->where('level', 1)
                      ->where('status', 1)
                      ->get();
                      
             //for view cat image for sub-cateogiry not present        
              //$result[$i]->subcategory = $app;
              if(count($app)>0){                  
                $result[$i]->subcategory = $app;
              }else{
                $app23 = DB::table('categories')
                      ->where('cat_id', $cats->cat_id)
                      ->where('level', 0)
                      ->where('status', 1)
                      ->get();      
                $app23[0]->subchild=[];      

                $result[$i]->subcategory = $app23;
              }
              
              $i++; 
              $res =array();
              $j = 0;

              foreach ($app as $appss) {
                  array_push($res, $appss);
                  $c = array($appss->cat_id);
                  $app1 = DB::table('categories')
                          ->whereIn('parent', $c)
                          ->where('level', 2)
                          ->get(); 


              if(count($app1)>0){ 

                  $res[$j]->subchild = $app1;
                  $j++; 
                 }
                 else{
                  $emptyArray = array();
                   //array_push($emptyArray, $appss);
                   $res[$j]->subchild = [];
                  $j++;  
                 }
               }   
          }

          $message = array('status'=>'1', 'message'=>'data found', 'data'=>$cat);
          return $message;
        } else {
            $message = array('status'=>'0', 'message'=>'data not found', 'data'=>[]);
            return $message;
        }
    }
      
    public function cat_product(Request $request)
    {
        $cat_id =$request->cat_id;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $city =$request->city;
        $pincode =$request->pincode;
        $userRange ='20';
        
        // $cat_id ='154';
        // $latitude ='25.6188491';
        // $longitude ='85.0932166';
        //$city ='delhi';
        //$pincode ='110091';

        // $prod =  DB::table('product')
        //             //->join('categories','categories.cat_id','=','product.cat_id')
        //             ->Leftjoin('product_varient','product_varient.product_id','=','product.product_id')
        //             ->Leftjoin('store','store.store_id','=','product.product_store_id')
        //             ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
        //             ->select(
        //                 'product.product_id',
        //                 'product.cat_id',
        //                 'product.product_name',
        //                 'product.product_image',
        //                 'product.product_store_id as store_id',
        //                 'store.store_name',
        //                 'store.city as store_city',
        //                 'store.store_state',
        //                 'store.address as store_address',
        //                 'store.lat as store_lat',
        //                 'store.lng as store_lng',
        //                 'store.store_desc as store_cat',
        //             )
        //             ->where('product.cat_id', $cat_id)
        //             //->where('categories.cat_id','=',$cat_id)
        //             //->orWhere('categories.parent','=',$cat_id)
        //             // ->where('store_products.stock', '>','0')
        //             ->get();

        $prod1 = DB::table('product')
        ->join('categories','categories.cat_id','=','product.cat_id')
        ->Leftjoin('product_varient','product_varient.product_id','=','product.product_id')
        ->Leftjoin('store','store.store_id','=','product.product_store_id')
        ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
        ->select(
                        'product.product_id',
                        'product.cat_id',
                        'categories.*',
                        'product.product_name',
                        'product.product_image',
                        'product.product_store_id as store_id',
                        'store.store_name',
                        'store.city as store_city',
                        'store.zip_code as store_zip_code',
                        'store.store_state',
                        'store.address as store_address',
                        'store.lat as store_lat',
                        'store.lng as store_lng',
                        'store.store_desc as store_cat',
                    )
        //->where('categories.cat_id','!=', $cat_id) 
        //->where('categories.cat_id','=',$cat_id)
        //->orWhere('categories.parent','=',$cat_id)
        ->where('product.product_approved_status', '=','1')
        ->orderby('product.product_id','asc');
        

        if($latitude){
          //$prod1->where('store.city','like','%' . $city . '%');
          $prod1->whereRaw("(6371*acos( cos( radians(".$latitude.") ) * cos( radians(store.lat) ) * cos( radians(store.lng) - radians(".$longitude.") ) + sin( radians(".$latitude.") ) * sin( radians(store.lat) ) ) ) <= ".$userRange);
        }             
        // if($pincode){
        //   $prod1->where('store.zip_code','=',$pincode);
        // }      
        $prod1=$prod1->get();


        $prod=array(); //for showing distinct product of only index 0
        $kidss=0;
        foreach($prod1 as $catproductrel){
              //dd($catproductrel->parent.' @@ '.(int)$cat_id);

               if($catproductrel->parent==(int)$cat_id){
                //dd('ddd');
                  $prod[$kidss] = $catproductrel;
                  $kidss++;
               }else if($catproductrel->cat_id==(int)$cat_id){
                //dd('dddggg');
                  $prod[$kidss] = $catproductrel;
                  $kidss++;
               }else{

               }
        }               


        if(count($prod)>0){
            $result =array();
            // $result1 =array();
            $i = 0;

            foreach ($prod as $prods) {
                // array_push($result, $prods);
                $result[] =    [
                                    'product_id'     =>  (string)$prods->product_id ?? '',
                                    'cat_id'         =>  (string)$prods->cat_id ?? '',
                                    'product_name'   =>  (string)$prods->product_name ?? '',
                                    'product_image'  =>  (string)$prods->product_image ?? '',
                                    'store_id'       =>  (string)$prods->store_id ?? '',
                                    'store_name'     =>  (string)$prods->store_name ?? '',
                                    'store_zipcode'  =>  (string)$prods->store_zip_code ?? '',
                                    'store_city'     =>  (string)$prods->store_city ?? '',
                                    'store_state'    =>  (string)$prods->store_state ?? '',
                                    'store_address'  =>  (string)$prods->store_address ?? '',
                                    'store_lat'      =>  (string)$prods->store_lat ?? '',
                                    'store_lng'      =>  (string)$prods->store_lng ?? '',
                                    'store_cat'      =>  (string)$prods->store_cat ?? '',
                                ];

                $app = json_decode($prods->product_id);
                $apps = array($app);
                $app = DB::table('product_varient')
                         ->Leftjoin('deal_product','product_varient.varient_id','=','deal_product.varient_id')
                         ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                         ->select('product_varient.product_id', 
                                  'product_varient.varient_id',
                                  'product_varient.description',
                                  DB::raw('ROUND(product_varient.price) as price'),
                                  'product_varient.mrp',
                                  'product_varient.varient_image',
                                  'product_varient.unit',
                                  'product_varient.quantity',
                                  'deal_product.deal_price',
                                  'deal_product.valid_from',
                                  'deal_product.valid_to',
                                  'store_products.stock')
                         ->whereIn('product_varient.product_id', $apps)
                        //  ->where('store_products.stock', '>','0')
                         ->groupBy('product_varient.product_id')
                         ->get();
              
                // $result[$i]->varients = $app;
                
                foreach($app as $appValue) {
                   
                    $result[$i]['varients'][]   =    [                                                    
                                                        "product_id"        =>  (string)$appValue->product_id ?? '',
                                                        "varient_id"        =>  (string)$appValue->varient_id ?? '',
                                                        "description"       =>  (string)$appValue->description ?? '',
                                                        "price"             =>  (string)$appValue->price ? round($appValue->price) : '',
                                                        "mrp"               =>  (string)$appValue->mrp ?? '',
                                                        "varient_image"     =>  (string)$appValue->varient_image ?? '',
                                                        "unit"              =>  (string)$appValue->unit ?? '',
                                                        "quantity"          =>  (string)$appValue->quantity ?? '',
                                                        "deal_price"        =>  (string)$appValue->deal_price ? round($appValue->deal_price) : '',
                                                        "valid_from"        =>  (string)$appValue->valid_from ?? '',
                                                        "valid_to"          =>  (string)$appValue->valid_to ?? '',
                                                        "stock"             =>  (!is_null($appValue->stock) OR $appValue->stock != 0) ? (string)$appValue->stock : (string)0
                                                    ];
                                  
                }
                $i++; 
            }

            $message = array('status'=>'1', 'message'=>'Products found', 'data'=>$result);
            return $message;
        }
        else{
            $message = array('status'=>'0', 'message'=>'Products not found', 'data'=>[]);
            return $message;
        }
     
    } 


    public function store_based_product(Request $request)
    {
        $store_id =$request->store_id;
        $store_lat =$request->latitude;
        $store_long =$request->longitude;
        $city =$request->city;
        $pincode =$request->pincode;

        // $store_lat ='28.7041';
        // $store_long ='77.1025';
        // $city ='delhi';
        // $pincode ='110091';


        // $store_id ='3';

        $products =  DB::table('product')
                    ->join('categories','categories.cat_id','=','product.cat_id')
                    ->Leftjoin('product_varient','product_varient.product_id','=','product.product_id')
                    ->Leftjoin('store','product.product_store_id','=', 'store.store_id')
                    ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                    ->select(
                        'product.product_id',
                        'product.cat_id',
                        'product.product_store_id',
                        'product.product_name',
                        'product.product_image',
                        'product.product_store_id as store_id',
                        'store.store_name',
                        'store.zip_code as store_zip_code',
                        'store.city as store_city',
                        'store.store_state',
                        'store.address as store_address',
                        'store.lat as store_lat',
                        'store.lng as store_lng',
                        'store.store_desc as store_cat',
                    )
                    ->where('product.product_store_id', $store_id)
                    ->where('product.product_approved_status', '=','1')
                    ->orderby('product.product_id','asc');
      if($city){
            $products->where('store.city','like','%' . $city . '%');
      }             
      // if($pincode){
      //       $products->where('store.zip_code','=',$pincode);
      // }      
      $products=$products->get();
            
       $result =array();


        if(count($products)>0){
            

            foreach ($products as $prods) {
                $appVarient = DB::table('product_varient')
                         ->Leftjoin('deal_product','product_varient.varient_id','=','deal_product.varient_id')
                         ->select('product_varient.product_id', 
                                  'product_varient.varient_id',
                                  'product_varient.description',
                                  DB::raw('ROUND(product_varient.price) as price'),
                                  'product_varient.mrp',
                                  'product_varient.varient_image',
                                  'product_varient.unit',
                                  'product_varient.quantity',
                                  'deal_product.deal_price',
                                  'deal_product.valid_from',
                                  'deal_product.valid_to')
                         ->where('product_varient.product_id', $prods->product_id)
                         ->groupBy('product_varient.product_id')
                         ->get();
                     foreach($appVarient as $appValue) {
                         $appVarientkkk = DB::table('store_products')
                         ->select('store_products.stock')
                         ->where('store_products.varient_id', $appValue->varient_id)
                         ->first();
                         if($appVarientkkk){
                           $appValue->stock = $appVarientkkk->stock; 
                         }else{
                            $appValue->stock = 0;
                         }
                     }    

             $result[] =    [
                                    'product_id'     =>  (string)$prods->product_id ?? '',
                                    'cat_id'         =>  (string)$prods->cat_id ?? '',
                                    'product_name'   =>  (string)$prods->product_name ?? '',
                                    'product_image'  =>  (string)$prods->product_image ?? '',
                                    'store_id'       =>  (string)$prods->store_id ?? '',
                                    'store_name'  =>  (string)$prods->store_name ?? '',
                                    'store_city'  =>  (string)$prods->store_city ?? '',
                                    'store_zipcode'  =>  (string)$prods->store_zip_code ?? '',
                                    'store_state'  =>  (string)$prods->store_state ?? '',
                                    'store_address'  =>  (string)$prods->store_address ?? '',
                                    'store_lat'  =>  (string)$prods->store_lat ?? '',
                                    'store_lng'  =>  (string)$prods->store_lng ?? '',
                                    'store_cat'  =>  (string)$prods->store_cat ?? '',
                                    'varients'   =>  $appVarient,
                                ];    
            }

            $message = array('status'=>'1', 'message'=>'Products Found', 'data'=>$result);
            return $message;
        }
        else{
            $message = array('status'=>'0', 'message'=>'Products Not Found!', 'data'=>[]);
            return $message;
        }
     
    }   
    
    public function cat(Request $request)
    {
     $cat = DB::table('categories')
                ->where('level', 0)
                ->where('status', 1)
                ->get();

        if(count($cat)>0){
            $result =array();
            $i = 0;

            foreach ($cat as $cats) {
                array_push($result, $cats);

                $app = json_decode($cats->cat_id);
                $apps = array($app);
                $app = DB::table('categories')
                        ->whereIn('parent', $apps)
                        ->where('status', 1)
                        ->where('level',1)
                        ->get();
                        
                if(count($app)>0){
                $result[$i]->subcategory = $app;
                $i++; 
                $res =array();
                $j = 0;
                foreach ($app as $appss) {
                    array_push($res, $appss);
                    $c = array($appss->cat_id);
                    $app1 = DB::table('categories')
                            ->whereIn('parent', $c)
                            ->where('level',2)
                            ->get();
                if(count($app1)>0){        
                    $res[$j]->subchild = $app1;
                    $j++; 
                    $res2 =array();
                    $k = 0;
                    foreach ($app1 as $apps1) {
                        array_push($res2, $apps1);
                        $catt = array($apps1->cat_id);
                        $prod = DB::table('product')
                                ->whereIn('cat_id', $catt)
                                ->get();
                                
                     $res2[$k]->product = $prod;
                     $k++;   
                     }
                    
                   }
                   else{
                       $pr = DB::table('product')
                        ->whereIn('cat_id', $c)
                        ->get();    
                        $res[$j]->product = $pr;
                        $j++; 
                   }
                 }   
                }
                else{
                $app = DB::table('product')
                        ->whereIn('cat_id', $apps)
                        ->get();    
                $result[$i]->product = $app;
                $i++; 
                }
            }

            $message = array('status'=>'1', 'message'=>'data found', 'data'=>$cat);
            return $message;
        }
        else{
            $message = array('status'=>'0', 'message'=>'data not found', 'data'=>[]);
            return $message;
        }
    }
    
    public function varient(Request $request)
    {
      $prod_id = $request->product_id;
      $varient = DB::table('product_varient')
                    ->select('product_varient.*','store_products.stock')
                    ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                    ->where('product_varient.product_id', $prod_id)
                    ->get();

      $output = [];
      foreach ($varient as $varientValue) {
        $output[] =   [
                        'varient_id'      => (string)$varientValue->varient_id ?? '',
                        'product_id'      => (string)$varientValue->product_id ?? '',
                        'quantity'        => (string)$varientValue->quantity ?? '',
                        'unit'            => (string)$varientValue->unit ?? '',
                        'mrp'             => (string)$varientValue->mrp ? round($varientValue->mrp) : '',
                        'discount_percentage'  => (string)$varientValue->discount_percentage ?? '',
                        'price'           => (string)$varientValue->price ? round($varientValue->price) : '',
                        'description'     => (string)$varientValue->description ?? '',
                        'varient_image'   => (string)$varientValue->varient_image ?? '',
                        'stock'           => (string)$varientValue->stock ?? '',
                      ];
      }

      if(count($varient) > 0) { 
        $message = array('status'  =>  '1', 'message'  =>  'varients', 'data'  =>  (object)$output);
        return $message;
      } else {
        $message = array('status'  =>  '0', 'message'  =>  'data not found', 'data'  =>  []);
        return $message;
      }    
                
    }
    
    
     public function dealproduct(Request $request)
    {
        $d = Carbon::Now();
        $deal_p= DB::table('deal_product')
                ->join('product_varient', 'deal_product.varient_id', '=', 'product_varient.varient_id')
                ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                ->select('deal_product.deal_price as price', 'product_varient.varient_image', 'product_varient.quantity','product_varient.unit', 'product_varient.mrp','product_varient.description' ,'product.product_name','product.product_image','product_varient.varient_id','product.product_id','deal_product.valid_to','deal_product.valid_from')
                ->whereDate('deal_product.valid_from','<=',$d->toDateString())
                ->WhereDate('deal_product.valid_to','>',$d->toDateString())
                ->get();
          
          
          if(count($deal_p)>0){
            $result =array();
            $i = 0;
             $j = 0;
            foreach ($deal_p as $deal_ps) {
                array_push($result, $deal_ps);
                
                $val_to =  $deal_ps->valid_to;       
                $diff_in_minutes = $d->diffInMinutes($val_to); 
                $totalDuration =  $d->diff($val_to)->format('%H:%I:%S');
                $result[$i]->timediff = $diff_in_minutes;
                $i++; 
                $result[$j]->hoursmin= $totalDuration;
                $j++; 
            }

            $message = array('status'=>'1', 'message'=>'Products found', 'data'=>$deal_p);
            return $message;
        }
        else{
            $message = array('status'=>'0', 'message'=>'Products not found', 'data'=>[]);
            return $message;
        }        

    }
    
    
       public function top_six(Request $request){
      $topsix = DB::table('product_varient')
                  ->join ('product', 'product_varient.product_id', '=', 'product.product_id')
                  ->Leftjoin ('store_orders', 'product_varient.varient_id', '=', 'store_orders.varient_id') 
                  ->Leftjoin ('orders', 'store_orders.order_cart_id', '=', 'orders.cart_id')
                  ->join ('categories', 'product.cat_id','=','categories.cat_id')
                  ->select('categories.title', 'categories.image', 'categories.description','categories.cat_id',DB::raw('count(store_orders.varient_id) as count'))
                  ->groupBy('categories.title', 'categories.image', 'categories.description','categories.cat_id')
                  ->orderBy('count','desc')
                  ->limit(6)
                  ->get();
         if(count($topsix)>0){
          $message = array('status'=>'1', 'message'=>'Top Six Categories', 'data'=>$topsix);
          return $message;
        }
        else{
          $message = array('status'=>'0', 'message'=>'Nothing in Top Six', 'data'=>[]);
          return $message;
        }          
  }    
  
    
}