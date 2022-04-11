<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use DB;

use Session;
use App\Order;
use Mail;
use Razorpay\Api\Api;
//use Session;
use Redirect;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductController extends Controller
{
    private $razorpayid="rzp_test_cn7PNije60Rutl";
    private $razorpaykey="r05UHvc4aYYIGrlyduGijDIG";
    public $categories;
    public $catpcount;
    public $catarray;
    public $products;
    public $catproductrels;
    public $catproductdetails;
    public $cat_crousel;
    public $coupondata;
    public $terms;
    public $cat;

    public function __construct(){

      #added code for cat var beg
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

             // dd($cat);

            
            }
      #added code for cat var end
       #terms to show in popup beg
      $terms=DB::table('termspage')->first();

      #terms end
       #coupon data beg
      $coupondata=DB::table('coupon')->first();

      #coupon data end
        #added code for carousel beg
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
      $this->cat_crousel=$cat_crousel;
      $this->coupondata=$coupondata;
      $this->terms=$terms;
      $this->cat=$cat;
        #added code for carousel end
        //best offer view beg
         $products=DB::table('product')->select('product.*','product_varient.*')
         ->join('product_varient','product_varient.product_id','=','product.product_id')
         ->where('product.product_approved_status','=',1)
       // ->orderby('product.product_id','desc')
        ->orderby('product_varient.discount_percentage','desc')
        //->limit(7)
        ->get();
        //best offer view end
        $categories=DB::table('categories')->select('*')
        ->where('status',1)
        ->where('parent','=',0)
        ->orderby('cat_id','desc')
        ->get();
       // ->paginate(3);
        $catproductrels=DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product_varient.*')
        //->distinct()
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->Leftjoin('product_varient','product_varient.product_id','=','product.product_id')
         ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
         ->where('product.product_approved_status','=',1)
        ->orderby('categories.cat_id','desc')
        
        ->get();
        //->paginate(6);
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
       $this->catarray=$catarray;
       $this->categories=$categories;
       $this->catpcount=$catpcount;
       $this->products=$products;
       $this->catproductrels=$catproductrels;
       $this->catproductdetails=$catproductdetails;
       
    }

    # Bind the website path.
    protected $viewPath = 'website.products.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id='')
    {

      $pincode = Session::get('visitor_pincode'); //get pincode session
      $vCity = Session::get('visitor_city');  //get city session
      $vLat = Session::get('visitor_lat'); //get latitude session
      $vLng = Session::get('visitor_lng');  //get longitude session
      
      if(Session::get('visitor_range')){
        $vRange = Session::get('visitor_range');  //get longitude session
      }else{
        $vRange = Session::get('visitor_range');  //get longitude session
      }

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
       $coupondata= $this->coupondata;
       $cat_crousel= $this->cat_crousel;
       $catarray= $this->catarray;
       $categories= $this->categories;
       $catpcount= $this->catpcount;
       $products= $this->products;
       $catproductrels= $this->catproductrels;
       $catproductdetails= $this->catproductdetails;
       $terms= $this->terms;
       $cat= $this->cat;
       //
        $products=DB::table('product')->select('product.*','product_varient.*','store_products.stock','store.store_name','store.city as store_city','store.zip_code as store_zip_code','store.lat as store_lat','store.lng as store_lng')
         ->join('product_varient','product_varient.product_id','=','product.product_id')
         ->Leftjoin('store','product.product_store_id','=','store.store_id') 
       // ->orderby('product.product_id','desc')
       ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
       ->where('product.product_approved_status','=',1)
       ->where('store_products.stock','>',0)
        ->orderby('product_varient.discount_percentage','desc')
        ->limit(7)
        ->get();
       // dd($products);
       
        //added code for categories beg 2 nov beg
        $categories = DB::table('categories')
                        ->leftJoin('categories as catt', 'categories.parent', '=' , 'catt.cat_id')
                        ->select('categories.*', 'catt.title as tttt')
                        ->where('categories.status','=',1)
                        ->where('categories.parent','=',0)
                        ->where('categories.level','=',0)
                        ->get();
        
        //added code for categories beg 2 nov end
       #to get catname n catid on the basis of id,category set in url beg
        $category_id_and_name=DB::table('categories')
                              
                              ->where('status',1);
       $store_id_and_name=DB::table('store')
                              
                              ->where('store_status',1);                        
       #to get catname n catid on the basis of id,category set in url end
       

         $catproductrels=DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product_varient.*','store_products.stock','store.store_name','store.city as store_city','store.lat as store_lat','store.lng as store_lng','store.zip_code as store_zip_code')      
         ->distinct()
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->Leftjoin('store','product.product_store_id','=','store.store_id')       
        ->Leftjoin('product_varient','product_varient.product_id','=','product.product_id')
        ->Leftjoin('product_synonym_names','product.product_id','=','product_synonym_names.product_id')
        
        #added for synonyms for product search end 11 nov
        #for check of stock
         ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')

         ->where('product.product_approved_status','=',1);

          //dd($vRange);
          //dd($catproductrels->get());
       if($vLat){

            $catproductrels->whereRaw("(6371*acos( cos( radians(".$vLat.") ) * cos( radians(store.lat) ) * cos( radians(store.lng) - radians(".$vLng.") ) + sin( radians(".$vLat.") ) * sin( radians(store.lat) ) ) ) <= ".$vRange);
            //dd($catproductrels->get());
         }     

        if(!empty($request->sortorder) && $request->sortorder == 'lh'){  
           // dd($request->price_ltoh);
        $catproductrels->orderby('product_varient.price','asc');
    }
       if(!empty($request->sortorder) && $request->sortorder == 'hl'){ 
            //dd('here');
            $catproductrels->orderby('product_varient.price','desc');
        }
        if(!empty($request->sortorder) && $request->sortorder == 'discounthl'){
            //dd('here');
            $catproductrels->orderby('product_varient.discount_percentage','desc');
        }
        if(!empty($request->sortorder) && $request->sortorder == 'atoz'){
            //dd('here');
            $catproductrels->orderby('product.product_name','asc');
        }

        $categoryFilterValue = '';
        if(!empty($request->catsearch)){
           $categoryFilterValue =$request->catsearch;

             $category_id_and_name->where('categories.title','like', '%' . $request->catsearch . '%');
          
              $catproductrels->where('categories.title','like', '%' . $request->catsearch . '%');
               

            }
            #best offer filter beg
           /* if(!empty($id) && $id =='best-offer'){
          
              $catproductrels->orderby('discount_percentage','desc');
               

            }*/

            
            #best offer filter end
       
        //productsearch top beg
        $searchByName='';    
        if(!empty($request->productsearch)){
          //dd($request->productsearch);
          $searchByName=$request->productsearch;
          $catproductrels->where('product.product_name','like', '%' . $request->productsearch . '%')
          ->orWhere('product_synonym_names.name', 'LIKE', '%'.$request->productsearch.'%');
           

        }
        //dd($searchByName);

        //productsearch top end
        #bottom n top range for price filter default value
        $top_range= '';
        $bottom_range='';
        $pricecheck='';

        if(!empty($request->pricerange) && $request->pricerange == 1){
       
          $catproductrels->where('product_varient.mrp','>=', 68);
          $catproductrels->where('product_varient.mrp','<=', 659);
         }

          if(!empty($request->pricerange) && $request->pricerange == 2){
       
          $catproductrels->where('product_varient.mrp','>=', 660);
          $catproductrels->where('product_varient.mrp','<=', 1014);
         }

          if(!empty($request->pricerange) && $request->pricerange == 3){
       
          $catproductrels->where('product_varient.mrp','>=', 1015);
          $catproductrels->where('product_varient.mrp','<=', 1679);
         }

          if(!empty($request->pricerange) && $request->pricerange == 4){
       
          $catproductrels->where('product_varient.mrp','>=', 1680);
          $catproductrels->where('product_varient.mrp','<=', 1856);
         }


        $checked_id='';
         //added code for catcheck beg
        if(!empty($request->category)){
          $category_id_and_name->where('categories.cat_id','=',$request->category);
        //  $checked_id=$request->catcheck;
        //dd($request->catcheck);
         // $catproductrels->where('product.cat_id','=', $request->catcheck);
         // $catproductrels->where('product.cat_id','=',$request->catcheck); //previous
         // $catproductrels->where('categories.parent','=',$request->category);
          $catproductrels->where('categories.cat_id','=',$request->category)
                          ->orWhere('categories.parent','=',$request->category); 
          //$catproductrels->where('categories.cat_id',$request->catcheck);
           

        }

        //pincode use during all only main category select
        // if($vCity){
        //    //dd($catproductrels->get());
        //     $catproductrels->where('store.city','like','%'.$vCity.'%');
             

        //  }
         if($vLat){

            $catproductrels->whereRaw("(6371*acos( cos( radians(".$vLat.") ) * cos( radians(store.lat) ) * cos( radians(store.lng) - radians(".$vLng.") ) + sin( radians(".$vLat.") ) * sin( radians(store.lat) ) ) ) <= ".$vRange);
            //dd($catproductrels->get());
         }
        if($pincode){
         
          //dd($pincode);
           // /dd($catproductrels->get());
            $catproductrels->where('store.zip_code','=',$pincode); 
             //dd($catproductrels->get());
          }
        //category filter for carousel beg
       /* if(!empty($id) && $id > 0){

             $catproductrels
             ->where('categories.cat_id','=',$id);
           
             
        }*/
        if(!empty($request->id)){
            //dd($request->id);

            $category_id_and_name->where('categories.cat_id','=',$request->id);
            //dd($category_id_and_name);
                                // ->orWhere('categories.parent','=',$request->id);
           
             $catproductrels
             ->where('categories.cat_id','=',$request->id)
             ->orWhere('categories.parent','=',$request->id); //uncommented by me  on  17dec 2020
            
             
        }

        if(!empty($request->storeId)){
         // dd('ghg');

            $category_id_and_name->where('categories.cat_id','=',$request->id);
                                // ->orWhere('categories.parent','=',$request->id);
            $store_id_and_name->where('store.store_id','=',$request->storeId);
           
             $catproductrels
             ->where('product.product_store_id','=',$request->storeId);
             //->orWhere('categories.parent','=',$request->id); //uncommented by me  on  17dec 2020   
        }

         if(!empty($request->id) && $request->id =='best-offer'){
          //dd($request->id);

              $catproductrels->orderby('product_varient.discount_percentage','desc');               

            }

          
          //pincode use during all except main category select 
         //dd($catproductrels->get());   
         //  if($vCity){
         //    $catproductrels->where('store.city','like','%'.$vCity.'%');
         // }
         if($vLat){
            $catproductrels->whereRaw("(6371*acos( cos( radians(".$vLat.") ) * cos( radians(store.lat) ) * cos( radians(store.lng) - radians(".$vLng.") ) + sin( radians(".$vLat.") ) * sin( radians(store.lat) ) ) ) <= ".$vRange);
         }  
          if($pincode){

            $catproductrels->where('store.zip_code','=',$pincode); 
          }
          //dd($catproductrels->get());       

        $category_id_and_name=$category_id_and_name->first();
        //category filter for carousel end
        //added code for catcheck end
        //dd($category_id_and_name);
       /// $catproductrels=$catproductrels ->orderby('categories.cat_id','desc')//bottom_range
      //  $catproductrels=$catproductrels->get(); //old original
        $catproductrels=$catproductrels->paginate(9);
       // dd($catproductrels);
        $catarray=array();
        $catpcount=array();
        $catnamearray=array();
        $catproductdetails=array();
        $productarray=array(); //for showing distinct product of only index 0
        foreach($catproductrels as $catproductrel){
                if(isset($catarray[$catproductrel->pcid])){
                
                    $catarray[$catproductrel->pcid]=$catproductrel;
                    $catproductdetails[$catproductrel->pcid][]=$catproductrel;
                    $productarray[$catproductrel->product_id][]=$catproductrel;
                    $catpcount[$catproductrel->pcid]+=1;
               }else{
                    //$catarray[$catproductrel->ctitle][]=$catproductrel;
                    $catarray[$catproductrel->pcid]=$catproductrel;
                    $catpcount[$catproductrel->pcid]=1;
                    $catproductdetails[$catproductrel->pcid][]=$catproductrel;
                    $catnamearray[$catproductrel->pcid]=$catproductrel->ctitle;
                      $productarray[$catproductrel->product_id][]=$catproductrel;
               }

        }

        //When city name not get

        // if(!$vCity){
        //     $productarray=array();
        //  }

       //////////////////////////
        //dd($catproductrels);
       // dd($productarray);
        # return the product page of website

        return view($this->viewPath.'index',compact('catarray','categories','catpcount','products','catproductrels','catproductdetails','catnamearray','cat_crousel','id','checked_id','coupondata','terms','top_range','bottom_range','pricecheck','variantproductarary','category_id_and_name','productarray','cat','searchByName','categoryFilterValue'));
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

    /**
     * Display specific product information
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productDetail(Request $request,$id)
    {

      // dd($request);
        //dd('jkjkg');
      $cat= $this->cat;
        $cat_crousel=$this->cat_crousel;
        $terms=$this->terms;
        $coupondata=$this->coupondata;
        $categories=DB::table('categories')->select('*')
        ->orderby('cat_id','desc')
        ->get();
         $catproductrels_single=DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product_varient.*','store_products.stock','store.store_name','store.city as store_city','store.lat as store_lat','store.lng as store_lng')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        ->Leftjoin('store','product.product_store_id','=','store.store_id')
          ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id') //to check stock
        ->where('product.product_id',$id)
        //->where('product_varient.varient_id',$id)
        ->first();
        //dd($catproductrels_single);
        $catproductrels=DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product_varient.*')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        ->orderby('categories.cat_id','desc')  
        ->get();
        
        // $catproductrels=DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid')
        // ->join('categories','product.cat_id','=','categories.cat_id')
        // ->orderby('categories.cat_id','desc')
        // ->get();
        //dd($catproductrel);
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
        # return the product-detail page of website

        return view($this->viewPath.'single-product',compact('catarray','categories','catpcount','catproductrels_single','cat_crousel','coupondata','terms','cat'));
    }
    //added to cart beg
public function addtocart(Request $request,$id,$type=""){

  //Remove coupon session during add
  Session::forget('coupon_code');
  Session::forget('coupon_amount'); 

  # new added code to get stock of varient id which is added beg
  $productstock=DB::table('store_products')
               ->where('varient_id',$id)
               ->first();
  #added code to get productid on the belhaf of variant id in productvariant table first beg
  $oldproductid=DB::table('product_varient')
                    ->where('varient_id',$id)
                    ->first();
  #added code to get productid on the belhaf of variant id in productvariant table first end  
  # new added code to get stock of varient id which is added end

  $cartsubtotal=0;
  $bulkOrderDiscount=0;
  $cartMrpsubtotal=0;
  if(!empty($id)){
    $pid=$id;  
    $pqty=1;
    $cartpids = Session::get('cartpids'); //get  session
 

    $tempids=array();
    if($cartpids){
      $newid=true;
      foreach($cartpids['pid'] as $k=>$v){
        if($pid==$k){
            $tempids['pid'][$k]=$v+$pqty;
            $newid=false;
        }else{
            $tempids['pid'][$k]=$v;
        }
      }
      if($newid)$tempids['pid'][$pid]=1;
    }else{
      $tempids['pid'][$pid]=1; //1st else condition bcoz session not set 1st time
    }
    //added code for type beg mange beg
    if(!empty($type) && $type == 'dec'){
      foreach($cartpids['pid'] as $k=>$v){
        if($pid==$k && $v > 1){
            $tempids['pid'][$k]=$v-1;
        }else{
            $tempids['pid'][$k]=$v;
        }
      }

    }
    //added code for type beg mange end

      Session::put('cartpids', $tempids); //session put
     $cartpids = Session::get('cartpids'); //get  session again updated
    // dd($cartpids);

    //added code 13 oct total item beg 
    $ids=array();
    $qty=array();
    $totalcartqty=0; //to show cart total qty on top
    //$totalcartitem=0; //to show cart total qty on top
    $products=array();

    $carthtml='';
    $checkStore=array();
    $checka1='';
    $checka2=0;
    $secondCount = 0;


    $carthtml='';
    if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
      foreach ($cartpids['pid'] as $k=>$v){
         $ids[$k]=$k;
         $qty[$k]=$v;
         $totalcartqty+=$v;
      }
      if(is_array($ids) && count($ids)>0){
       
        $products= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','store.store_id','store.store_name','product.cat_id as pcid','product_varient.*','product.product_id as pid','store_products.stock')  
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        #added code to get stock of each items added in cart beg
        ->Leftjoin('store','product.product_store_id','=','store.store_id')
         ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
        #added code to get stock of each items added in cart end
        ->whereIn('product_varient.varient_id',$ids)->get();

        #added code to get data of product which is addtocart individually beg
        $products_individual= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product_varient.*','product.product_id as pid','store_products.stock')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
        ->where('product_varient.varient_id',$id)->first();
       #added code to get data of product which is addtocart individually end
        
         $productcount=DB::table('product_varient')->select('*')->whereIn('varient_id',$ids)->count(); //added for count
       
      }
      #added data for individual product beg
      $price_after_discount_individual=$products_individual->price;
    
      #added data for individual product end
      //added code for caerthtml beg
    

      foreach($products as $product) {
        $price_after_discount=$product->price;
        if($product->mrp > 0  && $product->price >0) {
          $discountdata=(($product->mrp - $product->price)/$product->mrp) * 100;
        }else{
          $discountdata=0;
        }

        $cartsubtotal+=$price_after_discount *  $qty[$product->varient_id];
        $cartMrpsubtotal+=$product->mrp *  $qty[$product->varient_id];
        $product->totalPrice = $price_after_discount *  $qty[$product->varient_id];
        $product->totalMrp = $product->mrp *  $qty[$product->varient_id];
        //added code for img url beg
        if(!empty($product->varient_image)){
          $pimgurl=url('admin-panel/'.$product->varient_image);
        }else{
         $pimgurl= url('admin-panel/'.$product->product_image);
        }
        $dec='dec';
        $inc='inc';
        //added code for img url end
        //added code to show discount and regular price if discount is greater than zero beg
        $discount=round($discountdata,2).'% OFF';
        $regularprice='₹'.round($product->mrp,2);
        if($discountdata <= 0){
          $discount='';
          $regularprice='';
        }
        #added code to append qtyinc dec on product beg
        $qtyhtmlindividual='
        <p class="offer-price mb-0 quantity" style="float:right;">
        <span class="input-group-btn"><button disabled="disabled" class="btn btn-theme-round btn-number" type="button" style="font-size: 30px;"><span onclick="cartupdate('."'".$products_individual->varient_id."'".','."'".$dec."'".')" id="'.$dec.$products_individual->varient_id.'" class="'.$dec.$products_individual->varient_id.'" style="font-size:28px">x</span></button></span><span><input type="text" max="" min="" value="'.$qty[$products_individual->varient_id].'"  name="quant[1]" class="quant'.$products_individual->varient_id.'" style="width:30px;" readonly></span><span class="input-group-btn"><button class="btn btn-theme-round btn-number" type="button" style="font-size: 30px;"><span onclick="cartupdate('."'".$products_individual->varient_id."'".','."'".$inc."'".')" id="'.$inc.$products_individual->varient_id.'" class="'.$inc.$products_individual->varient_id.'">+</span></button></span>
        </p>';

        #added code to append qtyinc dec on product end
        #added code to show discount and regular price if discount is greater than zero end
        $carthtml.='
        <input type="hidden" name="stockvalue" value="'.$product->stock.'" id="stockvalue'.$product->varient_id.'">
        <input type="hidden" value="'.$qty[$product->varient_id].'" id="popup_cart_qty'.$product->varient_id.'"><div class="cart-list-product extrajs" id="singleitem'.$product->varient_id.'"> <a class="float-right remove-cart" href="#"><i class="mdi mdi-close" onclick="removeproduct('."'".$product->varient_id."'".')"></i></a><img class="img-fluid" src="'. $pimgurl.'" alt=""><span class="badge badge-success">'.$discount.'</span><h5><a href="#">'.$product->product_name.'</a></h5><h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - '.$product->quantity.' '.$product->unit.'</h6>
        <p class="offer-price mb-0">₹<span id="priceafterdis'.$product->varient_id.'">'.$price_after_discount.'</span><i class="mdi mdi-tag-outline"></i> <span class="regular-price">'.$regularprice.'</span></p>
        <p calss="price_qty_tab"><span class="price_dis">₹'.$price_after_discount.'X</span><span class="quant_single'.$product->varient_id.'">'.$qty[$product->varient_id].' </span><span>=₹</span><span class="single_product_total'.$product->varient_id.'">'.$price_after_discount * $qty[$product->varient_id] .'</span></p>
        <p class="offer-price mb-0 quantity">
        <span class="input-group-btn"><button disabled="disabled" class="btn btn-theme-round btn-number" type="button" style="font-size: 30px;"><span onclick="cartupdate('."'".$product->varient_id."'".','."'".$dec."'".')" id="'.$dec.$product->varient_id.'" class="'.$dec.$product->varient_id.'" style="font-size:24px">x</span></button></span>
        <span><input type="text" max="" min="" value="'.$qty[$product->varient_id].'"  name="quant[1]" class="quant'.$product->varient_id.'" style="width:30px;" readonly></span>
        <span class="input-group-btn"><button class="btn btn-theme-round btn-number" type="button" style="font-size: 30px;"><span onclick="cartupdate('."'".$product->varient_id."'".','."'".$inc."'".')" id="'.$inc.$product->varient_id.'" class="'.$inc.$product->varient_id.'">+</span></button>

        </span>
        </p>
        </div>';
      }

      //for check store base calculation
      foreach($products as $key => $value){

        $checka1 = $products[$key]->product_store_id;
        if($checka2!=$checka1){
          $checkStore[] = $products[$key];
          $checka2=$checka1;
          $secondCount++;

        }else{
          $checkStore[$secondCount-1]->totalPrice +=   $products[$key]->totalPrice;
          $checkStore[$secondCount-1]->totalMrp +=   $products[$key]->totalMrp;
        }
      }
      //added code for caerthtml end
    }
     
    $newCharge=0;
    foreach($checkStore as $checkStores)
     {
        #new code to  delivery charge purchase from individual store beg
        $newDelcharge=DB::table('freedeliverycart_by_store')
            ->where('delivery_store_id',$checkStores->product_store_id) 
            ->where('min_cart_value','>=',$checkStores->totalPrice)
            ->first();
      
        if($newDelcharge){
          $kk = $newDelcharge->del_charge;
        }else{
          $kk = 0;
        }   
        $newCharge+= $kk;

        //Get count for bulk condition check
        //$checkStores->totalPrice=12000;

        $newBulkDiscount=DB::table('bulk_order_discount')
            //->where('bulk_order_store_id',$checkStores->product_store_id) 
            ->where('bulk_order_min_amount','<=',$checkStores->totalPrice)
            ->where('bulk_order_max_amount','>=',$checkStores->totalPrice)
            ->first(); 
      
        if($newBulkDiscount){
          if($newBulkDiscount->bulk_order_discount_type=='percentage'){
            $bb = $checkStores->totalPrice*$newBulkDiscount->bulk_order_discount/100;  
          }else{
            $bb =$newBulkDiscount->bulk_order_discount;
          }  
        }else{
          $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
          $maxBDiscountData = DB::table('bulk_order_discount')
                         ->where('bulk_order_max_amount',$maxValueAmountLimit)
                         ->first();
          if($checkStores->totalPrice>$maxValueAmountLimit){
            if($maxBDiscountData->bulk_order_discount_type=='percentage'){
              $bb = $checkStores->totalPrice*$maxBDiscountData->bulk_order_discount/100;  
            }else{
              $bb =$maxBDiscountData->bulk_order_discount;
            }
          }else{
            $bb = 0;
          }
        }

        $bulkOrderDiscount+= $bb;

     }
     //dd($bulkOrderDiscount);
    //dd($newCharge);

 

 // $delcharge=DB::table('freedeliverycart')
 //            ->where('min_cart_value','<=',$cartsubtotal)
 //            ->orderby('id', 'desc')
 //            ->first();
 #new code to get delivery charge end

 
 #added code to check wether delivery charge will be applied or not beg
 // $charge=0;
 // if(isset($delcharge)){
 //  $charge=$delcharge->del_charge;
 // }
 
 #added code to check wether delivery charge will be applied or not end 
 $cartsubtotal=round($cartsubtotal,2);
 $bulkOrderDiscount=round($bulkOrderDiscount,2);
 
 Session::put('footer_cart_qty', $qty); //qt also need in footer popup
 Session::put('delivercharge', $newCharge); // session delivercharge
 Session::put('bulkOrderDiscount', $bulkOrderDiscount);
 $delivercharge=Session::get('delivercharge'); 
 Session::put('totalcartqty', $productcount);
 $totalitem=Session::get('totalcartqty');
 //seesion mein productlist ko dalo beg becoz we need it in popup unless cart is not empty beg
 Session::put('cart_product_list', $products);
 $cart_product_list=Session::get('cart_product_list'); 
 ////////////////////////////////
 #in session put stock of varient
 Session::put('productstock', $productstock->stock);
 Session::get('productstock');
 

/*return response()->json(['totalitem'=>$totalitem,'products'=>$products,'carthtml'=> $carthtml,'cartsubtotal'=>$cartsubtotal,'delivercharge'=>$charge,'type'=>$type,'stock'=>$product->stock,'cartqty'=>$qty[$product->varient_id],'productstock'=>$productstock->stock,'productid'=>$id,'oldproductid'=>$oldproductid->product_id]);*/ //old $cartpids['pid'][$id]
return response()->json(['totalitem'=>$totalitem,'products'=>$products,'checkStoreArray'=>$checkStore,'carthtml'=> $carthtml,'cartsubtotal'=>$cartsubtotal,'cartMrpsubtotal'=>$cartMrpsubtotal,'delivercharge'=>$newCharge,'type'=>$type,'stock'=>$product->stock,'cartqty'=>$cartpids['pid'][$id],'productstock'=>$productstock->stock,'productid'=>$id,'oldproductid'=>$oldproductid->product_id,'qtyhtmlindividual'=>$qtyhtmlindividual,'bulkOrderDiscount'=>$bulkOrderDiscount]);
 }else{
return response()->json('0');
 }
}
//added code for add to cart end

//added to cart end
//added code 20 oct for checkout popup and checkout page form submit beg
//added code for checkout beg
public function checkout(Request $request){
  //dd($request->all());

$products=''; //default
$checkStore='';
$bulkOrderDiscount=0;
$cat= $this->cat;
$todayDate = date("Y-m-d");


  //get address of user if session beg
   $getaddress_detail='';
   $get_city=DB::table('city')->get();

   //get delivery type
   $delivery_type=DB::table('delivery_type')
                  ->where('status',1)
                  ->get();
   //get coupon               
  $coupons=DB::table('coupon')
                //->whereDate('coupon.start_date', '<=', date('Y-m-d H:i:s'))
                ->whereDate('coupon.end_date', '>=', date('Y-m-d'))
                ->get();               
   //Store detail
   $store_detail=DB::table('store')
                           //->where('user_id','=',$user_session_data->user_id)
                           ->first();                                       

  if(session()->has('userData')){
    $user_session_data=session()->get('userData');
    //dd($user_session_data->user_id);user_id
    $getaddress_detail=DB::table('address')
                           ->where('user_id','=',$user_session_data->user_id)
                           ->first();   
  }
  //get address of user if session end

  //added code to show  time slot beg 6 nov 2020
        $current_time   = Carbon::Now();
        $date           = date('Y-m-d');
        $time_slot      = DB::table('time_slot')
                            ->first();
       
        //added code to get time slot beg
      $StartTime=$time_slot->open_hour;
      $EndTime=$time_slot->close_hour;
      $Duration=$time_slot->time_slot;
      $ReturnArray = array ();// Define output
      $StartTime    = strtotime ($StartTime); //Get Timestamp
      $EndTime      = strtotime ($EndTime); //Get Timestamp

      $AddMins  = $Duration * 60;

    while ($StartTime <= $EndTime) //Run loop
    {
        $ReturnArray[] = date ("G:i", $StartTime);
        $StartTime += $AddMins; //Endtime check
    }
   
  //added code to get time slot end
  //added code to show  time slot end 6 nov 2020
    
    $cat_crousel= $this->cat_crousel;
    $terms= $this->terms;
   
    $cartsubtotal=0;
    $cartMRPsubtotal=0;
    $productcount=0;
    $cartpids = Session::get('cartpids'); 

     $ordertotal=0;
     $ids=array();
     $qty=array();
     if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
     foreach ($cartpids['pid'] as $k=>$v){
         $ids[$k]=$k;
         $qty[$k]=$v;
     }
    }

     if(is_array($ids) && count($ids)>0){
       
          $products= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','store.store_name','product.cat_id as pcid','product_varient.*','product.product_id as pid','store_products.stock')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        #added code to get stock of each items added in cart beg
        ->Leftjoin('store','product.product_store_id','=','store.store_id')
         ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
        #added code to get stock of each items added in cart end
         ->whereIn('product_varient.varient_id',$ids)->get();
       
         $productcount=DB::table('product_varient')->select('*')->whereIn('varient_id',$ids)->count();
         
         //manipulate order total beg
         foreach($products as $product){
            //$ordertotal+=$product->pprice * $qty[$product->id];
            $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
            $discountprice=$product->mrp*$product->discount_percentage/100;
            //$cartsubtotal+=$price_after_discount *  $qty[$product->varient_id]; 
            $cartsubtotal+=$product->price *  $qty[$product->varient_id];
            $cartMRPsubtotal+=$product->mrp *  $qty[$product->varient_id];
            
         }
        
        
     }
     //dd($products);
     
 //form submit beg cod
    $orderno='';
    if(isset($request->paymenttype) && $request->paymenttype == 0 && is_array($ids) && count($ids)>0){


     
      
         $cod=$request->paymenttype;

        //added code to insert customer detail in user table beg
         //user check beg
         $customercheck=DB::table('users')->select('user_email','user_id')
                       ->where('user_email',$request->uemail)
                       ->orWhere('user_phone',$request->uphone)
                       ->first();
          
         //user check end
         if(empty($customercheck)){
        $customerdetails=DB::table('users')
                        ->insertGetId(['user_name'=>$request->ufname.' '.$request->ulname,'user_phone'=>$request->uphone,'user_email'=>$request->uemail]);
              }else{
             $customerdetails= $customercheck->user_id;
          }
       //added code to generate cart id beg
       $chars =  "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $val              =  "";

        # random character get for make cart id.
        for ($i = 0; $i < 4; $i++) {
          $val .= $chars[mt_rand(0, strlen($chars)-1)];
        }

        $chars2 = "0123456789";
        $val2 = "";
        # random number get for make cart id. there are 2 digit numbers.
        for ($i = 0; $i < 2; $i++){
          $val2 .= $chars2[mt_rand(0, strlen($chars2)-1)];
        }

        # get microtime.
        $cr      = substr(md5(microtime()),rand(0, 26),2);

        # make cart id with character, number and time strings.
        $cart_id = $val.$val2.$cr;

           $coupon_code='';
            $coupon_amount=0;
            $coupon_store_id='';
            # if coupon applied beg
           if(session()->has('coupon_code') && session()->has('coupon_amount')){
            $coupon_code=Session::get('coupon_code');
            $coupon_amount=Session::get('coupon_amount');
          }

          if($coupon_amount>0){
            $coupon_details=DB::table('coupon')
                  ->where(DB::raw("BINARY `coupon_code`"),$coupon_code)
                  ->first();
            $coupon_store_id =$coupon_details->coupon_store_id;
          }

          $ordertotal=$request->ordertotal + $request->delcharge-$request->bulkOrderDiscount;

         // dd($ordertotal);
            # if coupon applied beg
          #added code to get rewars point update for users on the basic of cart value beg
          #first get user previous reward value beg
               $previous_reward=DB::table('users')
              ->where('user_id','=',$customerdetails)
              ->first();
              //dd($previous_reward);
          #first get user previous reward value end
          $select_reward=DB::table('reward_points')
                     ->where('min_cart_value','<=', $ordertotal)
                     ->first();

          if(isset($select_reward)){
            #insert reward points in cart_rewards beg
          $insertrewad=DB::table('cart_rewards')
                       ->insert(['cart_id'=>$cart_id,'rewards'=>$select_reward->reward_point ?? 0,'user_id'=>$customerdetails]);
          #insert reward points in cart_rewards end
            #user rewards on cart min value
            $total_reward_after=$previous_reward->rewards + $select_reward->reward_point;
            $update_user_reward=DB::table('users')
                               ->where('user_id','=',$customerdetails)
                               ->update(['rewards'=>$total_reward_after]);
          }

          #added code to get rewars point update for users on the basic of cart value end
       //added code to generate cart id end
          #get address beg
          //dd($customerdetails);
            $ar      = DB::table('address')
                                  ->select('society','landmark','city','lat','lng','address_id')
                                  ->where('user_id', $customerdetails)
                                  //->where('select_status', 1)
                                  ->first();
               // dd($ar);
                if(isset($ar)){
                 $addressid= $ar->address_id;
                }else{
                  $addressid=0;
                }
    #get address beg
        $currentdate=date('Y-m-d');
        if(!isset($request->timeslot)){
             $request->timeslot='';
           }
         $withoutDeliveryDiscountTotal =  $request->ordertotal+$coupon_amount;
          
         $insorder=DB::table('orders')->insertGetId(
            ['user_id' =>$customerdetails, 'store_id' =>$store_detail->store_id , 'address_id'=>$addressid, 'cart_id' =>$cart_id,'total_price'=>$ordertotal, 'total_price_without_delivery_discount'=>$withoutDeliveryDiscountTotal,'price_without_delivery' =>$request->ordertotal, 'total_products_mrp'=>$request->ordertotalmrp, 'payment_method'=>'COD' ,'paid_by_wallet'=>0, 'rem_price' =>$ordertotal, 'order_date'=>$currentdate, 'delivery_date'=>$request->deliveydate,'delivery_charge'=>$request->delcharge, 'order_special_instructions'=>$request->special_instruction,'time_slot'=>$request->timeslot,'delivery_fname'=>$request->ufname, 'delivery_lname'=>$request->ulname, 'delivery_mobile'=>$request->uphone, 'delivery_email'=>$request->uemail, 'delivery_city'=>$request->ucity, 'delivery_landmark'=>$request->landmark,'delivery_type'=>$request->deliveryType,'delivery_type_id'=>$request->delivery_type_id,'delivery_whatsapp_no'=>$request->uphone_whatsapp, 'delivery_address'=>$request->uaddress,'dboy_id'=>0,'order_status'=>'Pending','user_signature'=>'','cancelling_reason'=>'','coupon_id'=>$coupon_code,'coupon_discount'=>$coupon_amount,'payment_status'=>'','cancel_by_store'=>'','image'=>'','bulk_order_based_discount'=>$request->bulkOrderDiscount]

        );
        // dd($insorder);

       $checkStore=array();
        $checka1='';
        $checka2=0;
        $secondCount = 0;

        foreach($products as $key => $value){
          $checka1 = $products[$key]->product_store_id;
          if($checka2!=$checka1){
            $products[$key]->totalPrice =  $products[$key]->price*$qty[$products[$key]->varient_id];
            $products[$key]->totalMrp = $products[$key]->mrp*$qty[$products[$key]->varient_id];
            $checkStore[] = $products[$key];
            $checka2=$checka1;
            $secondCount++;        
          }else{
            $checkStore[$secondCount-1]->totalPrice +=   $products[$key]->price*$qty[$products[$key]->varient_id];
            $checkStore[$secondCount-1]->totalMrp +=   $products[$key]->mrp*$qty[$products[$key]->varient_id];;
          }
        }
        $newCharge=0;
        foreach($checkStore as $key => $value){
          $newDelcharge=DB::table('freedeliverycart_by_store')
            ->where('delivery_store_id',$checkStore[$key]->product_store_id) 
            ->where('min_cart_value','>=',$checkStore[$key]->totalPrice)
            ->first();
          if($newDelcharge){
            $kk = $newDelcharge->del_charge;
            $checkStore[$key]->del_charge=$kk;
          }else{
            $kk = 0;
            $checkStore[$key]->del_charge=$kk;
          }
          $sub_cart_id = $cart_id.'-STORE-'.$checkStore[$key]->product_store_id;
          if($coupon_store_id && $checkStore[$key]->product_store_id==$coupon_store_id){
            $coupon_amount1=$coupon_amount;
            $coupon_code1=$coupon_code;

          }else{
            $coupon_amount1=0;
            $coupon_code1=0;

          }

          $newBulkDiscount=DB::table('bulk_order_discount')
          //->where('bulk_order_store_id',$checkStore[$key]->product_store_id) 
          ->where('bulk_order_min_amount','<=',$checkStore[$key]->totalPrice)
          ->where('bulk_order_max_amount','>=',$checkStore[$key]->totalPrice)
          ->first(); 

          if($newBulkDiscount){
            if($newBulkDiscount->bulk_order_discount_type=='percentage'){
              $bDiscountCharge = $checkStore[$key]->totalPrice*$newBulkDiscount->bulk_order_discount/100;  
            }else{
              $bDiscountCharge =$newBulkDiscount->bulk_order_discount;
            }  
          }else{
            $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
            $maxBDiscountData = DB::table('bulk_order_discount')
            ->where('bulk_order_max_amount',$maxValueAmountLimit)
            ->first();
            if($checkStore[$key]->totalPrice>$maxValueAmountLimit){
              if($maxBDiscountData->bulk_order_discount_type=='percentage'){
                $bDiscountCharge = $checkStore[$key]->totalPrice*$maxBDiscountData->bulk_order_discount/100;  
              }else{
                $bDiscountCharge =$maxBDiscountData->bulk_order_discount;
              }
            }else{
              $bDiscountCharge = 0;
            }
          }

          $subOrderTotal = $checkStore[$key]->totalPrice+$checkStore[$key]->del_charge-$coupon_amount1-$bDiscountCharge;
          $withoutDeliveryDiscountstoreTotal =  $checkStore[$key]->totalPrice;
          
          $ins_sub_order=DB::table('sub_orders')->insertGetId(
            ['user_id' =>$customerdetails, 'store_id' =>$checkStore[$key]->product_store_id, 'address_id'=>$addressid, 'cart_id' =>$cart_id,'sub_order_cart_id' =>$sub_cart_id,'total_price'=>$subOrderTotal, 'total_price_without_delivery_discount'=>$withoutDeliveryDiscountstoreTotal,'price_without_delivery' =>$checkStore[$key]->totalPrice-$coupon_amount1, 'total_products_mrp'=>$checkStore[$key]->totalMrp, 'payment_method'=>'COD' ,'paid_by_wallet'=>0, 'rem_price' =>$subOrderTotal, 'order_date'=>$currentdate, 'delivery_date'=>$request->deliveydate,'delivery_charge'=>$checkStore[$key]->del_charge, 'order_special_instructions'=>$request->special_instruction,'time_slot'=>$request->timeslot,'delivery_fname'=>$request->ufname, 'delivery_lname'=>$request->ulname, 'delivery_mobile'=>$request->uphone, 'delivery_email'=>$request->uemail, 'delivery_city'=>$request->ucity, 'delivery_landmark'=>$request->landmark,'delivery_type'=>$request->deliveryType,'delivery_type_id'=>$request->delivery_type_id,'delivery_whatsapp_no'=>$request->uphone_whatsapp, 'delivery_address'=>$request->uaddress,'dboy_id'=>0,'order_status'=>'Pending','user_signature'=>'','cancelling_reason'=>'','coupon_id'=>$coupon_code1,'coupon_discount'=>$coupon_amount1,'payment_status'=>'','cancel_by_store'=>'','image'=>'','bulk_order_based_discount'=>$bDiscountCharge]

          );

          //$newCharge+= $kk;
        }
        //dd($checkStore);  
        
      $year= substr(date("Y"),-2);

      $month=date('m');

       $date=date('d');
        
      
       $orderno=$year.$month.$date.'-'.$insorder;
     $deliverySlotData='';  
    if($request->delivery_type_id=='2'){
      $deliverySlotData = "<p><b>Delivery Date: </b>".$request->deliveydate."</p><p style='font-size:14px;color:red'>*You can not cancel the Express Delivery Order</p>";
    }else{
      $deliverySlotData = "<p><b>Delivery Date: </b>".$request->deliveydate."</p>";
      //"<p><b>Time Slot: </b>".$request->timeslot."</p>"
    } 
    $discountAmt = $cartMRPsubtotal-$cartsubtotal;
     $saveAmount = "<p style='font-size:14px;text-align:right'>*You saved Rs. ".$discountAmt." today</p>"; 
        
        
    $i=1;
    $html='<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; border:1px solid #000;width:500px;" align="center">
    <tr><th colspan="6">ORDER NO :'.' '. $orderno.'</th></tr>
    <tr><th >S.no</th><th>Item</th><th>MRP</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>';
    //$ordertotal=0;
    if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
     foreach ($cartpids['pid'] as $k=>$v){
         $ids[$k]=$k;
         $qty[$k]=$v;
     }
     if(is_array($ids) && count($ids)>0){
        // $products=Product::select('*')->whereIn('id',$ids)->get();
         $products= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','store.store_name','product.cat_id as pcid','product_varient.*','product.product_id as pid')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        ->join('store','product.product_store_id','store.store_id')
       // ->whereIn('product.product_id',$ids)->get(); //old
        ->whereIn('product_varient.varient_id',$ids)->get();
        //dd();
         
         
         //manipulate order total beg
         foreach($products as $product){
           // $ordertotal+=$product->pprice * $qty[$product->id];
             $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
             $discountprice=$product->mrp*$product->discount_percentage/100;
            // $cartsubtotal+=$price_after_discount *  $qty[$product->pid];
            
            /*$html.='<tr><td>'.$i.'</td><td>'.$product->product_name.'</td><td>'.$price_after_discount.'</td><td>'.$qty[$product->varient_id].'</td><td>'.$price_after_discount * $qty[$product->varient_id].'</td></tr>';*/

            $html.='<tr><td>'.$i.'</td><td>'.$product->product_name.'</td><td>'.$product->mrp.'</td><td>'.$product->price.'</td><td>'.$qty[$product->varient_id].'</td><td>'.$product->price * $qty[$product->varient_id].'</td></tr>';


            $i++;
         }
         $html.='<tr><td colspan="5">Total</td><td>'.$cartsubtotal.'</td></tr>';
         //manipulate order total end
     }
 }
  $html.='<tr style="text-align: left;"><td colspan="6">'.$saveAmount.'<p><b>Name: </b> '.ucfirst($request->ufname).' '.$request->ulname .'</p><p><b>Phone: </b>'.$request->uphone.'</p>
        <p><b>Email: </b>'.$request->uemail.'</p><p><b>Address: </b>'.$request->uaddress .','.$request->ucity.'</p>'.$deliverySlotData.'</td></tr></table>';


        $data['html']='<h2 style="text-align:center">Order Description</h2>
        <h3 style="text-align:center">Cart Items</h3>'.$html;
        $data['email']=$request->uemail;
        //$data['name']=$request->ulname;
        $data['name']=$request->ufname.' '.$request->ulname;

        $email = $request->uemail;

 
    //order total end
        $i=1;
        $datescv=date('d-F-Y');
        $timer = time();
     // $filename = "sfs-".$insorder->id.".csv"; 
      $filename = $orderno.".csv"; 
     $dirorder=public_path()."/order_csv/".$filename;

      //$downloadfile="/images/download/".$filename;
     // $downloadfile="/images/download/".$filename;
       $fhl=fopen($dirorder,"w");
            
           /*  fputcsv($fhl, array('sno'=>'S.No',
            'product'=>'Product',
            'price'=>'Price',
            'qty'=>'Quantity',
            'subtotal'=>'Sub Total',
            ));*/
            //new csv format beg
            //to show order no
        fputcsv($fhl, array('bl'=>'',
            'blll'=>'',
            'invoice' =>'Invoice',
            'invoiceno'=>$orderno,
            'blank1' =>'',
            
           
            ));
        fputcsv($fhl, array('bll'=>'',
            'blank1' =>'',
            'blank2' =>'',
            'blank3'=>'Date',
            'date'=>$datescv,
            'blank4'=>'',
          
            ));
        //blank
        fputcsv($fhl, array(''=>'',
            'blank1' =>'',
            'blank2' =>'',
            'blank3'=>'',
            'blank4'=>'',
            'blank4'=>'',
          
            ));

        //
         fputcsv($fhl, array(
        'bl2' =>'',
        'name'=>'Name',
        'b'=>$request->ufname.' '.$request->ulname,
        'a'=>'',
        'c'=>'',
        'd'=>'',
       
           
            ));
         fputcsv($fhl, array(
            'bl3' =>'',
        'address'=>'landmark',
         'b'=>$request->landmark,
        'a'=>'',
        'c'=>'',
        'd'=>'',
            ));
          fputcsv($fhl, array(
            'bl4' =>'',
        'address'=>'Address',
         'b'=>$request->uaddress.', '.$request->ucity,
        'a'=>'',
        'c'=>'',
        'd'=>'',
            ));
           fputcsv($fhl, array(
        'bl5'=>'',
        'phone'=>'Phone Number',
        'b'=>$request->uphone,
        'a'=>'',
        'c'=>'',
        'd'=>'',
       
           
            ));
            fputcsv($fhl, array(
        'd'=>'',
        'b'=>'',
        'a'=>'',
        'c'=>'',
        'd'=>'',
       
           
            ));
            fputcsv($fhl, array('sno'=>'S.No',
            'product'=>'Items',
            'mrp'=>'MRP',
            'rate'=>'Rate',
            'quantity'=>'Quantity',
            'amount'=>'Amt(Rs)'
            
            ));
        //
            //new csv format end

        foreach($products as $product){
           // $subt=$product->pprice * $qty[$product->id];
             $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
             $discountprice=$product->mrp*$product->discount_percentage/100;
            // $cartsubtotal+=$price_after_discount *  $qty[$product->pid];
          //  $subt=$price_after_discount * $qty[$product->pid]; //old
            //$subt=$price_after_discount * $qty[$product->varient_id]; //new
            $subt=$product->price * $qty[$product->varient_id];
            $price_after_discount=$product->price;
            
        //added code for putcsv beg 
        //added code to save data in store orders beg store_orders
       /* $insoproduct=DB::table('orderproducts')->insert(
            ['product_name'=>$product->product_name,'product_price'=>$price_after_discount,'product_qty'=>$qty[$product->pid],'order_id'=>$insorder]

        );*/
        //added new code to insert order products in store orders table beg
        $varient_id=$product->varient_id;
        $order_qty=$qty[$product->varient_id];
        $n=$product->product_name;
        $varient_image=$product->varient_image;
        $quantity=$product->quantity;
        $unit=$product->unit;
        $sub_cart_id = $cart_id.'-STORE-'.$product->product_store_id;
        
        $created_at=date('Y-m-d');
        $price1     =$price_after_discount*$order_qty;
         $total_mrp  = $product->mrp*$order_qty;

        $insert = DB::table('store_orders')
                            ->insertGetId([
                              'varient_id'     => $varient_id,
                              'qty'            => $order_qty,
                              'product_name'   => $n,
                              'varient_image'  => $varient_image,
                              'quantity'       => $quantity,
                              'unit'           => $unit,
                              'total_mrp'      => $total_mrp,
                              'order_cart_id'  => $cart_id,
                              'sub_order_cart_id'=>$sub_cart_id,
                              'order_date'     => $created_at,
                              'actual_price'   => $product->price,
                              'price'          => $price1
                            ]);
        //added new code to insert order products in store orders table end

        //added code to save data in store orders end
        #added code to update product variant stock after order is placed beg
          $getstockdata=DB::table('store_products')
                       ->where('varient_id','=',$varient_id)
                       ->first();
            //dd($getstockdata);
          $currentstock=$getstockdata->stock ?? 0;
          $remainingstock= $currentstock - $order_qty;
          $updatestore=DB::table('store_products')
                       ->where('varient_id','=',$varient_id)
                       ->update(['stock'=>$remainingstock]);
        #added code to update product variant stock after order is placed end
       
            /*fputcsv($fhl, array('sno'=>$i,
            'product'=>$product->product_name,
            'price'=>$price_after_discount,
             'qty'=>$qty[$product->pid],
            'subtotal'=> $subt
            ));*/
            //new beg
            fputcsv($fhl, array('sno'=>$i,
            'product'=>$product->product_name.'(' .$product->quantity.''.$product->unit.')',
            //'rate'=>$price_after_discount,
            'mrp'=>$product->mrp,
            'rate'=>$product->price,
            'quantity'=>$qty[$product->varient_id],
            'amount'=> $subt
            ));
            //new end

    
        
        //end end csv
            $i++;

    }  
    /*fputcsv($fhl, array(
        'total'=>'Total',
        'a'=>'',
        'b'=>'',
        'c'=>'',
            'tvalue'=>$request->ordertotal
           
            ));   */ 
            //new beg
            fputcsv($fhl, array(
        'total'=>'Total',
        'a'=>'',
        'b'=>'',
        'c'=>'',
        'd'=>'',
        'tvalue'=>$request->ordertotal,
           
            ));   
            //new end   

        //fclose($file);            
            fclose($fhl);
           // dd($dirorder);
           // $data['fn']=$filename;
            //dd(env('MAIL_HOST'));
            $emails=array();
            $emails['fl']=public_path()."/order_csv/".$filename;
            $allMails = ['colddrinkteam@gmail.com']; 
            //$allMails = [$email,'raviprakash.alobha@gmail.com']; 
            //$allMails = [$email,'colddrinkteam@gmail.com']; 
            #added code for email beg
             $a=  Mail::send('email.reply', ['data' => $data,'emails' => $emails] , function ($message) use ($email,$emails,$allMails)
        {
            $message->from($email, '');
            $message->subject('Order summary');
            $message->to($allMails);   
            $message->attach($emails['fl']); 
        
        }); 
            
             //send email from admin to user beg
         Mail::send('email.reply', ['data' => $data,'emails' => $emails], function ($message) use ($email,$emails)
         {

            $message->from('colddrinkteam@gmail.com', '');
            $message->subject('Order summary');
            $message->to($email);
            $message->attach($emails['fl']);

        });
            #added code for email end
            
           
        if($ordertotal){
          $getInvitationMsg = urlencode("Dear Customer, your Dealwy order no is ".$cart_id." and the bill amount is ".$ordertotal." and delivery within 48 hours.");
          $apiUrl = 'http://www.onex-ultimo.in/api/pushsms?user=NFDML&authkey=92A7YFf76gJU&sender=Dealwy&mobile='.$request->uphone.'&text='.$getInvitationMsg.'&rpt=1&summary=1&output=json&entityid=1201160517117234073&templateid=1207163298118080010';

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $apiUrl);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $response = curl_exec($ch);
          curl_close($ch);
        }
       

        

        //added code to insert order data in orders and orderproducts table end
        Session::forget('cartpids');
        Session::forget('totalcartqty');
        //forget coupon session
        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('bulkOrderDiscount');
        Session::forget('delivercharge');
        Session::forget('footer_cart_qty');
        Session::forget('cart_product_list');

        return redirect()->back()->with('msg','Order Placed Successfully. Check your email');
        //return redirect('/checkout')->with('msg','order placed successfully.check your email');
    }
    
    //form end
 //dd($filename);
 Session::put('totalcartqty', $productcount);
 $totalitem=Session::get('totalcartqty');
 //
  $catarray= $this->catarray;
 $categories= $this->categories;
 $catpcount= $this->catpcount;
 $coupondata= $this->coupondata;
       
 //
    
    
    return view('checkout.checkout',compact('cartpids','ordertotal','products','qty','catarray','catpcount','cat_crousel','coupons','ReturnArray','getaddress_detail','get_city','todayDate','delivery_type','terms','coupondata','cat'));


}


//added code for ajax timeslot
public function get_timeslot(Request $request){
  $date  =   $request->cdate;
  $currentDate  = date('Y-m-d');
  $currentTime = Carbon::now()->format('H:i');
  $time_slot      = DB::table('time_slot')->first();

  //added code to get time slot beg
  $StartTime=$time_slot->open_hour;
  $EndTime=$time_slot->close_hour;
  $Duration=$time_slot->time_slot;
  $ReturnArray = array ();// Define output
  $StartTime    = strtotime ($StartTime); //Get Timestamp
  $EndTime      = strtotime ($EndTime); //Get Timestamp

  $AddMins  = $Duration * 60;
  while ($StartTime <= $EndTime){
    $ReturnArray[] = date ("G:i", $StartTime);
    $StartTime += $AddMins; //Endtime check
  }  
  $count=count($ReturnArray) -2;
  $newhtml='';

  if($date == $currentDate){
    for($i=0;$i <= $count;$i++){
      if($currentTime<=Carbon::parse($ReturnArray[$i])->format('H:i')){ 
        $newhtml.='
        <div class="form-check">
        <input class="form-check-input timeslot ddf" id="timeslot'.$i.'" type="radio" name="timeslot"  value="'.$ReturnArray[$i].'-'.$ReturnArray[$i+1].'" required>
        <label class="form-check-label" for="timeslot'.$i.'">'.$ReturnArray[$i].'-'.$ReturnArray[$i+1].'
        </label>
        </div>';
      }
    }
    return response()->json(['code'=>'200','message'=>'date match','newHtml'=>$newhtml,'timeslot'=>$date,'timeslot22'=>$currentDate]);
  }else{
    for($i=0;$i <= $count;$i++){

      $newhtml.='
      <div class="form-check">
      <input class="form-check-input timeslot ddf" id="timeslot'.$i.'" type="radio" name="timeslot"  value="'.$ReturnArray[$i].'-'.$ReturnArray[$i+1].'" required>
      <label class="form-check-label" for="timeslot'.$i.'">'.$ReturnArray[$i].'-'.$ReturnArray[$i+1].'
      </label>
      </div>';
    }
    return response()->json(['code'=>'100','message'=>'not matched','newHtml'=>$newhtml,'timeslot'=>$date,'timeslot22'=>$currentDate]);
  }

}
//end of code for ajax timeslot

//added code for checkout end
//added code 20 oct for checkout popup and checkout page form submit end
//cart page beg
public function cart(Request $request){
   // dd('here');
    
  $cartpids = Session::get('cartpids'); 
     //added code to update cart items after remove product beg
    if(isset($this->cartpids)){
        
         Session::put('cartpids', $this->cartpids);
    }
//added code to update cart items after remove product end


 $ids=array();
 $qty=array();
 $totalcartqty=0; //to show cart total qty on top
 //$totalcartitem=0; //to show cart total qty on top
 $products=array();
 $productcount = 0;

 if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
     foreach ($cartpids['pid'] as $k=>$v){
         $ids[$k]=$k;
         $qty[$k]=$v;
         $totalcartqty+=$v;
     }
     if(is_array($ids) && count($ids)>0){
       
         $products= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product_varient.*','product.product_id as pid')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        ->whereIn('product.product_id',$ids)->get();
        
         $productcount=DB::table('product')->select('*')->whereIn('product_id',$ids)->count();
     }
 }
 //dd($productcount);
 //Session::put('totalcartqty', $totalcartqty);
 Session::put('totalcartqty', $productcount);
    $catarray= $this->catarray;
    $categories= $this->categories;
    $catpcount= $this->catpcount;
    //$products= $this->products;
    $catproductrels= $this->catproductrels;
    $catproductdetails= $this->catproductdetails;

 //return view('carts.cart',compact('ids','qty','products'));
 return view('carts.cart',compact('ids','qty','products','totalcartqty','catarray','catpcount'));
}
//cart page end
//added code to remove product beg
 /*public function removeproduct(Request $request,$id)
    {
        $cartpids = Session::get('cartpids'); 

        unset($cartpids['pid'][$id]); 
        Session::put('cartpids', $cartpids);
         $cartpids = Session::get('cartpids');  
       


    }*old method remove*/
//added code to remove product end
//added code for razorpay test payment beg
    public function removeproduct(Request $request,$id)
    {
             #added code to get delivery charge beg
        Session::forget('coupon_code');
        Session::forget('coupon_amount');
         $cartsubtotal=0;
         $cartMrpsubtotal=0;
         $bulkOrderDiscount=0;
        /*$delcharge = DB::table('freedeliverycart')
                               ->where('id', 1)
                               ->first();
        $deliverycharge=$delcharge->del_charge;*/
       #added code to get delivery charge end
        $cartpids = Session::get('cartpids'); 

        unset($cartpids['pid'][$id]); 
        Session::put('cartpids', $cartpids);
         $cartpids = Session::get('cartpids');  
         //adde code to update
         $ids=array();
         $qty=array(); 
        $products=array();
        $totalcartqty=0;
        $productcount=0;

        $checkStore=array();
        $checka1='';
        $checka2=0;
        $secondCount = 0;
 
 if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
     foreach ($cartpids['pid'] as $k=>$v){
         $ids[$k]=$k;
         $qty[$k]=$v;
         $totalcartqty+=$v;
     }
     if(is_array($ids) && count($ids)>0){

      $products= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','store.store_id','store.store_name','product.cat_id as pcid','product_varient.*','store.store_id','store.store_name','product.product_id as pid')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        ->Leftjoin('store','product.product_store_id','=','store.store_id')
        //->whereIn('product.product_id',$ids)->get();
        ->whereIn('product_varient.varient_id',$ids)->get();

         $productcount=DB::table('product_varient')->select('*')->whereIn('varient_id',$ids)->count(); //added for count
         #added code to getcaet total after removeitem beg
         foreach($products as $product) {
           // dd($product);
           $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
           $discountprice=$product->mrp*$product->discount_percentage/100;
           // $cartsubtotal+=$price_after_discount *  $qty[$product->pid];//previous
           $price_after_discount=$product->price;
              $cartsubtotal+=$price_after_discount *  $qty[$product->varient_id];
              $cartMrpsubtotal+=$product->mrp *  $qty[$product->varient_id];
              $product->totalPrice = $price_after_discount *  $qty[$product->varient_id];
              $product->totalMrp = $product->mrp *  $qty[$product->varient_id];
        }
         #added code to getcaet total after removeitem end
        foreach($products as $key => $value){
              $checka1 = $products[$key]->product_store_id;

              if($checka2!=$checka1){
                $checkStore[] = $products[$key];
                $checka2=$checka1;
                $secondCount++;
              }else{
                $checkStore[$secondCount-1]->totalPrice +=   $products[$key]->totalPrice;
                $checkStore[$secondCount-1]->totalMrp +=   $products[$key]->totalMrp;
              }
            }
        
      }

    }

    $newCharge=0;
        foreach($checkStore as $checkStores){
          $newDelcharge=DB::table('freedeliverycart_by_store')
            ->where('delivery_store_id',$checkStores->product_store_id) 
            ->where('min_cart_value','>=',$checkStores->totalPrice)
            ->first();
      
          if($newDelcharge){
            $kk = $newDelcharge->del_charge;
          }else{
            $kk = 0;
          }   
          $newCharge+= $kk;

          $newBulkDiscount=DB::table('bulk_order_discount')
            //->where('bulk_order_store_id',$checkStores->product_store_id) 
          ->where('bulk_order_min_amount','<=',$checkStores->totalPrice)
          ->where('bulk_order_max_amount','>=',$checkStores->totalPrice)
          ->first(); 

          if($newBulkDiscount){
            if($newBulkDiscount->bulk_order_discount_type=='percentage'){
              $bb = $checkStores->totalPrice*$newBulkDiscount->bulk_order_discount/100;  
            }else{
              $bb =$newBulkDiscount->bulk_order_discount;
            }  
          }else{
            $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
            $maxBDiscountData = DB::table('bulk_order_discount')
            ->where('bulk_order_max_amount',$maxValueAmountLimit)
            ->first();
            if($checkStores->totalPrice>$maxValueAmountLimit){
              if($maxBDiscountData->bulk_order_discount_type=='percentage'){
                $bb = $checkStores->totalPrice*$maxBDiscountData->bulk_order_discount/100;  
              }else{
                $bb =$maxBDiscountData->bulk_order_discount;
              }
            }else{
              $bb = 0;
            }
          }

          $bulkOrderDiscount+= $bb;


        }
    $delcharge=DB::table('freedeliverycart')
            ->where('min_cart_value','<=',$cartsubtotal)
            ->orderby('id', 'desc')
            ->first();

 #added code to check wether delivery charge will be applied or not beg
 $charge=0;
 if(isset($delcharge)){
  $charge=$delcharge->del_charge;
 }
 /*if($cartsubtotal >= $delcharge->min_cart_value){
 // $cartsubtotal=$cartsubtotal+$deliverycharge;
  $charge=$deliverycharge; //take this variable in session n also send in input tye hidden in cart html

 }/*
 /* if($cartsubtotal <= $delcharge->min_cart_value){
 // $cartsubtotal=$cartsubtotal+$deliverycharge;
  $charge=100; //take this variable in session n also send in input tye hidden in cart html

 }*/
 /*if($cartsubtotal >= 500){
 // $cartsubtotal=$cartsubtotal+$deliverycharge;
  $charge=0; //take this variable in session n also send in input tye hidden in cart html

 }*/
 $cartsubtotal=round($cartsubtotal,2);
 $cartMrpsubtotal=round($cartMrpsubtotal,2);
 $bulkOrderDiscount=round($bulkOrderDiscount,2);
 Session::put('delivercharge', $newCharge); // session delivercharge
 Session::put('bulkOrderDiscount', $bulkOrderDiscount);
 $delivercharge=Session::get('delivercharge'); 
 #added code to check wether delivery charge will be applied or not end 
 Session::put('totalcartqty', $productcount);
 Session::put('footer_cart_qty', $qty);
 $totalitem=Session::get('totalcartqty');
 Session::get('footer_cart_qty');
 Session::put('cart_product_list', $products);
 $cart_product_list=Session::get('cart_product_list'); 
 ///////////////
 return response()->json(['totalitem'=>$totalitem,'products'=>$products,'delivercharge'=>$newCharge,'cartsubtotal'=>$cartsubtotal,'cartMrpsubtotal'=>$cartMrpsubtotal,'checkStoreArray'=>$checkStore,'bulkOrderDiscount'=>$bulkOrderDiscount]);

 /////

}

    //added code for payment beg
    public function initiate(Request $request){
      //dd($request->all());
      $checkStore = json_decode($request->all()['storeWiseTotal']);
      $onlineOrderDiscount=0;
      $bulkOrderDiscount=0;
      $newCharge=0;
      $coupon_code='';
      $coupon_amount=0;
      if(session()->has('coupon_code') && session()->has('coupon_amount')){
            $coupon_code=Session::get('coupon_code');
            $coupon_amount=Session::get('coupon_amount');
      }

      //dd($checkStore);
      foreach($checkStore as $checkStores){
        $newDelcharge=DB::table('freedeliverycart_by_store')
            ->where('delivery_store_id',$checkStores->product_store_id) 
            ->where('min_cart_value','>=',$checkStores->totalPrice)
            ->first();
      
          if($newDelcharge){
            $kk = $newDelcharge->del_charge;
          }else{
            $kk = 0;
          }   
          $newCharge+= $kk;

        $newBulkDiscount=DB::table('bulk_order_discount')
            //->where('bulk_order_store_id',$checkStores->product_store_id) 
            ->where('bulk_order_min_amount','<=',$checkStores->totalPrice)
            ->where('bulk_order_max_amount','>=',$checkStores->totalPrice)
            ->first(); 
      
        if($newBulkDiscount){
          if($newBulkDiscount->bulk_order_discount_type=='percentage'){
            $bb = $checkStores->totalPrice*$newBulkDiscount->bulk_order_discount/100;  
          }else{
            $bb =$newBulkDiscount->bulk_order_discount;
          }  
        }else{
          $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
          $maxBDiscountData = DB::table('bulk_order_discount')
                         ->where('bulk_order_max_amount',$maxValueAmountLimit)
                         ->first();
          if($checkStores->totalPrice>$maxValueAmountLimit){
            if($maxBDiscountData->bulk_order_discount_type=='percentage'){
              $bb = $checkStores->totalPrice*$maxBDiscountData->bulk_order_discount/100;  
            }else{
              $bb =$maxBDiscountData->bulk_order_discount;
            }
          }else{
            $bb = 0;
          }
        }

          $bulkOrderDiscount+= $bb;

          

          $newOnlineDiscount=DB::table('online_payment_discount') 
          ->where('online_payment_min_amount','<=',$checkStores->totalPrice-$bb+$kk)
          ->where('online_payment_max_amount','>=',$checkStores->totalPrice-$bb+$kk)
          ->first();
          //dd($newOnlineDiscount); 

          if($newOnlineDiscount){
            if($newOnlineDiscount->online_payment_discount_type=='percentage'){
              $oo = ($checkStores->totalPrice-$bb+$kk)*$newOnlineDiscount->online_payment_discount/100;  
            }else{
              $oo =$newOnlineDiscount->online_payment_discount;
            }  
          }else{
            $maxValueAmountLimit = DB::table('online_payment_discount')->max('online_payment_max_amount');
            $maxOnlineDiscountData = DB::table('online_payment_discount')
            ->where('online_payment_max_amount',$maxValueAmountLimit)
            ->first();
            if($checkStores->totalPrice-$bb+$kk>$maxValueAmountLimit){
              if($maxOnlineDiscountData->online_payment_discount_type=='percentage'){
                $oo = ($checkStores->totalPrice-$bb+$kk)*$maxOnlineDiscountData->online_payment_discount/100;  
              }else{
                $oo =$maxOnlineDiscountData->online_payment_discount;
              }
            }else{
              $oo = 0;
            }
          }

          $onlineOrderDiscount+= $oo;

        }
        //dd($onlineOrderDiscount);

      $cat= $this->cat;
         if ($request->isMethod('post')) {
          
            $cat_crousel=$this->cat_crousel;
            $coupondata=$this->coupondata;
            $api= new Api($this->razorpayid,$this->razorpaykey);
           

                  //generate random reciept id
                  $recieptid=Str::random(20);
                  //create order
                  $order = $api->order->create(array(
                    'receipt' => $recieptid,
                    'amount' => $request->all()['ordertotal'] * 100 + $request->all()['delcharge'] * 100 - $request->all()['bulkOrderDiscount'] * 100 - round($onlineOrderDiscount,2)*100,
                    'currency' => 'INR'
                    )
                  );
                  //lets return the response
                  //lets create the razorpay payment page
                  $timeslotValue='';
                  if(isset($request->all()['timeslot'])){
                    $timeslotValue = $request->all()['timeslot'];
                  }

                  $addressNew = preg_replace( "/\r|\n/", "", $request->all()['uaddress'] );

                  $response=[
                   'orderId' =>$order['id'],
                   'razorpayId' =>$this->razorpayid,
                   'amount' =>$request->all()['ordertotal'],
                   'name' =>$request->all()['ufname'].' '.$request->all()['ulname'],
                   'currency' => 'INR',
                   'email' => $request->all()['uemail'],
                   'contactnumber' => $request->all()['uphone'],
                   'address' => $addressNew,
                   'description' => 'Dealwy Online Payment',
                   'deliveydate' => $request->all()['deliveydate'],
                   'delcharge' => $request->all()['delcharge'],
                   'timeslot' =>$timeslotValue,
                   "bulkOrderDiscount" => $request->bulkOrderDiscount,
                   "storeWiseTotal" => $request->storeWiseTotal,
                   "ordertotal" => $request->ordertotal,
                   "storeWiseTotal" => $request->storeWiseTotal,
                   "ordertotalmrp" => $request->ordertotalmrp,
                   "uphone_whatsapp" => $request->uphone_whatsapp,
                   "ucity" => $request->ucity,
                   "landmark" => $request->landmark,
                   "deliveryType" => $request->deliveryType,
                   "ucity" => $request->ucity,
                   "paymenttype" => $request->paymenttype,
                   "special_instruction" => $request->special_instruction,
                   'onlineOrderDiscount' => $onlineOrderDiscount,
                   'lastpaymentAmount'   => $request->all()['ordertotal'] + $request->all()['delcharge'] - $request->all()['bulkOrderDiscount'] - round($onlineOrderDiscount,2),
                   'couponCode'=>$coupon_code,
                   'couponAmount'=>$coupon_amount,
                   ];
                  //dd($response);
                }
         
        return view('website.payment-page',compact('response','cat_crousel','coupondata','cat'));

    }

    //paymentend
    //complete pament
    public function complete(Request $request){
      //dd('make payment');
     //dd($request->all());

     $cat_crousel=$this->cat_crousel;

    
     //dd($request->all());
     //save data in database
     //save data in database, make csv and send mail and destroy session beg
     //code to add payment complete page
     $products=''; //default
     $checkStore='';
     $cartsubtotal=0;
     $cartMRPsubtotal=0;
     $productcount=0;
     $cartpids = Session::get('cartpids'); 
     $orderno='';

     $ordertotal=0;
     $ids=array();
     $qty=array();
     //Store detail
     $store_detail=DB::table('store')
                           ->first();

     if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
     foreach ($cartpids['pid'] as $k=>$v){
         $ids[$k]=$k;
         $qty[$k]=$v;
     }
    }
     if(is_array($ids) && count($ids)>0){

        
          $products= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','store.store_name','product.cat_id as pcid','product_varient.*','product.product_id as pid','store_products.stock')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        ->Leftjoin('store','product.product_store_id','=','store.store_id')
         ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
        ->whereIn('product_varient.varient_id',$ids)->get();
         $productcount=DB::table('product_varient')->select('*')->whereIn('varient_id',$ids)->count();
         //dd($products);
         //manipulate order total beg
         foreach($products as $product){
            //$ordertotal+=$product->pprice * $qty[$product->id];
            $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
            $discountprice=$product->mrp*$product->discount_percentage/100;
            //$cartsubtotal+=$price_after_discount *  $qty[$product->varient_id];
            $cartsubtotal+=$product->price *  $qty[$product->varient_id];
            $cartMRPsubtotal+=$product->mrp *  $qty[$product->varient_id];
            
         }
        
        
     }
     //to insert data in database ,create csv and send mail beg
     $cod='Razorpay';
     //dd($cod);

        //added code to insert customer detail in user table beg
         //user check beg
         $customercheck=DB::table('users')->select('user_email','user_id')
         ->where('user_email',$request->uemail)
         ->orWhere('user_phone',$request->uphone)
         ->first();
        // dd($customercheck);
         //user check end
         if(empty($customercheck)){
        $customerdetails=DB::table('users')
        ->insertGetId(['user_name'=>$request->ufname,'user_phone'=>$request->uphone,'user_email'=>$request->uemail]);
    }else{
         $customerdetails= $customercheck->user_id;
    }
       //added code to generate cart id beg
       $chars =  "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $val              =  "";

        # random character get for make cart id.
        for ($i = 0; $i < 4; $i++) {
          $val .= $chars[mt_rand(0, strlen($chars)-1)];
        }

        $chars2 = "0123456789";
        $val2 = "";
        # random number get for make cart id. there are 2 digit numbers.
        for ($i = 0; $i < 2; $i++){
          $val2 .= $chars2[mt_rand(0, strlen($chars2)-1)];
        }

        # get microtime.
        $cr      = substr(md5(microtime()),rand(0, 26),2);

        # make cart id with character, number and time strings.
        $cart_id = $val.$val2.$cr;

       //added code to generate cart id end
        $currentdate=date('Y-m-d');
         // $insorder=DB::table('orders')->insertGetId(
         //    ['user_id' =>$customerdetails, 'store_id' =>1 , 'address_id'=>0, 'cart_id' =>$cart_id,'total_price'=>$cartsubtotal, 'price_without_delivery' =>0, 'total_products_mrp'=>0, 'payment_method'=>'razorpay' ,'paid_by_wallet'=>0, 'rem_price' =>0, 'order_date'=>$currentdate, 'delivery_date'=>'','delivery_charge'=>0, 'time_slot'=>0,'dboy_id'=>0,'order_status'=>'confirmed','user_signature'=>'','cancelling_reason'=>'','coupon_id'=>'','coupon_discount'=>'','payment_status'=>'','cancel_by_store'=>'','image'=>'']
            $coupon_code='';
            $coupon_amount=0;
            $coupon_store_id='';
            # if coupon applied beg
            if(session()->has('coupon_code') && session()->has('coupon_amount')){
              $coupon_code=Session::get('coupon_code');
              $coupon_amount=Session::get('coupon_amount');
            }

            if($coupon_amount>0){
              //dd(json_decode($request->storeWiseTotal));
              $coupon_details=DB::table('coupon')
                  ->where(DB::raw("BINARY `coupon_code`"),$coupon_code)
                  ->first();
              $coupon_store_id =$coupon_details->coupon_store_id;
            }
            
             $ordertotal = $request->ordertotal - $request->bulkOrderDiscount;
             //dd($ordertotal);
              #added code to get rewars point update for users on the basic of cart value beg
          #first get user previous reward value beg
               $previous_reward=DB::table('users')
              ->where('user_id','=',$customerdetails)
              ->first();
          #first get user previous reward value end
          $select_reward=DB::table('reward_points')
                     ->where('min_cart_value','<=', $ordertotal)
                     ->first();
          if(isset($select_reward)){
             #insert reward points in cart_rewards beg
          $insertrewad=DB::table('cart_rewards')
                       ->insert(['cart_id'=>$cart_id,'rewards'=>$select_reward->reward_point ?? 0,'user_id'=>$customerdetails]);
          #insert reward points in cart_rewards end
            #user rewards on cart min value
            $total_reward_after=$previous_reward->rewards + $select_reward->reward_point;
            $update_user_reward=DB::table('users')
                               ->where('user_id','=',$customerdetails)
                               ->update(['rewards'=>$total_reward_after]);
          }
          #added code to get rewars point update for users on the basic of cart value end
           #get address beg
            $ar      = DB::table('address')
                                  ->select('society','city','lat','lng','address_id')
                                  ->where('user_id', $customerdetails)
                                 // ->where('select_status', 1)
                                  ->first();
                if(isset($ar)){
                 $addressid= $ar->address_id;
                }else{
                  $addressid=0;
                }
        #get address beg
        $ordertotal=$ordertotal + $request->delcharge- round( $request->onlineOrderDiscount,2);
        //dd($ordertotal);
           if(!isset($request->timeslot)){
             $request->timeslot='';
           }


           $withoutDeliveryDiscountTotal =  $request->ordertotal+$coupon_amount;
           $onlineDiscountAmt= round( $request->onlineOrderDiscount,2);

            $insorder=DB::table('orders')->insertGetId(
            ['user_id' =>$customerdetails, 'store_id' =>$store_detail->store_id , 'address_id'=>$addressid, 'cart_id' =>$cart_id,'total_price'=>$ordertotal,'total_price_without_delivery_discount'=>$withoutDeliveryDiscountTotal, 'price_without_delivery' =>$request->ordertotal, 'total_products_mrp'=>$request->ordertotalmrp, 'payment_method'=>'razorpay' ,'paid_by_wallet'=>0, 'rem_price' =>0, 'order_date'=>$currentdate, 'delivery_date'=>$request->deliveydate,'delivery_charge'=>$request->delcharge, 'order_special_instructions'=>$request->special_instruction,'time_slot'=>$request->timeslot,'delivery_fname'=>$request->ufname, 'delivery_lname'=>$request->ulname, 'delivery_mobile'=>$request->uphone, 'delivery_email'=>$request->uemail, 'delivery_city'=>$request->ucity, 'delivery_landmark'=>$request->landmark,'delivery_type'=>$request->deliveryType,'delivery_type_id'=>$request->delivery_type_id,'delivery_whatsapp_no'=>$request->uphone_whatsapp, 'delivery_address'=>$request->uaddress,'dboy_id'=>0,'order_status'=>'Pending','user_signature'=>'','cancelling_reason'=>'','coupon_id'=>$coupon_code,'coupon_discount'=>$coupon_amount,'payment_status'=>'','cancel_by_store'=>'','image'=>'','bulk_order_based_discount'=>$request->bulkOrderDiscount,'online_payment_based_discount'=>$onlineDiscountAmt]

        );
        // dd($insorder);

          $checkStore=array();
        $checka1='';
        $checka2=0;
        $secondCount = 0;
        foreach($products as $key => $value){
          $checka1 = $products[$key]->product_store_id;
          if($checka2!=$checka1){
            $products[$key]->totalPrice =  $products[$key]->price*$qty[$products[$key]->varient_id];
            $products[$key]->totalMrp = $products[$key]->mrp*$qty[$products[$key]->varient_id];
            $checkStore[] = $products[$key];
            $checka2=$checka1;
            $secondCount++;        
          }else{
            $checkStore[$secondCount-1]->totalPrice +=   $products[$key]->price*$qty[$products[$key]->varient_id];
            $checkStore[$secondCount-1]->totalMrp +=   $products[$key]->mrp*$qty[$products[$key]->varient_id];;
          }
        }
        
        $newCharge=0;
        foreach($checkStore as $key => $value){
          $newDelcharge=DB::table('freedeliverycart_by_store')
            ->where('delivery_store_id',$checkStore[$key]->product_store_id) 
            ->where('min_cart_value','>=',$checkStore[$key]->totalPrice)
            ->first();
          if($newDelcharge){
            $kk = $newDelcharge->del_charge;
            $checkStore[$key]->del_charge=$kk;
          }else{
            $kk = 0;
            $checkStore[$key]->del_charge=$kk;
          }
          $sub_cart_id = $cart_id.'-STORE-'.$checkStore[$key]->product_store_id;
          //dd($coupon_store_id && $checkStore[$key]->product_store_id==$coupon_store_id);
          if($coupon_store_id && $checkStore[$key]->product_store_id==$coupon_store_id){
            $coupon_amount1=$coupon_amount;
            $coupon_code1=$coupon_code;

          }else{
            $coupon_amount1=0;
            $coupon_code1=0;
          }

          $newBulkDiscount=DB::table('bulk_order_discount')
            //->where('bulk_order_store_id',$checkStore[$key]->product_store_id) 
            ->where('bulk_order_min_amount','<=',$checkStore[$key]->totalPrice)
            ->where('bulk_order_max_amount','>=',$checkStore[$key]->totalPrice)
            ->first(); 
      
        if($newBulkDiscount){
          if($newBulkDiscount->bulk_order_discount_type=='percentage'){
            $bb = $checkStore[$key]->totalPrice*$newBulkDiscount->bulk_order_discount/100;  
          }else{
            $bb =$newBulkDiscount->bulk_order_discount;
          }  
        }else{
          $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
          $maxBDiscountData = DB::table('bulk_order_discount')
                         ->where('bulk_order_max_amount',$maxValueAmountLimit)
                         ->first();
          if($checkStore[$key]->totalPrice>$maxValueAmountLimit){
            if($maxBDiscountData->bulk_order_discount_type=='percentage'){
              $bb = $checkStore[$key]->totalPrice*$maxBDiscountData->bulk_order_discount/100;  
            }else{
              $bb =$maxBDiscountData->bulk_order_discount;
            }
          }else{
            $bb = 0;
          }
        }
          

          $newOnlineDiscount=DB::table('online_payment_discount') 
          ->where('online_payment_min_amount','<=',$checkStore[$key]->totalPrice-$bb+$kk)
          ->where('online_payment_max_amount','>=',$checkStore[$key]->totalPrice-$bb+$kk)
          ->first();
          //dd($newOnlineDiscount); 

          if($newOnlineDiscount){
            if($newOnlineDiscount->online_payment_discount_type=='percentage'){
              $oo = ($checkStore[$key]->totalPrice-$bb+$kk)*$newOnlineDiscount->online_payment_discount/100;  
            }else{
              $oo =$newOnlineDiscount->online_payment_discount;
            }  
          }else{
            $maxValueAmountLimit = DB::table('online_payment_discount')->max('online_payment_max_amount');
            $maxOnlineDiscountData = DB::table('online_payment_discount')
            ->where('online_payment_max_amount',$maxValueAmountLimit)
            ->first();
            if($checkStore[$key]->totalPrice-$bb+$kk>$maxValueAmountLimit){
              if($maxOnlineDiscountData->online_payment_discount_type=='percentage'){
                $oo = ($checkStore[$key]->totalPrice-$bb+$kk)*$maxOnlineDiscountData->online_payment_discount/100;  
              }else{
                $oo =$maxOnlineDiscountData->online_payment_discount;
              }
            }else{
              $oo = 0;
            }
          }

          

          $subOrderTotal = $checkStore[$key]->totalPrice+$checkStore[$key]->del_charge-$coupon_amount1-$bb-$oo;
          $subwithoutDeliveryDiscountstoreTotal =  $checkStore[$key]->totalPrice;

          
          $ins_sub_order=DB::table('sub_orders')->insertGetId(
            ['user_id' =>$customerdetails, 'store_id' =>$checkStore[$key]->product_store_id, 'address_id'=>$addressid, 'cart_id' =>$cart_id,'sub_order_cart_id' =>$sub_cart_id,'total_price'=>$subOrderTotal,'total_price_without_delivery_discount'=>$subwithoutDeliveryDiscountstoreTotal,'price_without_delivery' =>$checkStore[$key]->totalPrice, 'total_products_mrp'=>$checkStore[$key]->totalMrp, 'payment_method'=>'razorpay' ,'paid_by_wallet'=>0, 'rem_price' =>0, 'order_date'=>$currentdate, 'delivery_date'=>$request->deliveydate,'delivery_charge'=>$checkStore[$key]->del_charge, 'order_special_instructions'=>$request->special_instruction,'time_slot'=>$request->timeslot,'delivery_fname'=>$request->ufname, 'delivery_lname'=>$request->ulname, 'delivery_mobile'=>$request->uphone, 'delivery_email'=>$request->uemail, 'delivery_city'=>$request->ucity, 'delivery_landmark'=>$request->landmark,'delivery_type'=>$request->deliveryType,'delivery_type_id'=>$request->delivery_type_id,'delivery_whatsapp_no'=>$request->uphone_whatsapp, 'delivery_address'=>$request->uaddress,'dboy_id'=>0,'order_status'=>'Pending','user_signature'=>'','cancelling_reason'=>'','coupon_id'=>$coupon_code1,'coupon_discount'=>$coupon_amount1,'payment_status'=>'','cancel_by_store'=>'','image'=>'','bulk_order_based_discount'=>$bb,'online_payment_based_discount'=>$oo]

          );

          //$newCharge+= $kk;
         }   
        
      $year= substr(date("Y"),-2);

      $month=date('m');

       $date=date('d');
        
      
       $orderno=$year.$month.$date.'-'.$insorder;

       $deliverySlotData='';  
       if($request->delivery_type_id=='2'){
        $deliverySlotData = "<p><b>Delivery Date: </b>".$request->deliveydate."</p><p style='font-size:14px;color:red'>*You can not cancel the Express Delivery Order</p>";
      }else{
        $deliverySlotData = "<p><b>Delivery Date: </b>".$request->deliveydate."</p>";
        //<p><b>Time Slot: </b>".$request->timeslot."</p>
      }

      $discountAmt = $cartMRPsubtotal-$cartsubtotal;
      $saveAmount = "<p style='font-size:14px;text-align:right'>*You saved Rs. ".$discountAmt." today</p>";
        
        
    $i=1;
    $html='<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; border:1px solid #000;width:500px;" align="center">
    <tr><th colspan="6">ORDER NO :'.' '. $orderno.'</th></tr>
    <tr><th >S.no</th><th>Item</th><th>MRP</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>';
    //$ordertotal=0;
    if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
     foreach ($cartpids['pid'] as $k=>$v){
         $ids[$k]=$k;
         $qty[$k]=$v;
     }
     if(is_array($ids) && count($ids)>0){
        // $products=Product::select('*')->whereIn('id',$ids)->get();
         $products= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product_varient.*','product.product_id as pid')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        //->whereIn('product.product_id',$ids)->get();
        ->whereIn('product_varient.varient_id',$ids)->get();
        //dd();
         
         
         //manipulate order total beg
         foreach($products as $product){
           // $ordertotal+=$product->pprice * $qty[$product->id];
             $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
             $discountprice=$product->mrp*$product->discount_percentage/100;
            // $cartsubtotal+=$price_after_discount *  $qty[$product->pid];
            $html.='<tr><td>'.$i.'</td><td>'.$product->product_name.'</td><td>'.$product->mrp.'</td><td>'.$product->price.'</td><td>'.$qty[$product->varient_id].'</td><td>'.$product->price * $qty[$product->varient_id].'</td></tr>
            ';
            $i++;
         }
         $html.='<tr><td colspan="5">Total</td><td>'.$cartsubtotal.'</td></tr>';
         //manipulate order total end
     }
 }
  $html.='<tr style="text-align: left;"><td colspan="6">'.$saveAmount.'<p><b>Name: </b> '.$request->ufname.' '.$request->ulname .'</p><p><b>Phone: </b>'.$request->uphone.'</p>
        <p><b>Email: </b>'.$request->uemail.'</p><p><b>Address: </b>'.$request->uaddress .','.$request->ucity.'</p>'.$deliverySlotData.'</td></tr></table>';

        $data['html']='<h2 style="text-align:center">Order Description</h2>
        <h3 style="text-align:center">Cart Items</h3>'.$html;
        $data['email']=$request->uemail;
        //$data['name']=$request->ulname;
        $data['name']=$request->ufname.' '.$request->ulname;

        $email = $request->uemail;

 
    //order total end
        /*$i=1;
         $timer = time();
     // $filename = "sfs-".$insorder->id.".csv"; 
      $filename = $orderno.".csv"; 
      $dirorder=public_path()."/order_csv/".$filename;
      //$downloadfile="/images/download/".$filename;
     // $downloadfile="/images/download/".$filename;
        $fhl=fopen($dirorder,"w");
            
            fputcsv($fhl, array('sno'=>'S.No',
            'product'=>'Product',
            'price'=>'Price',
            'qty'=>'Quantity',
            'subtotal'=>'Sub Total',
            ));*/
             $i=1;
        $datescv=date('d-F-Y');
        $timer = time();
     // $filename = "sfs-".$insorder->id.".csv"; 
      $filename = $orderno.".csv"; 
      $dirorder=public_path()."/order_csv/".$filename;
      //$downloadfile="/images/download/".$filename;
     // $downloadfile="/images/download/".$filename;
       $fhl=fopen($dirorder,"w");
            
           /*  fputcsv($fhl, array('sno'=>'S.No',
            'product'=>'Product',
            'price'=>'Price',
            'qty'=>'Quantity',
            'subtotal'=>'Sub Total',
            ));*/
            //new csv format beg
            //to show order no
        fputcsv($fhl, array('bl'=>'',
            'blll'=>'',
            'invoice' =>'Invoice',
            'invoiceno'=>$orderno,
            'blank1' =>'',
            
           
            ));
        fputcsv($fhl, array('bll'=>'',
            'blank1' =>'',
            'blank2' =>'',
            'blank3'=>'Date',
            'date'=>$datescv,
            'blank4'=>'',
          
            ));
        //blank
        fputcsv($fhl, array(''=>'',
            'blank1' =>'',
            'blank2' =>'',
            'blank3'=>'',
            'blank4'=>'',
            'blank4'=>'',
          
            ));

        //
         fputcsv($fhl, array(
        'bl2' =>'',
        'name'=>'Name',
        'b'=>$request->ufname.' '.$request->ulname,
        'a'=>'',
        'c'=>'',
        'd'=>'',
       
           
            ));
         fputcsv($fhl, array(
            'bl3' =>'',
        'address'=>'landmark',
         'b'=>$request->landmark,
        'a'=>'',
        'c'=>'',
        'd'=>'',
            ));
          fputcsv($fhl, array(
            'bl4' =>'',
        'address'=>'Address',
         'b'=>$request->uaddress.', '.$request->ucity,
        'a'=>'',
        'c'=>'',
        'd'=>'',
          /*'b'=>$request->ushippaddr, change by anish*/
            ));


           fputcsv($fhl, array(
        'bl5'=>'',
        'phone'=>'Phone Number',
        'b'=>$request->uphone,
        'a'=>'',
        'c'=>'',
        'd'=>'',
       
           
            ));
            fputcsv($fhl, array(
        'd'=>'',
        'b'=>'',
        'a'=>'',
        'c'=>'',
        'd'=>'',
       
           
            ));
            fputcsv($fhl, array('sno'=>'S.No',
            'product'=>'Items',
            'mrp'=>'MRP',           
            'rate'=>'Rate',
            'quantity'=>'Quantity',
            'amount'=>'Amt(Rs)'
            
            ));

        foreach($products as $product){
           // $subt=$product->pprice * $qty[$product->id];
             $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
             $discountprice=$product->mrp*$product->discount_percentage/100;
            // $cartsubtotal+=$price_after_discount *  $qty[$product->pid];
            //$subt=$price_after_discount * $qty[$product->varient_id];
             $subt=$product->price * $qty[$product->varient_id];
             $price_after_discount=$product->price;
            
        //added code for putcsv beg 
        //added code to save data in store orders beg store_orders
       /* $insoproduct=DB::table('orderproducts')->insert(
            ['product_name'=>$product->product_name,'product_price'=>$price_after_discount,'product_qty'=>$qty[$product->varient_id],'order_id'=>$insorder]

        );*/
        //added new code to insert order products in store orders table beg
        $varient_id=$product->varient_id;
        $order_qty=$qty[$product->varient_id];
        $n=$product->product_name;
        $varient_image=$product->varient_image;
        $quantity=$product->quantity;
        $unit=$product->unit;
        $sub_cart_id = $cart_id.'-STORE-'.$product->product_store_id;
        
        $created_at=date('Y-m-d');
        $price1     =$price_after_discount*$order_qty;
         $total_mrp  = $product->mrp*$order_qty;

        $insert = DB::table('store_orders')
                            ->insertGetId([
                              'varient_id'     => $varient_id,
                              'qty'            => $order_qty,
                              'product_name'   => $n,
                              'varient_image'  => $varient_image,
                              'quantity'       => $quantity,
                              'unit'           => $unit,
                              'total_mrp'      => $total_mrp,
                              'order_cart_id'  => $cart_id,
                              'sub_order_cart_id'=>$sub_cart_id,
                              'order_date'     => $created_at,
                              //'price'          => $price1
                              'actual_price'   => $product->price,
                              'price'          => $price1
                            ]);
        //added new code to insert order products in store orders table end
        //added code to save data in store orders end

        #added code to update product variant stock after order is placed beg
          $getstockdata=DB::table('store_products')
                       ->where('varient_id','=',$varient_id)
                       ->first();

          $currentstock=$getstockdata->stock ?? 0;
          $remainingstock= $currentstock - $order_qty;
          $updatestore=DB::table('store_products')
                       ->where('varient_id','=',$varient_id)
                       ->update(['stock'=>$remainingstock]);
#added code to update product variant stock after order is placed end
       
          
            fputcsv($fhl, array('sno'=>$i,
            'product'=>$product->product_name.'(' .$product->quantity.''.$product->unit.')',
            //'rate'=>$price_after_discount,
            'mrp'=>$product->mrp,
            'rate'=>$product->price,
            'quantity'=>$qty[$product->varient_id],
            'amount'=> $subt
            ));

    
        
        //end end csv
            $i++;

    }  
    /*fputcsv($fhl, array(
        'total'=>'Total',
        'a'=>'',
        'b'=>'',
        'c'=>'',
            'tvalue'=>$request->ordertotal
           
            ));*/
             fputcsv($fhl, array(
        'total'=>'Total',
        'a'=>'',
        'b'=>'',
        'c'=>'',
        'd'=>'',
        'tvalue'=>$request->ordertotal,
           
            ));        

        //fclose($file);            
            fclose($fhl);
           // dd($dirorder);
           // $data['fn']=$filename;
            //dd(env('MAIL_HOST'));
            $emails=array();
            $emails['fl']=public_path()."/order_csv/".$filename;
            $allMails = ['colddrinkteam@gmail.com'];
            //send mail to user and admin
           
            #added code for email beg
             $a=  Mail::send('email.reply', ['data' => $data,'emails' => $emails] , function ($message) use ($email,$emails,$allMails)
        {
            $message->from($email, '');
            $message->subject('Order summary');
            $message->to($allMails);   
            $message->attach($emails['fl']);
        }); 
             #send email from admin to user beg
         Mail::send('email.reply', ['data' => $data,'emails' => $emails], function ($message) use ($email,$emails)
         {
            $message->from('colddrinkteam@gmail.com', '');
            $message->subject('Order summary');
            $message->to($email);
            $message->attach($emails['fl']);
        });
            #added code for email end

         if($ordertotal){
          $getInvitationMsg = urlencode("Dear Customer, your Dealwy order no is ".$cart_id." and the bill amount is ".$ordertotal." and delivery within 48 hours.");
          $apiUrl = 'http://www.onex-ultimo.in/api/pushsms?user=NFDML&authkey=92A7YFf76gJU&sender=Dealwy&mobile='.$request->uphone.'&text='.$getInvitationMsg.'&rpt=1&summary=1&output=json&entityid=1201160517117234073&templateid=1207163298118080010';

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $apiUrl);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $response = curl_exec($ch);
          curl_close($ch);
        }
        

        //added code to insert order data in orders and orderproducts table end
        Session::forget('cartpids');
        Session::forget('totalcartqty');
        //forget coupon session
        Session::forget('coupon_code');
        Session::forget('bulkOrderDiscount');
        Session::forget('coupon_amount');
        Session::forget('delivercharge');
        Session::forget('footer_cart_qty');
        Session::forget('cart_product_list');


     //to insert data in database ,create csv and send mail end
     //save data in database, make csv and send mail and destroy session end
    
   
       return redirect('/checkout')->with('msg','Order Placed Successfully. Check your email');
    } 
    
//nethod to get varient product details of specific varient of product beg
    public function getvarientproduct(Request $request,$productid,$varientid){

        //get varient details of a specific product id and display it in frontend beg
        $product_varient_details= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product_varient.*','product.product_id as pid')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        ->where('product_varient.product_id','=',$productid)
        ->where('product_varient.varient_id','=',$varientid)
        ->first();
        #added code to get variant of stock on the badis of variantid beg
        $stockdata=DB::table('store_products')
                    ->select('stock')
                   ->where('varient_id',$varientid)
                   ->first();
       
        $productimgdefault= DB::table('product')->select('product_image')
            ->where('product.product_id','=',$productid)
            ->first();
        $variantimage=$productimgdefault->product_image;
       // $variantimage='assets/website/img/logo.png';
        if( !empty($product_varient_details->varient_image)){
            $variantimage=$product_varient_details->varient_image;
        }
        //to check if variant iamge exist or not end
   //dd($productid);
     return response()->json(['productid'=>$productid,'varientid'=>$varientid,'product_varient_details'=>$product_varient_details,'variantimage'=>$variantimage,'stock'=>$stockdata->stock ?? 0]); 
    }
//nethod to get varient product details of specific varient of product end
  //added code for about page
  public function about(Request $request){
    $cat= $this->cat;
    $aboutContent=DB::table('pages')
                  ->select('*')
                  ->where('id','=',1)
                  ->first();
      //dd($aboutContent);
       $cat_crousel= $this->cat_crousel;
       $coupondata= $this->coupondata;

    return view('website.about',compact('aboutContent','cat_crousel','coupondata','cat'));
  }
  public function faq(Request $request){

    $cat= $this->cat;
    $content=DB::table('pages')
                  ->select('*')
                  ->where('id','=',3)
                  ->first();
     $cat_crousel= $this->cat_crousel;
      $coupondata= $this->coupondata;
    
    return view('website.faq',compact('cat_crousel','content','coupondata','cat'));
  }
  public function privacy(Request $request){
    $cat= $this->cat;
    $content=DB::table('pages')
                  ->select('*')
                  ->where('id','=',5)
                  ->first();
     $cat_crousel= $this->cat_crousel;
     #to dhow coupon data in header
     $coupondata= $this->coupondata;
    
    return view('website.privacy',compact('cat_crousel','content','coupondata','cat'));
  }

  public function shippingPolicy(Request $request){
    $cat= $this->cat;
    $content=DB::table('pages')
                  ->select('*')
                  ->where('id','=',6)
                  ->first();
     $cat_crousel= $this->cat_crousel;
     #to dhow coupon data in header
     $coupondata= $this->coupondata;
    
    return view('website.shipping_policy',compact('cat_crousel','content','coupondata','cat'));
  }

  public function returnRefund(Request $request){
    $cat= $this->cat;
    $content=DB::table('pages')
                  ->select('*')
                  ->where('id','=',7)
                  ->first();
     $cat_crousel= $this->cat_crousel;
     #to dhow coupon data in header
     $coupondata= $this->coupondata;
    
    return view('website.return_refund',compact('cat_crousel','content','coupondata','cat'));
  }

   #added code for terms and condition beg
  public function terms(Request $request){
    $cat= $this->cat;
    $content=DB::table('termspage')
                  ->first();
    //dd($content);
     $cat_crousel= $this->cat_crousel;
     #to dhow coupon data in header
     $coupondata= $this->coupondata;
    
    return view('website.terms',compact('cat_crousel','content','coupondata','cat'));
  }
  #added code for terms and condition end

  public function contact(Request $request){
    $cat= $this->cat;
    $content=DB::table('pages')
                  ->select('*')
                  ->where('id','=',4)
                  ->first();
     $cat_crousel= $this->cat_crousel;
     $coupondata= $this->coupondata;
    
    return view('website.contact',compact('cat_crousel','content','coupondata','cat'));
  }
 public function blog(Request $request){
   $cat_crousel= $this->cat_crousel;
    
    return view('website.blog',compact('cat_crousel'));
  }
  //added code for about end
  #set coupon beg
  public function set_coupon(Request $request){
  #get user detail beg to limit one time restriction of coupon code
  $bulkOrderDiscount=Session::get('bulkOrderDiscount');

    if(session()->has('userData')) {
      $user_session=Session::get('userData');
      $userId = $user_session->user_id;
      //dd($user_session);
    }
  #get user detail end  

  $storebasedData=json_decode($request->storebasedData);
  $coupon_code=$request->coupon_code;
  $ordertotal=$request->ordertotal;
  $couponLimit = $request->coupon_apply_limit;
  

  // $storebasedData=json_decode('[{"ctitle":"Flour\/Atta","image":"images\/category\/28-05-2021\/Atta1.png","cid":128,"product_id":731,"cat_id":128,"product_store_id":3,"product_name":"chickpeas flour","product_image":"images\/product\/08-06-2021\/15-chickpeas-flour.jpg","status":1,"store_name":"Sai Asmita Store","pcid":128,"varient_id":733,"quantity":1,"unit":"KG\/Gram","mrp":100,"discount_percentage":"0.00","price":100,"description":"chickpeas flour","varient_image":"images\/product\/08-06-2021\/15-chickpeas-flour.jpg","pid":731,"stock":4,"totalPrice":100,"totalMrp":100,"del_charge":20},{"ctitle":"Restaurant","image":"images\/category\/03-06-2021\/resturent.png","cid":124,"product_id":956,"cat_id":124,"product_store_id":5,"product_name":"Fried Rice","product_image":"images\/product\/08-06-2021\/Fried-Rice.jpg","status":1,"store_name":"Vishal Mega Mart","pcid":124,"varient_id":958,"quantity":1,"unit":"KG\/Gram","mrp":100,"discount_percentage":"10.00","price":90,"description":"Fried Rice","varient_image":"images\/product\/08-06-2021\/Fried-Rice.jpg","pid":956,"stock":8,"totalPrice":270,"totalMrp":300,"del_charge":40}]');
  // $coupon_code='KDH3423';
  // $ordertotal=370;
  // $userId=115;
  // $couponLimit=0;
 
  //dd($storebasedData);

  
   //order total before coupon apply
   #user can apply coupon first time only.to chexk if user used coupon code previously beg
 $checkcouponusage=DB::table('orders')
                    ->where('user_id',$userId)
                    ->where('coupon_id',$coupon_code)
                    //->where('coupon_discount','!=',0)
                    ->get();
   
               
  // if(isset($checkcouponusage)){
  //    $msg= 'Sorry! Coupon is only for new users';

  //   return response()->json(['message'=>$msg,'coupon_code'=>$coupon_code,'coupon_amount'=>0,'final_price'=>$ordertotal,'coupon_apply_limit'=>0]);

  // }
  #user can apply coupon first time only.to chexk if user used coupon code previously end
  #get coupon detail
  $coupon_details=DB::table('coupon')
                 // ->where('coupon_code',$coupon_code)
                  ->where(DB::raw("BINARY `coupon_code`"),$coupon_code)
                  //->where('coupon_code','like', '%' .$coupon_code. '%')
                  //->where('coupon_code','like', '%' .$coupon_code. '%')
                  ->first();
  //dd($coupon_details);
  if($bulkOrderDiscount>0){
    return response()->json(['message'=>'Coupon cannot be applied beacuse your order reached bulk order discount','coupon_code'=>$coupon_code,'coupon_amount'=>0,'final_price'=>$ordertotal,'coupon_apply_limit'=>$couponLimit]);
  }                 
  if(isset($checkcouponusage) && count($checkcouponusage)==$coupon_details->uses_restriction){
    return response()->json(['message'=>'Coupon use limit has been exceeded','coupon_code'=>$coupon_code,'coupon_amount'=>0,'final_price'=>$ordertotal,'coupon_apply_limit'=>$couponLimit]);
  }
                 
    //dd($storebasedData);
    if(isset($coupon_details)){


       #compare cart min amount and cart total to check wether amount is more than cart min value or not beg
      $countA=0;
      foreach($storebasedData as $key => $value){
        //dd($coupon_details->coupon_store_id==$storebasedData[$key]->product_store_id);
       if($coupon_details->coupon_store_id==$storebasedData[$key]->product_store_id){
        $countA++;
        //dd($coupon_details->cart_value>$storebasedData[$key]->totalPrice);
         if($coupon_details->cart_value > $storebasedData[$key]->totalPrice){
          $msg= 'Cart Value for one store must be greater than '.$coupon_details->cart_value;
           return response()->json(['message'=>$msg,'coupon_code'=>$coupon_code,'coupon_amount'=>0,'final_price'=>$ordertotal,'coupon_apply_limit'=>$couponLimit]);
         }else{
          
          #apply coupon code
          $coupon_amount=round($coupon_details->amount,2);
          $coupon_type=$coupon_details->type;
          //dd($coupon_type);


            //put in session coupon_code and coupon_discount end
          if($coupon_type == 'percentage'){
            //dd('per');

            $coupon_amount=round(($storebasedData[$key]->totalPrice*$coupon_amount/100),2);
            $final_price=round(($ordertotal-$coupon_amount),2);
                  //put in session coupon_code and coupon_discount beg
            Session::put('coupon_code', $request->coupon_code);
            Session::put('coupon_amount', $coupon_amount);


                  //put in session coupon_code and coupon_discount end
            return response()->json(['message'=>'','coupon_code'=>$coupon_code,'coupon_amount'=>$coupon_amount,'final_price'=>$final_price,'subT'=> $storebasedData,'coupon_apply_limit'=>$couponLimit + 1]);

          }else{
            //dd('price');
            $final_price=$ordertotal-$coupon_amount;
            
            Session::put('coupon_code', $request->coupon_code);
            Session::put('coupon_amount', $coupon_amount);

            return response()->json(['message'=>'','coupon_code'=>$coupon_code,'coupon_amount'=>$coupon_amount,'final_price'=>$final_price,'subT'=> $storebasedData,'coupon_apply_limit'=>$couponLimit + 1]);
          
          }
          Session::put('coupon_code', '');
          Session::put('coupon_amount', 0);
        }
       }else{

       }
       // else{

       //    return response()->json(['message'=>'Coupon not applicable for this order','coupon_code'=>$coupon_code,'coupon_amount'=>0,'final_price'=>$ordertotal,'coupon_apply_limit'=>$couponLimit]);
       // } 
        
      }
      if($countA==0){
        return response()->json(['message'=>'Coupon not applicable for this order','coupon_code'=>$coupon_code,'coupon_amount'=>0,'final_price'=>$ordertotal,'coupon_apply_limit'=>$couponLimit]);

      }  

       #compare cart min amount and cart total to check wether amount is more than cart min value or not end
      
    // return response()->json(['coupon_details'=>$coupon_details]);
    }else{
     
       return response()->json(['message'=>'Please enter valid coupon code','coupon_code'=>$coupon_code,'coupon_amount'=>0,'final_price'=>$ordertotal,'coupon_apply_limit'=>$couponLimit]);
    }
   // return response()->json(['coupon_details'=>$coupon_details]);
  }
  #set coupon end
  #added code for payment with wallet 13 nov beg
  public function paymentbywallet(Request $request){
    //dd($request->all());
    #wallet payment code beg

    $products='';
    $checkStore='';
    $bulkOrderDiscount=0;
    $cat= $this->cat;
    $todayDate = date("Y-m-d");

      //get address of user if session beg
    $getaddress_detail='';
    if(session()->has('userData')){
      $user_session_data=session()->get('userData');
    //dd($user_session_data->user_id);user_id
      $getaddress_detail=DB::table('address')
      ->where('user_id','=',$user_session_data->user_id)
      ->first();
      
    }

    //get coupon               
    $coupons=DB::table('coupon')
                //->whereDate('coupon.start_date', '<=', date('Y-m-d H:i:s'))
                ->whereDate('coupon.end_date', '>=', date('Y-m-d'))
                ->get();
    //Store detail
    $store_detail=DB::table('store')
                           //->where('user_id','=',$user_session_data->user_id)
                           ->first();
    //get address of user if session end

    //added code to show  time slot beg 6 nov 2020
    $current_time   = Carbon::Now();
    $date           = date('Y-m-d');
    $time_slot      = DB::table('time_slot')
                            ->first();
       
    //added code to get time slot beg
    $StartTime=$time_slot->open_hour;
    $EndTime=$time_slot->close_hour;
    $Duration=$time_slot->time_slot;
    $ReturnArray = array ();// Define output
    $StartTime    = strtotime ($StartTime); //Get Timestamp
    $EndTime      = strtotime ($EndTime); //Get Timestamp

    $AddMins  = $Duration * 60;

    while ($StartTime <= $EndTime) //Run loop
    {
        $ReturnArray[] = date ("G:i", $StartTime);
        $StartTime += $AddMins; //Endtime check
    }
   
  //added code to get time slot end
  //added code to show  time slot end 6 nov 2020
    
    $cat_crousel= $this->cat_crousel;
    $cat=$this->cat;
    
   
    $cartsubtotal=0;
    $cartMRPsubtotal=0;
    $productcount=0;
    $cartpids = Session::get('cartpids'); 

    $ordertotal=0;
    $ids=array();
    $qty=array();
    if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
        foreach ($cartpids['pid'] as $k=>$v){
          $ids[$k]=$k;
          $qty[$k]=$v;
        }
    }
      //dd($ids);
    //dd($qty);
    
     if(is_array($ids) && count($ids)>0){
       
          $products= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','store.store_name','product.cat_id as pcid','product_varient.*','product.product_id as pid','store_products.stock')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        #added code to get stock of each items added in cart beg
        ->Leftjoin('store','product.product_store_id','=','store.store_id')
         ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
        #added code to get stock of each items added in cart end
         ->whereIn('product_varient.varient_id',$ids)->get();
       
         $productcount=DB::table('product_varient')->select('*')->whereIn('varient_id',$ids)->count();
         
         //manipulate order total beg
         foreach($products as $product){
            //$ordertotal+=$product->pprice * $qty[$product->id];
            $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
            $discountprice=$product->mrp*$product->discount_percentage/100;
            //$cartsubtotal+=$price_after_discount *  $qty[$product->varient_id];
            $cartsubtotal+=$product->price *  $qty[$product->varient_id];
            $cartMRPsubtotal+=$product->mrp *  $qty[$product->varient_id];
         }
        
        
     }
     
//form submit beg
    $orderno='';
    if(isset($request->paymenttype) && $request->paymenttype == 2 && is_array($ids) && count($ids)>0){ //form post beg


       
        //added code to insert customer detail in user table beg
         #user check beg
         $customercheck=DB::table('users')->select('user_email','user_id','wallet')
         ->where('user_email',$request->uemail)
         ->orWhere('user_phone',$request->uphone)
         ->first();
                 
         //user check end
         if(empty($customercheck)){
          $customerdetails=DB::table('users')
          ->insertGetId(['user_name'=>$request->ufname.' '.$request->ulname,'user_phone'=>$request->uphone,'user_email'=>$request->uemail]);
         }else{
           $customerdetails= $customercheck->user_id;
         }
       //added code to generate cart id beg
       $chars =  "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $val              =  "";

        # random character get for make cart id.
        for ($i = 0; $i < 4; $i++) {
          $val .= $chars[mt_rand(0, strlen($chars)-1)];
        }

        $chars2 = "0123456789";
        $val2 = "";
        # random number get for make cart id. there are 2 digit numbers.
        for ($i = 0; $i < 2; $i++){
          $val2 .= $chars2[mt_rand(0, strlen($chars2)-1)];
        }

        # get microtime.
        $cr      = substr(md5(microtime()),rand(0, 26),2);

        # make cart id with character, number and time strings.
        $cart_id = $val.$val2.$cr;

           $coupon_code='';
            $coupon_amount=0;
            $coupon_store_id='';
            # if coupon applied beg
           if(session()->has('coupon_code') && session()->has('coupon_amount')){
            $coupon_code=Session::get('coupon_code');
            $coupon_amount=Session::get('coupon_amount');
          }
          if($coupon_amount>0){
            $coupon_details=DB::table('coupon')
                  ->where(DB::raw("BINARY `coupon_code`"),$coupon_code)
                  ->first();
            $coupon_store_id =$coupon_details->coupon_store_id;
          }

          $ordertotal=$request->ordertotal-$request->bulkOrderDiscount;
         // dd($ordertotal);
          #get user waallet detail beg
          $userwallet= $customercheck->wallet;
          //dd($userwallet);
          if($userwallet >= $ordertotal){
            #wallet payment if wallet amount is greater than order total beg
             # if coupon applied beg
          #added code to get rewars point update for users on the basis of cart value beg
          #first get user previous reward value beg
         $previous_reward=DB::table('users')
        ->where('user_id','=',$customerdetails)
        ->first();
        
          #first get user previous reward value end
          $select_reward=DB::table('reward_points')
                     ->where('min_cart_value','<=', $ordertotal)
                     ->first();
          if(isset($select_reward)){
             #insert reward points in cart_rewards beg
          $insertrewad=DB::table('cart_rewards')
                       ->insert(['cart_id'=>$cart_id,'rewards'=>$select_reward->reward_point ?? 0,'user_id'=>$customerdetails]);
          #insert reward points in cart_rewards end
            #user rewards on cart min value
            $total_reward_after=$previous_reward->rewards + $select_reward->reward_point;
            $update_user_reward=DB::table('users')
                               ->where('user_id','=',$customerdetails)
                               ->update(['rewards'=>$total_reward_after]);
          }
          
          #added code to get rewars point update for users on the basic of cart value end
       //added code to generate cart id end
          $ordertotal=$ordertotal + $request->delcharge;

          $newWalletAmounttt=$previous_reward->wallet - $ordertotal;
          //dd($ordertotal);
          #get address beg
          //dd($customerdetails);
            $ar      = DB::table('address')
                                  ->select('society','landmark','city','lat','lng','address_id')
                                  ->where('user_id', $customerdetails)
                                  //->where('select_status', 1)
                                  ->first();

         $update_user_reward=DB::table('users')
                               ->where('user_id','=',$customerdetails)
                               ->update(['wallet'=>$newWalletAmounttt]);                         
               // dd($ar);
                if(isset($ar)){
                 $addressid= $ar->address_id;
                }else{
                  $addressid=0;
                }
    #get address beg

        $currentdate=date('Y-m-d');

          if(!isset($request->timeslot)){
             $request->timeslot='';
           }
        $withoutDeliveryDiscountTotal =  $request->ordertotal+$coupon_amount;   
         $insorder=DB::table('orders')->insertGetId(
            ['user_id' =>$customerdetails, 'store_id' =>$store_detail->store_id , 'address_id'=>$addressid, 'cart_id' =>$cart_id,'total_price'=>$ordertotal,'total_price_without_delivery_discount'=>$withoutDeliveryDiscountTotal, 'price_without_delivery' =>$request->ordertotal, 'total_products_mrp'=>$request->ordertotalmrp, 'payment_method'=>'wallet' ,'paid_by_wallet'=>$ordertotal, 'rem_price' =>0, 'order_date'=>$currentdate, 'delivery_date'=>$request->deliveydate,'delivery_charge'=>$request->delcharge, 'order_special_instructions'=>$request->special_instruction,'time_slot'=>$request->timeslot,'dboy_id'=>0,'delivery_fname'=>$request->ufname, 'delivery_lname'=>$request->ulname, 'delivery_mobile'=>$request->uphone, 'delivery_email'=>$request->uemail, 'delivery_city'=>$request->ucity, 'delivery_landmark'=>$request->landmark, 'delivery_type'=>$request->deliveryType,'delivery_type_id'=>$request->delivery_type_id,'delivery_whatsapp_no'=>$request->uphone_whatsapp,'delivery_address'=>$request->uaddress,'order_status'=>'Pending','user_signature'=>'','cancelling_reason'=>'','coupon_id'=>$coupon_code,'coupon_discount'=>$coupon_amount,'payment_status'=>'','cancel_by_store'=>'','image'=>'','bulk_order_based_discount'=>$request->bulkOrderDiscount]

        );
        // dd($insorder);

       $checkStore=array();
        $checka1='';
        $checka2=0;
        $secondCount = 0;

        foreach($products as $key => $value){
          $checka1 = $products[$key]->product_store_id;
          if($checka2!=$checka1){
            $products[$key]->totalPrice =  $products[$key]->price*$qty[$products[$key]->varient_id];
            $products[$key]->totalMrp = $products[$key]->mrp*$qty[$products[$key]->varient_id];
            $checkStore[] = $products[$key];
            $checka2=$checka1;
            $secondCount++;        
          }else{
            $checkStore[$secondCount-1]->totalPrice +=   $products[$key]->price*$qty[$products[$key]->varient_id];
            $checkStore[$secondCount-1]->totalMrp +=   $products[$key]->mrp*$qty[$products[$key]->varient_id];;
          }
        }
        $newCharge=0;
        foreach($checkStore as $key => $value){
          $newDelcharge=DB::table('freedeliverycart_by_store')
            ->where('delivery_store_id',$checkStore[$key]->product_store_id) 
            ->where('min_cart_value','>=',$checkStore[$key]->totalPrice)
            ->first();
          if($newDelcharge){
            $kk = $newDelcharge->del_charge;
            $checkStore[$key]->del_charge=$kk;
          }else{
            $kk = 0;
            $checkStore[$key]->del_charge=$kk;
          }
          $sub_cart_id = $cart_id.'-STORE-'.$checkStore[$key]->product_store_id;
          if($coupon_store_id && $checkStore[$key]->product_store_id==$coupon_store_id){
            $coupon_amount1=$coupon_amount;
            $coupon_code1=$coupon_code;

          }else{
            $coupon_amount1=0;
            $coupon_code1=0;

          }

          $newBulkDiscount=DB::table('bulk_order_discount')
          //->where('bulk_order_store_id',$checkStore[$key]->product_store_id) 
          ->where('bulk_order_min_amount','<=',$checkStore[$key]->totalPrice)
          ->where('bulk_order_max_amount','>=',$checkStore[$key]->totalPrice)
          ->first(); 

          if($newBulkDiscount){
            if($newBulkDiscount->bulk_order_discount_type=='percentage'){
              $bDiscountCharge = $checkStore[$key]->totalPrice*$newBulkDiscount->bulk_order_discount/100;  
            }else{
              $bDiscountCharge =$newBulkDiscount->bulk_order_discount;
            }  
          }else{
            $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
            $maxBDiscountData = DB::table('bulk_order_discount')
            ->where('bulk_order_max_amount',$maxValueAmountLimit)
            ->first();
            if($checkStore[$key]->totalPrice>$maxValueAmountLimit){
              if($maxBDiscountData->bulk_order_discount_type=='percentage'){
                $bDiscountCharge = $checkStore[$key]->totalPrice*$maxBDiscountData->bulk_order_discount/100;  
              }else{
                $bDiscountCharge =$maxBDiscountData->bulk_order_discount;
              }
            }else{
              $bDiscountCharge = 0;
            }
          }

          $subOrderTotal = $checkStore[$key]->totalPrice+$checkStore[$key]->del_charge-$coupon_amount1-$bDiscountCharge;
          $withoutDeliveryDiscountstoreTotal =  $checkStore[$key]->totalPrice;
          
          $ins_sub_order=DB::table('sub_orders')->insertGetId(
            ['user_id' =>$customerdetails, 'store_id' =>$checkStore[$key]->product_store_id, 'address_id'=>$addressid, 'cart_id' =>$cart_id,'sub_order_cart_id' =>$sub_cart_id,'total_price'=>$subOrderTotal, 'total_price_without_delivery_discount'=>$withoutDeliveryDiscountstoreTotal,'price_without_delivery' =>$checkStore[$key]->totalPrice-$coupon_amount1, 'total_products_mrp'=>$checkStore[$key]->totalMrp, 'payment_method'=>'wallet' ,'paid_by_wallet'=>$subOrderTotal, 'rem_price' =>0, 'order_date'=>$currentdate, 'delivery_date'=>$request->deliveydate,'delivery_charge'=>$checkStore[$key]->del_charge, 'order_special_instructions'=>$request->special_instruction,'time_slot'=>$request->timeslot,'delivery_fname'=>$request->ufname, 'delivery_lname'=>$request->ulname, 'delivery_mobile'=>$request->uphone, 'delivery_email'=>$request->uemail, 'delivery_city'=>$request->ucity, 'delivery_landmark'=>$request->landmark,'delivery_type'=>$request->deliveryType,'delivery_type_id'=>$request->delivery_type_id,'delivery_whatsapp_no'=>$request->uphone_whatsapp, 'delivery_address'=>$request->uaddress,'dboy_id'=>0,'order_status'=>'Pending','user_signature'=>'','cancelling_reason'=>'','coupon_id'=>$coupon_code1,'coupon_discount'=>$coupon_amount1,'payment_status'=>'','cancel_by_store'=>'','image'=>'','bulk_order_based_discount'=>$bDiscountCharge]

          );

          //$newCharge+= $kk;
        }  
        
      $year= substr(date("Y"),-2);

      $month=date('m');

       $date=date('d');
        
      
       $orderno=$year.$month.$date.'-'.$insorder;
       $deliverySlotData='';  
       if($request->delivery_type_id=='2'){
        $deliverySlotData = "<p><b>Delivery Date: </b>".$request->deliveydate."</p><p style='font-size:14px;color:red'>*You can not cancel the Express Delivery Order</p>";
      }else{
        $deliverySlotData = "<p><b>Delivery Date: </b>".$request->deliveydate."</p>";
        //<p><b>Time Slot: </b>".$request->timeslot."</p>
      }

       $discountAmt = $cartMRPsubtotal-$cartsubtotal;
       $saveAmount = "<p style='font-size:14px;text-align:right'>*You saved Rs. ".$discountAmt." today</p>";
        
        
    $i=1;
    $html='<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; border:1px solid #000;width:500px;" align="center">
    <tr><th colspan="6">ORDER NO :'.' '. $orderno.'</th></tr>
    <tr><th >S.no</th><th>Item</th><th>MRP</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>';
    //$ordertotal=0;
    if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
     foreach ($cartpids['pid'] as $k=>$v){
         $ids[$k]=$k;
         $qty[$k]=$v;
     }
      if(is_array($ids) && count($ids)>0){
        // $products=Product::select('*')->whereIn('id',$ids)->get();
         $products= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','product.cat_id as pcid','product_varient.*','product.product_id as pid')
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
       // ->whereIn('product.product_id',$ids)->get(); //old
        ->whereIn('product_varient.varient_id',$ids)->get();
        //dd($products);
         
         
         //manipulate order total beg
        //dd($products);
        //dd($qty);
         //manipulate order total beg
         foreach($products as $product){
           // $ordertotal+=$product->pprice * $qty[$product->id];
             $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
             $discountprice=$product->mrp*$product->discount_percentage/100;
            // $cartsubtotal+=$price_after_discount *  $qty[$product->pid];
            
            /*$html.='<tr><td>'.$i.'</td><td>'.$product->product_name.'</td><td>'.$price_after_discount.'</td><td>'.$qty[$product->varient_id].'</td><td>'.$price_after_discount * $qty[$product->varient_id].'</td></tr>';*/

            $html.='<tr><td>'.$i.'</td><td>'.$product->product_name.'</td><td>'.$product->mrp.'</td><td>'.$product->price.'</td><td>'.$qty[$product->varient_id].'</td><td>'.$product->price * $qty[$product->varient_id].'</td></tr>';


            $i++;
         }
         $html.='<tr><td colspan="5">Total</td><td>'.$cartsubtotal.'</td></tr>';
         //manipulate order total end
      }
    }
    $html.='<tr style="text-align: left;"><td colspan="6">'.$saveAmount.'<p><b>Name: </b> '.$request->ufname.' '.$request->ulname .'</p><p><b>Phone: </b>'.$request->uphone.'</p>
        <p><b>Email: </b>'.$request->uemail.'</p><p><b>Address: </b>'.$request->uaddress .','.$request->ucity.'</p>'.$deliverySlotData.'</td></tr></table>';

        $data['html']='<h2 style="text-align:center">Order Description</h2>
        <h3 style="text-align:center">Cart Items</h3>'.$html;
        $data['email']=$request->uemail;
        //$data['name']=$request->ulname;
        $data['name']=$request->ufname.' '.$request->ulname;

        $email = $request->uemail;

 
    //order total end
        $i=1;
        $datescv=date('d-F-Y');
        $timer = time();
     // $filename = "sfs-".$insorder->id.".csv"; 
      $filename = $orderno.".csv"; 
      $dirorder=public_path()."/order_csv/".$filename;
      //$downloadfile="/images/download/".$filename;
     // $downloadfile="/images/download/".$filename;
       $fhl=fopen($dirorder,"w");
            
           /*  fputcsv($fhl, array('sno'=>'S.No',
            'product'=>'Product',
            'price'=>'Price',
            'qty'=>'Quantity',
            'subtotal'=>'Sub Total',
            ));*/
            //new csv format beg
            //to show order no
        fputcsv($fhl, array('bl'=>'',
            'blll'=>'',
            'invoice' =>'Invoice',
            'invoiceno'=>$orderno,
            'blank1' =>'',
            
           
            ));
        fputcsv($fhl, array('bll'=>'',
            'blank1' =>'',
            'blank2' =>'',
            'blank3'=>'Date',
            'date'=>$datescv,
            'blank4'=>'',
          
            ));
        //blank
        fputcsv($fhl, array(''=>'',
            'blank1' =>'',
            'blank2' =>'',
            'blank3'=>'',
            'blank4'=>'',
            'blank4'=>'',
          
            ));

        //
         fputcsv($fhl, array(
        'bl2' =>'',
        'name'=>'Name',
        'b'=>$request->ufname.' '.$request->ulname,
        'a'=>'',
        'c'=>'',
        'd'=>'',
       
           
            ));
          fputcsv($fhl, array(
            'bl3' =>'',
        'address'=>'landmark',
         'b'=>$request->landmark,
        'a'=>'',
        'c'=>'',
        'd'=>'',
            ));
          fputcsv($fhl, array(
            'bl4' =>'',
        'address'=>'Address',
         'b'=>$request->uaddress.', '.$request->ucity,
        'a'=>'',
        'c'=>'',
        'd'=>'',
            ));
           fputcsv($fhl, array(
        'bl5'=>'',
        'phone'=>'Phone Number',
        'b'=>$request->uphone,
        'a'=>'',
        'c'=>'',
        'd'=>'',
       
           
            ));
            fputcsv($fhl, array(
        'd'=>'',
        'b'=>'',
        'a'=>'',
        'c'=>'',
        'd'=>'',
       
           
            ));
            fputcsv($fhl, array('sno'=>'S.No',
            'product'=>'Items',
            'mrp'=>'MRP',
            'rate'=>'Rate',
            'quantity'=>'Quantity',
            'amount'=>'Amt(Rs)'
            
            ));
        //
            //new csv format end

        foreach($products as $product){
           // $subt=$product->pprice * $qty[$product->id];
             $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
             $discountprice=$product->mrp*$product->discount_percentage/100;
            // $cartsubtotal+=$price_after_discount *  $qty[$product->pid];
          //  $subt=$price_after_discount * $qty[$product->pid]; //old
            //$subt=$price_after_discount * $qty[$product->varient_id]; //new
             $subt=$product->price * $qty[$product->varient_id]; //new
            $price_after_discount=$product->price;
        //added code for putcsv beg 
        //added code to save data in store orders beg store_orders
       /* $insoproduct=DB::table('orderproducts')->insert(
            ['product_name'=>$product->product_name,'product_price'=>$price_after_discount,'product_qty'=>$qty[$product->pid],'order_id'=>$insorder]

        );*/
        //added new code to insert order products in store orders table beg
        $varient_id=$product->varient_id;
        $order_qty=$qty[$product->varient_id];
        $n=$product->product_name;
        $varient_image=$product->varient_image;
        $quantity=$product->quantity;
        $unit=$product->unit;
        $sub_cart_id = $cart_id.'-STORE-'.$product->product_store_id;
        
        $created_at=date('Y-m-d');
        $price1     =$price_after_discount*$order_qty;
         $total_mrp  = $product->mrp*$order_qty;

        $insert = DB::table('store_orders')
                            ->insertGetId([
                              'varient_id'     => $varient_id,
                              'qty'            => $order_qty,
                              'product_name'   => $n,
                              'varient_image'  => $varient_image,
                              'quantity'       => $quantity,
                              'unit'           => $unit,
                              'total_mrp'      => $total_mrp,
                              'order_cart_id'  => $cart_id,
                              'order_date'     => $created_at,
                              'sub_order_cart_id'=>$sub_cart_id,
                              //'price'          => $price1
                              'actual_price'   => $product->price,
                              'price'          => $price1
                            ]);
        //added new code to insert order products in store orders table end
        //added code to save data in store orders end
        #added code to update product variant stock after order is placed beg
          $getstockdata=DB::table('store_products')
                       ->where('varient_id','=',$varient_id)
                       ->first();

          $currentstock=$getstockdata->stock ?? 0;
          $remainingstock= $currentstock - $order_qty;
          $updatestore=DB::table('store_products')
                       ->where('varient_id','=',$varient_id)
                       ->update(['stock'=>$remainingstock]);
         #added code to update product variant stock after order is placed end                  
       
            /*fputcsv($fhl, array('sno'=>$i,
            'product'=>$product->product_name,
            'price'=>$price_after_discount,
             'qty'=>$qty[$product->pid],
            'subtotal'=> $subt
            ));*/
            //new beg
            fputcsv($fhl, array('sno'=>$i,
            'product'=>$product->product_name.'(' .$product->quantity.''.$product->unit.')',
            //'rate'=>$price_after_discount,
            'mrp'=>$product->mrp,
            'rate'=>$product->price,
            'quantity'=>$qty[$product->varient_id],
            'amount'=> $subt
            ));
            //new end

    
        
        //end end csv
            $i++;

        }  
        /*fputcsv($fhl, array(
        'total'=>'Total',
        'a'=>'',
        'b'=>'',
        'c'=>'',
            'tvalue'=>$request->ordertotal
           
            ));   */ 
            //new beg
            fputcsv($fhl, array(
        'total'=>'Total',
        'a'=>'',
        'b'=>'',
        'c'=>'',
        'd'=>'',
        'tvalue'=>$request->ordertotal,
           
            ));   
            //new end   

        //fclose($file);            
            fclose($fhl);
           // dd($dirorder);
           // $data['fn']=$filename;
            //dd(env('MAIL_HOST'));
            $emails=array();
            $emails['fl']=public_path()."/order_csv/".$filename;
            $allMails = [$email,'colddrinkteam@gmail.com']; 
            //Email credentials:-colddrinkteam@gmail.com
           
        #added code for email beg
             $a=  Mail::send('email.reply', ['data' => $data,'emails' => $emails] , function ($message) use ($email,$emails,$allMails)
        {

            $message->from($email, '');
            $message->subject('Order summary');
           
            
            $message->to($allMails);   
            
            $message->attach($emails['fl']);
          
        
        }); 
             #send email from admin to user beg
         Mail::send('email.reply', ['data' => $data,'emails' => $emails], function ($message) use ($email,$emails)
         {

            $message->from('colddrinkteam@gmail.com', '');
            $message->subject('Order summary');
            $message->to($email);
            $message->attach($emails['fl']);
           

        });
            #added code for email end

        #update wallet of user after payment beg

         $rem_wallet = $customercheck->wallet-$ordertotal;
          //dd($ordertotal);
         $walupdate = DB::table('users')
                   ->where('user_email',$request->uemail)
              ->orWhere('user_phone',$request->uphone)
                   ->update(['wallet'=>$rem_wallet]);
        #update wallet of user after payment end
        if($ordertotal){
          $getInvitationMsg = urlencode("Dear Customer, your Dealwy order no is ".$cart_id." and the bill amount is ".$ordertotal." and delivery within 48 hours.");
          $apiUrl = 'http://www.onex-ultimo.in/api/pushsms?user=NFDML&authkey=92A7YFf76gJU&sender=Dealwy&mobile='.$request->uphone.'&text='.$getInvitationMsg.'&rpt=1&summary=1&output=json&entityid=1201160517117234073&templateid=1207163298118080010';

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $apiUrl);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $response = curl_exec($ch);
          curl_close($ch);
        }           



        //added code to insert order data in orders and orderproducts table end
        Session::forget('cartpids');
        Session::forget('totalcartqty');
        //forget coupon session
        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('bulkOrderDiscount');
        Session::forget('delivercharge');
        Session::forget('footer_cart_qty');
        Session::forget('cart_product_list');

        return redirect('/checkout')->with('msg','Order Placed Successfully. Check Your Email');
            #wallet payment if wallet amount is greater than order total end
      }else{
            return redirect('/checkout')->with('message','Insufficient balance. Please choose cash on delivery or Online Payment');
      }
          #get user waallet detail end


         
    } //form post end 
    
    //form end

    #wallet payment code end

  }
  #added code for payment with wallet 13 nov end
  #added code for contact us beg
  public function contactus(Request $request){
  
    if(isset($request->submit)){
      $insert_contact=DB::table('contact_us')
                      ->insert(['name' =>$request->fullname,'mobile'=>$request->phone,'email'=>$request->email,'message'=>$request->message]);
        #send message to admin beg

             $data['html']='<p>Query send by user :- </p><br><p><p>Name : '.$request->fullname.'</p><br><p>Message : '.$request->message.'</p>';
          
             $data['name']=$request->fullname;
             $data['email']=$request->email;
             $data['emailresponse']='<p>Your Query submittted succesfully. We will get you in touch soon</p>';
              $allMails = ['colddrinkteam@gmail.com']; 
    
      #code for email send to admin
       if($data['email']){
        $a= Mail::send('email.contact_reply', ['data'=>$data], function ($message) use ($data,$allMails) {
            $message->to($allMails, '')->subject
            ('Feedback');
            $message->from( $data['email'],  $data['name']);
            $message->setBody($data['html'],'text/html');
        });

         
        #code for email send to user
        Mail::send('email.contact_response_reply', ['data'=>$data], function ($message) use ($data) {
            $message->to( $data['email'], '')->subject
            ('Feedback');
            $message->from('colddrinkteam@gmail.com','');
            $message->setBody( $data['emailresponse'],'text/html');
        });

       }       
     
        //return redirect('/contact')->with('message','Your Query Submitted Successfully');
        return redirect()->back()->with('message','Your Query Submitted Successfully');

        #send message to admin end
    }

  }
  #added code for contact us end
 

  # new added code to update cart ie increse decrese item if qty is less than stock beg
   public function updatecart(Request $request,$id,$type){
    #added code to get old productid to dusable container of thtat product beg

    $oldproductid=DB::table('product_varient')
                    ->where('varient_id',$id)
                    ->first();
    $stockById=DB::table('store_products')
                    ->where('varient_id',$id)
                    ->first();
    $bulkOrderDiscount=0;                
    Session::forget('coupon_code');
    Session::forget('coupon_amount');                                
    #added code to get old productid to dusable container of thtat product end

    $cartsubtotal=0;
    $cartMrpsubtotal=0;
    $pid=$id;
    #added code to get price of particular product id which is to be update either inc or dec beg
    $getpriceofvarient=DB::table('product_varient')
                       ->select('product_varient.price','store_products.stock')
                       ->join('store_products','product_varient.varient_id','=','store_products.varient_id')
                       ->where('product_varient.varient_id',$id)
                       ->first();
    #added code to get price of particular product id which is to be update either inc or dec end
    $tempids=array();
    $ids=array();
    $qty=array();
    $cartpids = Session::get('cartpids'); //get  session
    
     // dd($cartpids);
     #dec code beg
   if($cartpids) {
        # restriction of stock cartpids['pid'][$id]
   //if($getpriceofvarient->stock >=  $cartpids['pid'][$id]){
   // if($cartpids['pid'][$id] <= $getpriceofvarient->stock -1){

        if(!empty($type) && $type == 'dec'){
          $checkStore=array();
          $checka1='';
          $checka2=0;
          $secondCount = 0;
        
          foreach($cartpids['pid'] as $k=>$v){
               if($pid==$k && $v > 1){
              // if($pid==$k && $v > 1){
                  $tempids['pid'][$k]=$v-1;
                  
               }else{
                $tempids['pid'][$k]=$v;
               }
          }
          #put data in session afer dec beg
          Session::put('cartpids', $tempids); 
          $cartpids = Session::get('cartpids'); 
          #added to update session data on page reload beg
          if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
                 foreach ($cartpids['pid'] as $k=>$v){
                     $ids[$k]=$k;
                     $qty[$k]=$v;
                    
                 }
            }
          Session::put('footer_cart_qty', $qty);
          #added to update session data on page reload end
          #added code to get deliveycharge on the basis of cart total beg
          if(is_array($ids) && count($ids)>0){
       
        $products= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','store.store_id','store.store_name','product.cat_id as pcid','product_varient.*','product.product_id as pid','store_products.stock')
        
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        #added code to get stock of each items added in cart beg
         ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
         ->Leftjoin('store','product.product_store_id','=','store.store_id')
        #added code to get stock of each items added in cart end
        ->whereIn('product_varient.varient_id',$ids)->get(); 
 }

      foreach($products as $product) {
        $cartsubtotal+=$product->price *  $qty[$product->varient_id];
        $cartMrpsubtotal+=$product->mrp *  $qty[$product->varient_id];
            $product->totalPrice = $product->price *  $qty[$product->varient_id];
            $product->totalMrp = $product->mrp *  $qty[$product->varient_id];
   
    }

    foreach($products as $key => $value){    
              $checka1 = $products[$key]->product_store_id;
              if($checka2!=$checka1){
                $checkStore[] = $products[$key];
                $checka2=$checka1;
                $secondCount++;
              }else{
                $checkStore[$secondCount-1]->totalPrice +=   $products[$key]->totalPrice;
                $checkStore[$secondCount-1]->totalMrp +=   $products[$key]->totalMrp;
              }
          }
          $newCharge=0;
          foreach($checkStore as $checkStores){
            $newDelcharge=DB::table('freedeliverycart_by_store')
              ->where('delivery_store_id',$checkStores->product_store_id) 
              ->where('min_cart_value','>=',$checkStores->totalPrice)
              ->first();
            if($newDelcharge){
              $kk = $newDelcharge->del_charge;
            }else{
              $kk = 0;
            }
            $newCharge+= $kk;


            $newBulkDiscount=DB::table('bulk_order_discount')
            //->where('bulk_order_store_id',$checkStores->product_store_id) 
            ->where('bulk_order_min_amount','<=',$checkStores->totalPrice)
            ->where('bulk_order_max_amount','>=',$checkStores->totalPrice)
            ->first(); 

            if($newBulkDiscount){
              if($newBulkDiscount->bulk_order_discount_type=='percentage'){
                $bb = $checkStores->totalPrice*$newBulkDiscount->bulk_order_discount/100;  
              }else{
                $bb =$newBulkDiscount->bulk_order_discount;
              }  
            }else{
              $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
              $maxBDiscountData = DB::table('bulk_order_discount')
              ->where('bulk_order_max_amount',$maxValueAmountLimit)
              ->first();
              if($checkStores->totalPrice>$maxValueAmountLimit){
                if($maxBDiscountData->bulk_order_discount_type=='percentage'){
                  $bb = $checkStores->totalPrice*$maxBDiscountData->bulk_order_discount/100;  
                }else{
                  $bb =$maxBDiscountData->bulk_order_discount;
                }
              }else{
                $bb = 0;
              }
            }

            $bulkOrderDiscount+= $bb;
          }


          #new code to get delivery charge beg
         $delcharge=DB::table('freedeliverycart')
                    ->where('min_cart_value','<=',$cartsubtotal)
                    ->orderby('id', 'desc')
                    ->first();
           $charge=0;
         if(isset($delcharge)){
          $charge=$delcharge->del_charge;
         }
         $bulkOrderDiscount=round($bulkOrderDiscount,2);
         Session::put('delivercharge', $newCharge);
         Session::put('bulkOrderDiscount', $bulkOrderDiscount); 
         $delivercharge=Session::get('delivercharge'); 
         $cartsubtotal=round($cartsubtotal,2);       

          #new code to get delivery charge end
          #added code to get deliveycharge on the basis of cart total end

        
         $store_id = $stockById->store_id;
         $product_id = $oldproductid->product_id;


            $discount = DB::table('discounts')->select('*')
              ->where('store_id',$store_id)
              ->where('product_id',$product_id)
              ->where('min',  '<=', $cartpids['pid'][$id])
              ->where('max', '>=', $cartpids['pid'][$id])
              ->first();
            if ($discount){
               $discountNumber = $discount->discount;
            }else{
               $discountNumber = null;
            }


          
          return response()->json(['status'=>1,'oldStock'=>$stockById->stock,'checkStoreArray'=>$checkStore,'cartdata' =>$cartpids,'productid'=>$id,'updatedQty'=>$cartpids['pid'][$id],'type'=>'dec','productprice'=>round($getpriceofvarient->price,2),'delivercharge'=>$newCharge,'cartsubtotal'=>$cartsubtotal,'cartMrpsubtotal'=>$cartMrpsubtotal,'oldproductid'=>$oldproductid->product_id,'varientstock'=>$getpriceofvarient->stock,'bulkOrderDiscount'=>$bulkOrderDiscount, 'discount'=>$discountNumber]);
          #put data in session afer dec end

        }
       #dec code end
      #added code for increase qty beg

      if(!empty($type) && $type == 'inc'){
        $checkStore=array();
        $checka1='';
        $checka2=0;
        $secondCount = 0;
        if($cartpids['pid'][$id] <= $getpriceofvarient->stock -1){
            
              foreach($cartpids['pid'] as $k=>$v){
                   if($pid==$k){
                      $tempids['pid'][$k]=$v+1;
                      
                   }else{
                     $tempids['pid'][$k]=$v;
                   }
              }
              #put data in session afer dec beg
              Session::put('cartpids', $tempids); 
              $cartpids = Session::get('cartpids'); 
               #added to update session data on page reload beg
          if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
                 foreach ($cartpids['pid'] as $k=>$v){
                     $ids[$k]=$k;
                     $qty[$k]=$v;
                    
                 }
            }
          Session::put('footer_cart_qty', $qty);
          #added to update session data on page reload end
           #added code to get deliveycharge on the basis of cart total beg
          if(is_array($ids) && count($ids)>0){
       
        $products= DB::table('product')->select('categories.title as ctitle','categories.image','categories.cat_id as cid','product.*','store.store_id','store.store_name','product.cat_id as pcid','product_varient.*','product.product_id as pid','store_products.stock')
        
        ->join('categories','product.cat_id','=','categories.cat_id')
        ->join('product_varient','product_varient.product_id','=','product.product_id')
        #added code to get stock of each items added in cart beg
         ->Leftjoin('store_products','product_varient.varient_id','=','store_products.varient_id')
         ->Leftjoin('store','product.product_store_id','=','store.store_id')
        #added code to get stock of each items added in cart end
        ->whereIn('product_varient.varient_id',$ids)->get(); 
 }

      foreach($products as $product) {
         $cartsubtotal+=$product->price *  $qty[$product->varient_id];
         $cartMrpsubtotal+=$product->mrp *  $qty[$product->varient_id];
            $product->totalPrice = $product->price *  $qty[$product->varient_id];
            $product->totalMrp = $product->mrp *  $qty[$product->varient_id];
   
        }
        foreach($products as $key => $value){    
              $checka1 = $products[$key]->product_store_id;
              if($checka2!=$checka1){
                $checkStore[] = $products[$key];
                $checka2=$checka1;
                $secondCount++;
              }else{
                $checkStore[$secondCount-1]->totalPrice +=   $products[$key]->totalPrice;
                $checkStore[$secondCount-1]->totalMrp +=   $products[$key]->totalMrp;
              }
          }
          $newCharge=0;
          foreach($checkStore as $checkStores){
            $newDelcharge=DB::table('freedeliverycart_by_store')
              ->where('delivery_store_id',$checkStores->product_store_id) 
              ->where('min_cart_value','>=',$checkStores->totalPrice)
              ->first();
            if($newDelcharge){
              $kk = $newDelcharge->del_charge;
            }else{
              $kk = 0;
            }
            $newCharge+= $kk;

            $newBulkDiscount=DB::table('bulk_order_discount')
            //->where('bulk_order_store_id',$checkStores->product_store_id) 
            ->where('bulk_order_min_amount','<=',$checkStores->totalPrice)
            ->where('bulk_order_max_amount','>=',$checkStores->totalPrice)
            ->first(); 

            if($newBulkDiscount){
              if($newBulkDiscount->bulk_order_discount_type=='percentage'){
                $bb = $checkStores->totalPrice*$newBulkDiscount->bulk_order_discount/100;  
              }else{
                $bb =$newBulkDiscount->bulk_order_discount;
              }  
            }else{
              $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
              $maxBDiscountData = DB::table('bulk_order_discount')
              ->where('bulk_order_max_amount',$maxValueAmountLimit)
              ->first();
              if($checkStores->totalPrice>$maxValueAmountLimit){
                if($maxBDiscountData->bulk_order_discount_type=='percentage'){
                  $bb = $checkStores->totalPrice*$maxBDiscountData->bulk_order_discount/100;  
                }else{
                  $bb =$maxBDiscountData->bulk_order_discount;
                }
              }else{
                $bb = 0;
              }
            }

            $bulkOrderDiscount+= $bb;
          }
          //dd($bulkOrderDiscount);

      #new code to get delivery charge beg
         $delcharge=DB::table('freedeliverycart')
                    ->where('min_cart_value','<=',$cartsubtotal)
                    ->orderby('id', 'desc')
                    ->first();
           $charge=0;
         if(isset($delcharge)){
          $charge=$delcharge->del_charge;
         }
         $bulkOrderDiscount=round($bulkOrderDiscount,2);
         Session::put('delivercharge', $newCharge); 
         Session::put('bulkOrderDiscount', $bulkOrderDiscount);
         $delivercharge=Session::get('delivercharge'); 
         $cartsubtotal=round($cartsubtotal,2);

          #new code to get delivery charge end
          #added code to get deliveycharge on the basis of cart total end

        
             
         $store_id = $stockById->store_id;
         $product_id = $oldproductid->product_id;


            $discount = DB::table('discounts')->select('*')
              ->where('store_id',$store_id)
              ->where('product_id',$product_id)
              ->where('min',  '<=', $cartpids['pid'][$id])
              ->where('max', '>=', $cartpids['pid'][$id])
              ->first();
            if ($discount){
               $discountNumber = $discount->discount;
            }else{
               $discountNumber = null;
            }   
            

              return response()->json(['status'=>1,'oldStock'=>$stockById->stock,'checkStoreArray'=>$checkStore,'cartdata' =>$cartpids,'productid'=>$id,'updatedQty'=>$cartpids['pid'][$id],'type'=>'inc','productprice'=>round($getpriceofvarient->price,2),'delivercharge'=>$newCharge,'cartsubtotal'=>$cartsubtotal,'cartMrpsubtotal'=>$cartMrpsubtotal,'oldproductid'=>$oldproductid->product_id,'varientstock'=>$getpriceofvarient->stock,'bulkOrderDiscount'=>$bulkOrderDiscount, 'discount'=>$discountNumber]);
              #put data in session afer dec end
            }// stock condition end
        }

    

    } //if set cartpids session
  }
  # new added code to update cart ie increse decrese item if qty is less than stock end

  #added co to get current user location  beg


    public function getCurrentLocation(Request $request)
    {

      $latitude = $request->latitude;
      $longitude = $request->longitude;

      $mapapi = DB::table('map_api')
                 ->first();
                 
      $key = $mapapi->map_api_key;
      //$key = 'AIzaSyAZbvJlKvTEKfrZmu5xdXhNdA6rwzDL5E8';

      # Set lat long
      $latlong = $latitude.','.$longitude;

      # google map geocode api url
      $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latlong}&key=$key";

      $json = file_get_contents($url);
      $resp = json_decode($json, true);
      //dd($resp);
      $__city_name = '';
      $__state_name = '';
      $__zip_name = '';
      $__address = '';

      $__address = $resp['results'][0]['formatted_address'];
      //$__city_name=$__address;
      $value=explode(" ", $__address);
      $countC = count($value);
      $__city_name=trim($value[$countC-3]);

      //$__city_name = $resp['results'][0]['address_components'][2]['long_name'];
      //$__state_name = $resp['results'][0]['address_components'][5]['long_name'];
      //$__zip_name = $resp['results'][0]['address_components'][7]['long_name'];

      if(!isset($resp['error_message']))
      {
        //$__city_name = $resp['results'][0]['address_components'][2]['long_name'];
        $__address = $resp['results'][0]['formatted_address'];
        $value=explode(",", $__address);
        $countC = count($value);
        $__city_name=trim($value[$countC-3]);
        //$__state_name = $resp['results'][0]['address_components'][5]['long_name'];
        //$__zip_name = $resp['results'][0]['address_components'][7]['long_name'];

        //$__final_url = $__city_name .', '. $__state_name. ", " .$__zip_name;
      }else
      {
        $__city_name='Delhi';
        //$__final_url = 'Atlanta, GA';
      }
      Session::put('visitor_city', $__city_name);
      Session::put('visitor_lat', $latitude);
      Session::put('visitor_lng', $longitude);
      Session::put('visitor_range', 30);
      //Session::put('visitor_state', $__state_name);
      //Session::put('visitor_pincode', $__zip_name);
      return response()->json(['visitor_city'=>$__city_name,'visitor_state'=>$__state_name,'pincode'=>$__zip_name,'complete'=> $resp['results'][0]]);
    }
    #added co to get current user location  end


     public function changePincode(Request $request)
    {

      $pincode = $request->pincodesearch;
      //dd($pincode);

      Session::put('visitor_pincode', $pincode);

      return response()->json(['visitor_pincode'=>$pincode]);
    }
    #added co to get current user location  end


    //added code check all discount & delivery charges
   public function checkOnlineDiscount(Request $request){
        $data_array=json_decode($request->storebasedData);
        $ordertotal=$request->ordertotal;
        
        try {

          $totalDeliveryCharge =0;
          $bulkOrderDiscount =0;
          $onlineOrderDiscount=0;

          foreach ($data_array as $sh){
            //dd($sh);
            $delcharge = DB::table('freedeliverycart_by_store')
                         ->where('delivery_store_id', $sh->product_store_id)
                         ->where('min_cart_value','>=', $sh->totalPrice)
                         ->first();
            if($delcharge){
              $newCharge = $delcharge->del_charge;
            }else{
              $newCharge = 0;
            }
            $totalDeliveryCharge +=   $newCharge;


            $newBulkDiscount=DB::table('bulk_order_discount')
                  ->where('bulk_order_min_amount','<=',$sh->totalPrice)
                  ->where('bulk_order_max_amount','>=',$sh->totalPrice)
                  ->first();

            if($newBulkDiscount){
              if($newBulkDiscount->bulk_order_discount_type=='percentage'){
                $bb = $sh->totalPrice*$newBulkDiscount->bulk_order_discount/100;  
              }else{
                $bb =$newBulkDiscount->bulk_order_discount;
              }  
            }else{
              $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
              $maxBDiscountData = DB::table('bulk_order_discount')
              ->where('bulk_order_max_amount',$maxValueAmountLimit)
              ->first();
              if($sh->totalPrice>$maxValueAmountLimit){
                if($maxBDiscountData->bulk_order_discount_type=='percentage'){
                  $bb = $sh->totalPrice*$maxBDiscountData->bulk_order_discount/100;  
                }else{
                  $bb =$maxBDiscountData->bulk_order_discount;
                }
              }else{
                $bb = 0;
              }
            }

            $bulkOrderDiscount+= $bb;

            $letNewTotal= $sh->totalPrice-$bb+$newCharge;
            $newOnlineDiscount=DB::table('online_payment_discount') 
                  ->where('online_payment_min_amount','<=',$letNewTotal)
                  ->where('online_payment_max_amount','>=',$letNewTotal)
                  ->first();
            if($newOnlineDiscount){
              if($newOnlineDiscount->online_payment_discount_type=='percentage'){
                $oo = ($letNewTotal)*$newOnlineDiscount->online_payment_discount/100;  
              }else{
                $oo =$newOnlineDiscount->online_payment_discount;
              }  
            }else{
              $maxValueAmountLimit = DB::table('online_payment_discount')->max('online_payment_max_amount');
              $maxOnlineDiscountData = DB::table('online_payment_discount')
              ->where('online_payment_max_amount',$maxValueAmountLimit)
              ->first();
              if($letNewTotal>$maxValueAmountLimit){
                if($maxOnlineDiscountData->online_payment_discount_type=='percentage'){
                  $oo = ($letNewTotal)*$maxOnlineDiscountData->online_payment_discount/100;  
                }else{
                  $oo =$maxOnlineDiscountData->online_payment_discount;
                }
              }else{
                $oo = 0;
              }
            }

            $onlineOrderDiscount+= $oo;
          }
            
          $data=array('delivery_charge'=>round($totalDeliveryCharge,2),'bulk_order_discount'=>number_format($bulkOrderDiscount,2), 'online_payment_discount'=>number_format($onlineOrderDiscount,2));

          $OnlinePay = $ordertotal+round($totalDeliveryCharge,2)-round($bulkOrderDiscount,2)-round($onlineOrderDiscount,2);
        
          $message = array('status' => '1', 'total' => $ordertotal, 'onlinetotal' => number_format($OnlinePay,2), 'delivery_charge' => round($totalDeliveryCharge,2), 'bulk_order_discount' => number_format($bulkOrderDiscount,2), 'online_payment_discount' => number_format($onlineOrderDiscount,2));

          return $message;
        } catch (\Exception $e) {
            
            $message = array('status'=>'0', 'total' => $ordertotal,'onlinetotal' => $ordertotal, 'delivery_charge' => 0, 'bulk_order_discount' => 0, 'online_payment_discount' => 0);
            return $message;
        }
    }
  
}
?>

