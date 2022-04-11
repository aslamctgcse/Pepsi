<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use App\Product;
use App\Varient;
use App\Storeproduct;

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
                      
              $result[$i]->subcategory = $app;
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
        $prod =  DB::table('product')
                    ->Leftjoin('product_varient','product_varient.product_id','=','product.product_id')
                    ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                    ->select(
                        'product.product_id',
                        'product.cat_id',
                        'product.product_name',
                        'product.product_image',
                        'product_varient.varient_id'
                    )
                    ->where('product.cat_id', $cat_id)
                    // ->where('store_products.stock', '>','0')
                    ->get();

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
                                    'product_image'  =>  (string)$prods->product_image ?? ''
                                ];

                //$app = json_decode($prods->product_id); //sonu
                $app = json_decode($prods->varient_id);
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
                         //->whereIn('product_varient.product_id', $apps) //old
                         ->whereIn('product_varient.varient_id', $apps) //get varient id 
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
        //$message = array('status'  =>  '1', 'message'  =>  'varients', 'data'  =>  (object)$output); //original
        $message = array('status'  =>  '1', 'message'  =>  'varients', 'data'  => $output);
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

  #added code to get product n its variant list of a particular parent category beg 
  public function parent_cat_product(Request $request){
    $cat_id =$request->cat_id;
    #make relation between product and variant on the basis of product id
  
    $prod=Product::with('productvarient','productvarient.productstock')
    ->where('product.cat_id',$cat_id) 
    //->has('productvarient.productstock', '>' , 0) 
    ->get();

    
   if(count($prod)>0){
        $message = array('status'=>'1', 'message'=>'Products found', 'data'=>$prod);
                return $message;
    }else{
        $message = array('status'=>'0', 'message'=>'Products not found', 'data'=>[]);
            return $message;
    }

  }   
  #added code to get product n its variant list of a particular parent category end 

  
    
}
