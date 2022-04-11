<?php

namespace App\Http\Controllers\Website;

use DB;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    # Bind the website path.
    protected $viewPath = 'website.home.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response

     */
    public $cat_crousel;
    public $coupondata;
    public $terms;
    public function __construct(){
      #terms to show in popup beg
      $terms=DB::table('termspage')->first();

      #terms end
      #coupon data beg
      $coupondata=DB::table('coupon')->first();

      #coupon data end
      $cat_crousel = DB::table('categories')
          ->where('level', 0)
          ->where('status', 1)
          ->get();
          

        if(count($cat_crousel)>0) {

          $result = array();
          $i = 0;

          foreach ($cat_crousel as $cats) {

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
      }
      //dd($cat_crousel);
      $this->cat_crousel=$cat_crousel;
      $this->coupondata=$coupondata;
      $this->terms=$terms;
    }
      public function index(Request $request)
      {

        $pincode = Session::get('visitor_pincode'); //get  session
        $vCity = Session::get('visitor_city');
        $vLat = Session::get('visitor_lat'); //get latitude session
        $vLng = Session::get('visitor_lng');  //get longitude session
        if(Session::get('visitor_range')){
          $vRange = Session::get('visitor_range');  //get longitude session
        }else{
          $vRange = Session::get('visitor_range');  //get longitude session
        }

         #added code to show variants of products on home page and not show subcategory beg 11 dec 2020
          #get varient data beg
               
               $productsvariants=DB::table('product_varient')->select('product_varient.*','store_products.stock')
                                 ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                                 //->where('store_products.stock','>',0)
                                 ->get();

               
               $variantproductarary=array();
               foreach($productsvariants as $productsvariants){
                   if(isset($variantproductarary[$productsvariants->product_id])){
                    $variantproductarary[$productsvariants->product_id][]= $productsvariants;
                   }else{
                      $variantproductarary[$productsvariants->product_id][]= $productsvariants;
                   }
               }
           
               #get varient data end
               #getproductarray beg
               $catproductrels=DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product_varient.*','store_products.stock')
                 ->distinct()
                 ->join('categories','product.cat_id','=','categories.cat_id')
                ->Leftjoin('product_varient','product_varient.product_id','=','product.product_id')
                ->Leftjoin('product_synonym_names','product.product_id','=','product_synonym_names.product_id')
                ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
                //->limit(6)
                ->get();
               //dd($catproductrels);
                 $productarray=array(); //for showing distinct product of only index 0
                 $catproductarrays=array(); //added to show categorywise product
                 $catarray=array();
                 foreach($catproductrels as $catproductrel){
                         if(isset($catarray[$catproductrel->pcid])){
                         
                             $catarray[$catproductrel->pcid]=$catproductrel;
                            
                             $productarray[$catproductrel->product_id][]=$catproductrel;
                             $catproductarrays[$catproductrel->pcid][$catproductrel->product_id][]=$catproductrel;
                            
                        }else{
                            
                             $catarray[$catproductrel->pcid]=$catproductrel;
                             
                               $productarray[$catproductrel->product_id][]=$catproductrel;
                               $catproductarrays[$catproductrel->pcid][$catproductrel->product_id][]=$catproductrel;
                        }

                 }
              //dd($catproductarrays);

               #getproductarray end
         #added code to show variants of products on home page and not show subcategory end
      
         $cat_crousel= $this->cat_crousel;
         $coupondata= $this->coupondata;
         $terms= $this->terms;
     
         $banner = DB::table('banner')->select('*')
                      ->orderby('banner.banner_id','desc')
                      ->get();

         // For Store listing show event              

         $storeList = DB::table('store')->select('*')
                      ->where('store.store_status',1)
                      ->where('store_delete_status', 0)
                      ->orderby('store.store_id','asc');             
         // if($vCity){
         //    $storeList->where('store.city','like','%' . $vCity . '%');
         // }
         // if($vCity){
         //    $storeList->where('store.city','like','%' . $vCity . '%');
         // } 
          if($vLat){

            $storeList->whereRaw("(6371*acos( cos( radians(".$vLat.") ) * cos( radians(store.lat) ) * cos( radians(store.lng) - radians(".$vLng.") ) + sin( radians(".$vLat.") ) * sin( radians(store.lat) ) ) ) <= ".$vRange);
            //dd($catproductrels->get());
         }             
         if($pincode){
            $storeList->where('store.zip_code','=',$pincode);
         }

         $storeList=$storeList->get();


         $storeList=array(); //For hide store list by use null array 

         // End Store listing show event            

        //dd($storeList);
                      
          //added code to show category and subcategory ref api beg
              $cat = DB::table('categories')
            ->where('level', 0)
            ->where('status', 1)
            ->orderby('sort_order','desc'); //added on 16 jan 2p21 for squencing
            //search product by name beg
            if(!empty($request->productsearch)){
             $cat->where('categories.title','like','%' . $request->productsearch . '%');
            }
            //search product by name end
           $cat=$cat->get();
          //dd($cat);
      
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
                      //dd($app);
                        
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

          //dd($cat);

         
          }
        //added code for offers beg specific kepp on top
          $cat_offer = DB::table('categories')
            ->where('level', 0)
            ->where('status', 1)
            ->where('title','offers');
            //->orWhere('title','Offers')
            if(!empty($request->productsearch)){
             $cat_offer->where('categories.title','like','%' . $request->productsearch . '%');
            }
            $cat_offer=$cat_offer->get();

            
      
          if(count($cat_offer)>0) {

            $result = array();
            $i = 0;

            foreach ($cat_offer as $cats) {

                array_push($result, $cats);

                $app = json_decode($cats->cat_id);
                $apps = array($app);
                $app = DB::table('categories')
                        ->whereIn('parent', $apps)
                        ->where('level', 1)
                        ->where('status', 1)
                        ->get();
                      //dd($app);
                        
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

          

         
          }
         
        
        //added code for offers end
        #added code to show dialy fruits individually beg 18 jan
          $cat_daily_fruit = DB::table('categories')
            ->where('level', 0)
            ->where('status', 1)
            ->where('title','Daily Fruits');
            //->orWhere('title','Offers')
            if(!empty($request->productsearch)){
             $cat_daily_fruit->where('categories.title','like','%' . $request->productsearch . '%');
            }
            $cat_daily_fruit=$cat_daily_fruit->get();

            //dd($cat_daily_fruit);

            
          
          if(count($cat_daily_fruit)>0) {

            $result = array();
            $i = 0;

            foreach ($cat_daily_fruit as $cats) {

                array_push($result, $cats);

                $app = json_decode($cats->cat_id);
                $apps = array($app);
                $app = DB::table('categories')
                        ->whereIn('parent', $apps)
                        ->where('level', 1)
                        ->where('status', 1)
                        ->get();
                      //dd($app);
                        
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

          

          
          }
        #added code to show dialy fruits individually end
          #added code for daily vegies beg
          $cat_daily_Veggies = DB::table('categories')
            ->where('level', 0)
            ->where('status', 1)
            ->where('title','Daily Veggies');
            //->orWhere('title','Offers')
            if(!empty($request->productsearch)){
             $cat_daily_Veggies->where('categories.title','like','%' . $request->productsearch . '%');
            }
            $cat_daily_Veggies=$cat_daily_Veggies->get();
            //dd($cat_daily_Veggies);
            
          
          if(count($cat_daily_Veggies)>0) {

            $result = array();
            $i = 0;

            foreach ($cat_daily_Veggies as $cats) {

                array_push($result, $cats);

                $app = json_decode($cats->cat_id);
                $apps = array($app);
                $app = DB::table('categories')
                        ->whereIn('parent', $apps)
                        ->where('level', 1)
                        ->where('status', 1)
                        ->get();
                      //dd($app);
                        
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

          

          
          }
          #added code for daily vegies end
        //combo beg 
          //added code for offers beg
          $cat_combo = DB::table('categories')
            ->where('level', 0)
            ->where('status', 1)
            ->where('title','combo');
            //->orWhere('title','Offers')
           if(!empty($request->productsearch)){
             $cat_combo->where('categories.title','like','%' . $request->productsearch . '%');
            }
            $cat_combo=$cat_combo->get();
            
      
          if(count($cat_combo)>0) {

            $result = array();
            $i = 0;

            foreach ($cat_combo as $cats) {

                array_push($result, $cats);

                $app = json_decode($cats->cat_id);
                $apps = array($app);
                $app = DB::table('categories')
                        ->whereIn('parent', $apps)
                        ->where('level', 1)
                        ->where('status', 1)
                        ->get();
                      //dd($app);
                        
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

          //dd($cat_combo);

         
          }
        
        //added code for offers end
        //combo end
          //added code to get parent category beg
          $categories = DB::table('categories')
                          ->leftJoin('categories as catt', 'categories.parent', '=' , 'catt.cat_id')
                          ->select('categories.*', 'catt.title as tttt')
                          ->where('categories.status','=',1)
                          ->where('categories.parent','=',0)
                          ->where('categories.level','=',0)
                          ->get();
        
          //parent category only beg 2 nov
          $catproductrels=DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product.product_id as pid','product_varient.discount_percentage','product_varient.varient_image')
          ->join('categories','product.cat_id','=','categories.cat_id')
            ->leftJoin('categories as catt', 'categories.parent', '=' , 'catt.cat_id')
           
          ->join('product_varient','product_varient.product_id','=','product.product_id')
          ->orderby('categories.cat_id','desc')
            ->get();

          //end parent cat 2 nov
       // dd($catproductrels);
          $catarray=array();
          $catpcount=array();
          $catproductdetails=array();
          foreach($catproductrels as $catproductrel){
                  if(isset($catarray[$catproductrel->pcid])){
                  
                      $catarray[$catproductrel->pcid]=$catproductrel;
                      $catproductdetails[$catproductrel->pcid][]=$catproductrel;
                      $catpcount[$catproductrel->pcid]+=1;
                 }else{
                      //$catarray[$catproductrel->ctitle][]=$catproductrel;
                      $catarray[$catproductrel->pcid]=$catproductrel;
                      $catpcount[$catproductrel->pcid]=1;
                      $catproductdetails[$catproductrel->pcid][]=$catproductrel;
                 }

          }

          
        
          
          //best offers view beg
          
            $products=DB::table('product')->select('product.*','store.store_name','store.lat as store_lat','store.lng as store_lng','product_varient.*','store_products.stock')
           ->join('categories as cat','product.cat_id','=','cat.cat_id')
           ->join('product_varient','product_varient.product_id','=','product.product_id')
          //->orderby('product.product_id','desc');
           ->Leftjoin('store','product.product_store_id','=','store.store_id')           
           ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
           ->where('product.product_approved_status','=',1)
           ->where('store_products.stock','>',0)
           ->orderby('product_varient.discount_percentage','desc');
           //dd($vCity);
         //  if($vCity){
         //    $products->where('store.city','like','%' . $vCity . '%');
         // } 

          if($vLat){

            $products->whereRaw("(6371*acos( cos( radians(".$vLat.") ) * cos( radians(store.lat) ) * cos( radians(store.lng) - radians(".$vLng.") ) + sin( radians(".$vLat.") ) * sin( radians(store.lat) ) ) ) <= ".$vRange);
            //dd($catproductrels->get());
         }

          if($pincode){
            $products->where('store.zip_code','=',$pincode); 

          }
          

           if(!empty($request->productsearch)){
             
              $products->where('product.product_name','like','%' . $request->productsearch . '%');

          }
          $products=$products->limit(7)
          ->get();

          //When city name not get
          // if(!$vCity){
          //   $products=array();
          // }
          //dd($products);
          
          $productsvariants=DB::table('product_varient')->select('*')
          //->orderby('varient_id','desc')
          ->get();
          //best offers view end
          //
          $variantproductarary=array();
          foreach($productsvariants as $productsvariants){
               $variantproductarary[$productsvariants->product_id][]= $productsvariants;
          }

          //
          
          //added code to show product categorywise
          $category_products=DB::table('product')
          ->select('product.*','product_varient.discount_percentage','product_varient.mrp','product.product_image as pimg','product_varient.quantity','product_varient.unit','product.product_id as pid','categories.cat_id','categories.title as cattitle','product_varient.varient_id','product_varient.varient_image','product_varient.varient_image')
          ->join('product_varient','product_varient.product_id','=','product.product_id')
          
           ->join('categories','product.cat_id','=','categories.cat_id')
            
           ->where('categories.status','=',1)
            //->whereIn('categories.parent', $parent_category)
          //->distinct();
          ->groupBy('product.product_name');
         // ->limit(100); //product_name
          if(!empty($request->productsearch)){
              //dd($request->productsearch);
              $category_products->where('product.product_name','like','%' . $request->productsearch . '%');

          }

           $category_products= $category_products->orderby('product.product_id','desc')
           ->where('categories.status','=',1)
           ->get();
          $topproductarraycount=array();
          $category_productarraydetail=array();
          $catnamearray=array();
          
         
          foreach($category_products as $category_product){
                  if(isset($category_productarraydetail[$category_product->cattitle])){
                
                 $category_productarraydetail[$category_product->cattitle][]= $category_product;
                 $catnamearray[$category_product->cattitle]= $category_product->cattitle;
                
             }else{
             
               $category_productarraydetail[$category_product->cattitle][]= $category_product;
                $catnamearray[$category_product->cattitle]= $category_product->cattitle;
            
             }
          }
         
         
          // return view($this->viewPath.'index',compact('banner','categories','products','catarray','catpcount','catproductdetails','topproductarraycount','category_productarraydetail','catnamearray','variantproductarary','cat','cat_crousel','cat_combo','cat_offer','coupondata','terms')); //old blade is index.blade.php
          //cat_daily_fruit,cat_daily_Veggies

          return view($this->viewPath.'index',compact('banner','categories','storeList','products','catarray','catpcount','catproductdetails','topproductarraycount','category_productarraydetail','catnamearray','variantproductarary','cat','cat_crousel','cat_combo','cat_offer','coupondata','terms', 'productsvariants','catproductrels','productarray','catproductarrays','cat_daily_fruit','cat_daily_Veggies'));
      } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    //added code to show category product according to category beg 
    public function categoryproduct(Request $request){
      // $var=DB::table('');
        $banner = DB::table('banner')->select('*')
                    ->orderby('banner.banner_id','desc')
                    ->get();

        
        // $categories=DB::table('categories')->select('*')
        // ->orderby('cat_id','desc')
        // ->where('status','=',1)
        //  ->where('level', 0)
        //  ->where('parent','=',0)
        //  ->orderby('cat_id','desc')
        // ->get();
        //added code for categories ref admin code
        $categories = DB::table('categories')
                        ->leftJoin('categories as catt', 'categories.parent', '=' , 'catt.cat_id')
                        ->select('categories.*', 'catt.title as tttt')
                        ->where('categories.status','=',1)
                        ->get();
        //dd($categories);
        //added code to get parent category beg
            $parent_category=array();
            foreach($categories as $cat){
                $parent_category[]=$cat->cat_id;
            }
          
         $catproductrels=DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product.product_id as pid','product_varient.discount_percentage','product_varient.varient_image')
        ->join('categories','product.cat_id','=','categories.cat_id')
          ->leftJoin('categories as catt', 'categories.parent', '=' , 'catt.cat_id')
         
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        ->orderby('categories.cat_id','desc')
        ->get();
      //dd($catproductrels);
        $catarray=array();
        $catpcount=array();
        $catproductdetails=array();
        foreach($catproductrels as $catproductrel){
                if(isset($catarray[$catproductrel->pcid])){
                
                    $catarray[$catproductrel->pcid]=$catproductrel;
                    $catproductdetails[$catproductrel->pcid][]=$catproductrel;
                    $catpcount[$catproductrel->pcid]+=1;
               }else{
                    //$catarray[$catproductrel->ctitle][]=$catproductrel;
                    $catarray[$catproductrel->pcid]=$catproductrel;
                    $catpcount[$catproductrel->pcid]=1;
                    $catproductdetails[$catproductrel->pcid][]=$catproductrel;
               }

        }
      
        
        //best offers view beg
        $products=DB::table('product')->select('product.*','product_varient.*')
         ->join('product_varient','product_varient.product_id','=','product.product_id')
        //->orderby('product.product_id','desc');
         ->orderby('product_varient.discount_percentage','desc');
         if(!empty($request->productsearch)){
            //dd($request->productsearch);
            $products->where('product.product_name','like','%' . $request->productsearch . '%');

        }
        $products=$products->limit(7)
        ->get();
       // dd($products);
        $productsvariants=DB::table('product_varient')->select('*')
        //->orderby('varient_id','desc')
        ->get();
        //best offers view end
        //
        $variantproductarary=array();
        foreach($productsvariants as $productsvariants){
             $variantproductarary[$productsvariants->product_id][]= $productsvariants;
        }

        //
        
        //added code to show product categorywise
        $category_products=DB::table('product')
        ->select('product.*','product_varient.discount_percentage','product_varient.mrp','product.product_image as pimg','product_varient.quantity','product_varient.unit','product.product_id as pid','categories.cat_id','categories.title as cattitle','product_varient.varient_id','product_varient.varient_image','product_varient.varient_image')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        
         ->join('categories','product.cat_id','=','categories.cat_id')
          
         ->where('categories.status','=',1)
          ->whereIn('categories.parent', $parent_category)
        //->distinct();
        ->groupBy('product.product_name');
       // ->limit(100); //product_name
        if(!empty($request->productsearch)){
            //dd($request->productsearch);
            $category_products->where('product.product_name','like','%' . $request->productsearch . '%');

        }

         $category_products= $category_products->orderby('product.product_id','desc')
         ->where('categories.status','=',1)
         ->get();
         //dd($category_products); 
       // dd($topsellingproducts);
        $topproductarraycount=array();
        $category_productarraydetail=array();
        $catnamearray=array();
        
       
        foreach($category_products as $category_product){
                if(isset($category_productarraydetail[$category_product->cattitle])){
              
               $category_productarraydetail[$category_product->cattitle][]= $category_product;
               $catnamearray[$category_product->cattitle]= $category_product->cattitle;
              
           }else{
           
             $category_productarraydetail[$category_product->cattitle][]= $category_product;
              $catnamearray[$category_product->cattitle]= $category_product->cattitle;
          
           }
        }
       
       
        //dd($this->viewPath.'index');topproductarraydetail
        return view($this->viewPath.'index',compact('banner','categories','products','catarray','catpcount','catproductdetails','topproductarraycount','category_productarraydetail','catnamearray','variantproductarary'));
    }
    //added code to show category product according to category end
}
