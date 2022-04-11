<?php

namespace App\Http\Controllers\Storeapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;

class AddproductController extends Controller
{
    public function productselect(Request $request)
    {
        $store_id = $request->store_id;
        $selected =  DB::table('store_products')
                ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                ->where('store_products.store_id', $store_id)
                ->get();  
                
        $check=  DB::table('store_products')
                ->where('store_id', $store_id)
                ->get(); 
        if(count($check)>0)  {
        foreach($check as $ch){
            $ch2 = $ch->varient_id;
            $ch3[] = array($ch2);
        }
          $products = DB::table('product_varient')
                ->join('product','product_varient.product_id', '=', 'product.product_id')
                ->whereNotIn('product_varient.varient_id', $ch3)
                ->get();    
        
    	                     
        if(count($products)>0){
        	$message = array('status'=>'1', 'message'=>'Store Products', 'data'=>$products);
	        return $message;
              }
    	else{
    		$message = array('status'=>'0', 'message'=>'Products not found', 'data'=>[]);
	        return $message;
    	}
        }else{
             $products = DB::table('product_varient')
                ->join('product','product_varient.product_id', '=', 'product.product_id')
                ->get();
        
        if(count($products)>0){
        	$message = array('status'=>'1', 'message'=>'Store Products', 'data'=>$products);
	        return $message;
              }
    	else{
    		$message = array('status'=>'0', 'message'=>'Products not found', 'data'=>[]);
	        return $message;
    	}  
        }
      
    }
    
    
    public function add_products(Request $request)
    {
       $store_id=$request->store_id;
       $varient_id =$request->varient_id;
        $stock = $request->stock;	 		   
    	 $stockupdate = DB::table('store_products')
                ->update(['store_id'=>$store_id,
                    'stock'=>$stock,
                    'varient_id'=>$varient_id]);
         if($stockupdate){
           	$message = array('status'=>'1', 'message'=>'Product Added');
	        return $message;
         } else{
        	$message = array('status'=>'0', 'message'=>'something went wrong');
	        return $message;
         }
    }
    
     public function delete_product(Request $request)
    {
        $id =$request->p_id;
    	 $delete = DB::table('store_products')
                ->where('p_id', $id)
                ->delete();
                
         if($delete){
           	$message = array('status'=>'1', 'message'=>'product deleted successfully');
	        return $message;
         } else{
        	$message = array('status'=>'0', 'message'=>'something went wrong');
	        return $message;
         }        
    }
    
     public function stock_update(Request $request)
    {
        $id =$request->p_id;
        $stock = $request->stock;
    	$stockupdate = DB::table('store_products')
                ->where('p_id', $id)
                ->update(['stock'=>$stock]);
                 
       if($stockupdate){
           	$message = array('status'=>'1', 'message'=>'Stock Updated');
	        return $message;
         } else{
        	$message = array('status'=>'0', 'message'=>'something went wrong');
	        return $message;
         }

    }
    
    public function store_products(Request $request)
    {
        $store_id = $request->store_id;
        $storeproducts = DB::table('store_products')
                            ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                            ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                            ->where('store_id', $store_id)
                            ->get();
       
        $storeProductData  = [];
        foreach($storeproducts as $storeProductsValue) {
            $storeProductData[] = [
                "p_id"                  =>  (string)$storeProductsValue->p_id ?? '',
                "varient_id"            =>  (string)$storeProductsValue->varient_id ?? '',
                "stock"                 =>  (string)$storeProductsValue->stock ?? '',
                "store_id"              =>  (string)$storeProductsValue->store_id ?? '',
                "product_id"            =>  (string)$storeProductsValue->product_id ?? '',
                "quantity"              =>  (string)$storeProductsValue->quantity ?? '',
                "unit"                  =>  (string)($storeProductsValue->unit) ?? '',
                "mrp"                   =>  (string)($storeProductsValue->mrp) ? round($storeProductsValue->mrp) : '',
                "discount_percentage"   =>  (string)$storeProductsValue->discount_percentage ?? '',
                "price"                 =>  (string)($storeProductsValue->price) ? round($storeProductsValue->price) : '',
                "description"           =>  (string)$storeProductsValue->description ?? '',
                "varient_image"         =>  (string)$storeProductsValue->varient_image ?? '',
                "cat_id"                =>  (string)$storeProductsValue->cat_id ?? '',
                "product_name"          =>  (string)$storeProductsValue->product_name ?? '',
                "product_image"         =>  (string)$storeProductsValue->product_image ?? '',
            ];
        }
                
       if($storeproducts){
           	$message = array('status'  =>  '1', 'message'  =>  'Store Products', 'data'  =>  $storeProductData);
	        return $message;
        } else{
        	$message = array('status'  =>  '0', 'message'  =>  'something went wrong');
	        return $message;
        }
    }
    
}
