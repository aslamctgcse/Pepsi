<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    public function st_product_list(Request $request)
    {
          
        $title = "Product List";
        $email = Session::get('bamaStore');
        $store= DB::table('store')
                   ->where('email',$email)
                   ->first();

        $logo = DB::table('tbl_web_setting')
            ->where('set_id', '1')
            ->first();
           $storeproducts = DB::table('product AS productss')
                    ->join('categories as cat','productss.cat_id','=','cat.cat_id')
                     ->join('product_varient as prodVarient','prodVarient.product_id','=','productss.product_id')
                     ->join('store as storesList','storesList.store_id','=','productss.product_store_id')
                   //->paginate(10); //old
                   ->select('productss.product_id as product_id','productss.product_image as product_image','productss.status as status','productss.product_approved_status as approved','prodVarient.mrp as mrp','prodVarient.price as price','prodVarient.quantity as quantity','prodVarient.unit as unit','productss.product_name as product_name', 'cat.title as title', 'storesList.store_name as storeName')
                   ->where('productss.product_store_id', $store->store_id)
                   ->orderBy('productss.product_id', 'DESC') 
                   ->get();         
        return view('store.products.product_list', compact('title',"store", "logo","storeproducts"));
    }

   // Discount Product list 
   public function discount_product(Request $request){
        $title = "Product List";
        $email = Session::get('bamaStore');
        $store= DB::table('store')->where('email',$email)->first();
        $logo = DB::table('tbl_web_setting')->where('set_id', '1')->first();

         $storeproducts = DB::table('product AS productss')
                    ->join('categories as cat','productss.cat_id','=','cat.cat_id')
                     ->join('product_varient as prodVarient','prodVarient.product_id','=','productss.product_id')
                     ->join('store as storesList','storesList.store_id','=','productss.product_store_id')
                   //->paginate(10); //old
                   ->select('productss.product_id as product_id','productss.product_image as product_image','productss.status as status','productss.product_approved_status as approved','prodVarient.mrp as mrp','prodVarient.price as price','prodVarient.quantity as quantity','prodVarient.unit as unit','productss.product_name as product_name', 'cat.title as title', 'storesList.store_name as storeName')
                   ->where('productss.product_store_id', $store->store_id)->orderBy('productss.product_id', 'DESC')->get();        
        return view('store.products.product_discount', compact('title',"store", "logo","storeproducts"));
    }
   
   public function single_discount_product(Request $request){ 

      $title = "Product List";
        $email = Session::get('bamaStore');
        $store= DB::table('store')
                   ->where('email',$email)
                   ->first();

        $logo = DB::table('tbl_web_setting')
            ->where('set_id', '1')
            ->first();

       $storeproducts = DB::table('product AS productss')
              ->join('categories as cat','productss.cat_id','=','cat.cat_id')
               ->join('product_varient as prodVarient','prodVarient.product_id','=','productss.product_id')
               ->join('store as storesList','storesList.store_id','=','productss.product_store_id')
             //->paginate(10); //old
             ->select('productss.product_id as product_id','productss.product_image as product_image','productss.status as status','productss.product_approved_status as approved','prodVarient.mrp as mrp','prodVarient.price as price','prodVarient.quantity as quantity','prodVarient.unit as unit','productss.product_name as product_name', 'cat.title as title', 'storesList.store_name as storeName')
             ->where('productss.product_store_id', $store->store_id)->orderBy('productss.product_id', 'DESC')->get();        

         $productName = DB::table('product')->where('product_id', $request->id)->first();
      
           $singleDiscount = DB::table('discounts')->where('store_id', $store->store_id)->where('product_id', $request->id)
                   ->orderBy('product_id', 'asc')->get();
        
        return view('store.products.product_discount', compact('title',"store", "logo","storeproducts", "productName", "singleDiscount"));

   }

   public function addDiscount(Request $request){

      $validator = Validator::make($request->all(),[
         'store_id'=>"required",
         'product_id'=>"required",
         'min'=>"required|numeric",
         'max'=>"required|numeric|gt:min",
         'discount'=>"required|numeric"
      ]);

      if($validator->fails()) {
         $messages = $validator->messages();       
         return Redirect::back()->withErrors($validator);
      }
      
      DB::table('discounts')
         ->insert([
            'store_id'    => $request->store_id,
            'product_id'    => $request->product_id,
            'min'    => $request->min,
            'max'    => $request->max,
            'discount'    => $request->discount
      ]);
      return redirect()->back()->withSuccess('Discount Added Successfully');
   }

   public function deleteDiscount(Request $request){
      
      DB::table('discounts')->where('id', $request->id)->delete();         
      return redirect()->back()->withSuccess('Discount delete Successfully');
   }
    

    public function st_addProduct(Request $request)
    {
      
          
        # set title of product fro show the create page
        $title = "Add Store Product";

        # get login user email id.
        $email = Session::get('bamaStore');

        # get login user information.
        $store= DB::table('store')
                   ->where('email',$email)
                   ->first();          

        # get logo of admin panel.
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();

        # all child categories from category.
        $cat = DB::table('categories')
                   ->select('parent')
                   ->where('status', 1)
                   ->get();          
                   
        if(count($cat)>0) {           
            foreach($cat as $cats) {
                $a = $cats->parent;
               $aa[] = array($a); 
            }
        } else {
            $a = 0;
            $aa[] = array($a);
        }
        
        # all parent category.
        /*$category = DB::table('categories')
                    ->where('level', '!=', 0)
                    ->WhereNotIn('cat_id', $aa)
                    ->where('status', 1)
                    ->get();*/ //old
                    $category = DB::table('categories')
                   ->select('*')
                   ->where('status', 1)
                   ->get();
                  // dd($category);

   
        # view to create page
        return view('store.products.product_add', compact("category", "email", "logo", "store", "title"));
     }


    public function st_addBulkProduct(Request $request)
    {
        if ($request->hasFile('products_csv')) 
        {

            Excel::import(new ImportProduct, request()->file('products_csv'));
            return redirect()->back();

        } else {
            # view create page with error
            return redirect()->back()->withErrors("Please select file");
        }  
   } 
    
    public function st_addNewProduct(Request $request)
    {

         # get login user email id.
        $email = Session::get('bamaStore');

        # get login user information.
        $store= DB::table('store')
                   ->where('email',$email)
                   ->first();          


        # Gather the form data
        $category_id        = $request->cat_id;
        $product_name       = $request->product_name;
        $quantity           = $request->quantity;
        $unit               = $request->unit;
        $price              = $request->price;
        $description        = $request->description;
        $date               = date('d-m-Y');
        $mrp                = round($request->mrp);
        $discountPercentage = $request->discount_percentage;
        $store_id            = $store->store_id;

        $this->validate(
            $request,
                [
                    'cat_id'        => 'required',
                    'product_name'  => 'required',
                    'product_image' => 'required|mimes:jpeg,png,jpg|max:1000',
                    'quantity'      => 'required',
                    'unit'          => 'required',
                    'price'         => 'required',
                    'mrp'           => 'required',
                ],
                [
                    'cat_id.required'        => 'Select category',
                    'product_name.required'  => 'Enter product name.',
                    'product_image.required' => 'Choose product image.',
                    'quantity.required'      => 'Enter quantity.',
                    'unit.required'          => 'Choose unit.',
                    'price.required'         => 'Enter price.',
                    'mrp.required'           => 'Enter MRP.',
                ]
        );

        # upload product image
        if($request->hasFile('product_image')){
            $product_image  = $request->product_image;
            $fileName       = $product_image->getClientOriginalName();
            $fileName       = str_replace(" ", "-", $fileName);
            $product_image->move('admin-panel/images/product/'.$date.'/', $fileName);
            $product_image = 'images/product/'.$date.'/'.$fileName;
        } else {
            $category_image = 'N/A';
        }

        # store product.
        $insertproduct = DB::table('product')
                            ->insertGetId([
                                'cat_id'           =>  $category_id,
                                'product_store_id' =>  $store_id,
                                'product_name'     =>  $product_name,
                                'product_image'    =>  $product_image,
                                'product_approved_status'=>0,
                            ]);
        
        if($insertproduct) {

            # store product varient.
            DB::table('product_varient')
                    ->insert([
                        'product_id'    => $insertproduct,
                        'quantity'      => $quantity,
                        'varient_image' => $product_image,
                        'unit'          => $unit,
                        'price'         => $price,
                        'mrp'           => $mrp,
                        'description'   => $description,
                        'discount_percentage' => $discountPercentage
                    ]);

            $productSynonymsNames   =  $request->product_synonyms_name;

            if (!is_null($productSynonymsNames)) {

                foreach ($productSynonymsNames as $key => $productSynonymsName) {

                    # store Synonyms Name of product.
                    DB::table('product_synonym_names')
                            ->insert([
                                'product_id'    => $insertproduct,
                                'name'          => $productSynonymsName,
                            ]);
                }
            }

            # view create page with success.
            return redirect()->back()->withSuccess('Product Added Successfully');

        } else {
            # view create page with error
            return redirect()->back()->withErrors("Something Wents Wrong");
        }
      
    }

    // Code by Aslam
   public function st_productDiscount(Request $request){

      $email = Session::get('bamaStore');
      $store= DB::table('store')->where('email',$email)->first();

      $selected =  DB::table('discounts')->where('store_id', $store->store_id)->where('product_id', 1)
                ->orderBy('product_id','asc')->paginate(8);

   }

    public function sel_product(Request $request)
    {
        $title = "Add Product";
         $email=Session::get('bamaStore');
       $store= DB::table('store')
               ->where('email',$email)
               ->first();
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
         
        $selected =  DB::table('store_products')
                ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                ->join('categories as cat','product.cat_id','=','cat.cat_id')
                ->where('store_products.store_id', $store->store_id)
                ->orderBy('store_products.stock','asc')
                ->paginate(8);  
                
        $check=  DB::table('store_products')
                ->where('store_id', $store->store_id)
                ->get(); 
        if(count($check)>0)  {
        foreach($check as $ch){
            $ch2 = $ch->varient_id;
            $ch3[] = array($ch2);
        }
          $products = DB::table('product_varient')
                ->join('product','product_varient.product_id', '=', 'product.product_id')
                ->join('categories as cat','product.cat_id','=','cat.cat_id')
                ->where('product.product_store_id', $store->store_id)
                ->whereNotIn('product_varient.varient_id', $ch3)
                ->get();    
        
      return view('store.products.select', compact('title',"store", "logo","products","selected"));
        }else{
             $products = DB::table('product_varient')
                ->join('product','product_varient.product_id', '=', 'product.product_id')
                ->join('categories as cat','product.cat_id','=','cat.cat_id')
                ->where('product.product_store_id', $store->store_id)
                ->get();
                
            return view('store.products.select', compact('title',"store", "logo","products","selected"));    
        }
      
    }
    
    
    
      public function st_product(Request $request)
    {
        $title = "Products";
         $email=Session::get('bamaStore');
       $store= DB::table('store')
               ->where('email',$email)
               ->first();
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
         
        $selected =  DB::table('store_products')
                ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                ->join('categories as cat','product.cat_id','=','cat.cat_id')
                ->where('store_id', $store->store_id)
                ->orderBy('store_products.stock','asc')
                ->paginate(8);  
                
        $check=  DB::table('store_products')
                ->where('store_id', $store->store_id)
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
        
      return view('store.products.pr', compact('title',"store", "logo","products","selected"));
        }else{
             $products = DB::table('product_varient')
                ->join('product','product_varient.product_id', '=', 'product.product_id')
                ->get();
                
            return view('store.products.pr', compact('title',"store", "logo","products","selected"));    
        }
      
    }
    
    
    public function added_product(Request $request)
    {
         $email=Session::get('bamaStore');
       $store= DB::table('store')
               ->where('email',$email)
               ->first();
               
       $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
          
    $prod = $request->prod;
    $countprod = count($prod);

    for($i=0;$i<=($countprod-1);$i++)
        {
            $insert2 = DB::table('store_products')
                  ->insert(['store_id'=>$store->store_id,'stock'=>0, 'varient_id'=>$prod[$i]]);
        }
          
         return redirect()->back()->withSuccess('Product Added Successfully');
    }
    
     public function delete_product(Request $request)
    {
        $id =$request->id;
       $delete = DB::table('store_products')
                ->where('p_id', $id)
                ->delete();
         if($delete){
            return redirect()->back()->withSuccess('Product Removed'); 
         } else{
         return redirect()->back()->withErrors('Something Went Wrong');
         }

    }
    
     public function stock_update(Request $request)
    {
        $id =$request->id;
        $stock = $request->stock;
       $stockupdate = DB::table('store_products')
                ->where('p_id', $id)
                ->update(['stock'=>$stock]);
         if($stockupdate){
            return redirect()->back()->withSuccess('Product Stock Updated Successfully'); 
         } else{
         return redirect()->back()->withErrors('something went wrong');
         }

    }

     /**
     * Block unblock product
     * 
     * @param $request
     */
    public function st_productUnblock(Request $request)
    {
        # get product id.
        $product_id = $request->id;
  
        $product = DB::table('product')
                    ->where('product_id', $product_id)
                    ->update(['status' => 0]);
                    
        if($product) {   
            return redirect()->back()->withSuccess('Product Blocked Successfully');
        } else {
          return redirect()->back()->withErrors('Something Wents Wrong');   
        }
    }

    /**
     * Block unblock product
     * 
     * @param $request
     */
    public function st_productblock(Request $request)
    {
        # get product id.
        $product_id = $request->id;
  
        $product = DB::table('product')
                    ->where('product_id', $product_id)
                    ->update(['status' => 1]);
                    
        if($product) {   
            return redirect()->back()->withSuccess('Product Active Successfully');
        } else {
          return redirect()->back()->withErrors('Something Wents Wrong');   
        }
    }

    public function st_deleteProduct(Request $request)
    {
        $product_id=$request->product_id;

        $delete=DB::table('product')->where('product_id',$request->product_id)->delete();
        if($delete)
        {
         $delete=DB::table('product_varient')->where('product_id',$request->product_id)->delete();  
         
        return redirect()->back()->withSuccess('Deleted Successfully');
        }
        else
        {
           return redirect()->back()->withErrors('Unsuccessfull Delete'); 
        }
    }


    public function st_editProduct(Request $request)
    {
       
        # get product id
        $product_id = $request->product_id;

        # set title of product fro show the create page
        $title      = "Edit Store Product";

        # get login user id.
        $email  = Session::get('bamaStore');

        # get login user information.
        $store = DB::table('store')
                        ->where('email',$email)
                        ->first();

        # get logo of admin panel.
        $logo = DB::table('tbl_web_setting')
                    ->where('set_id', '1')
                    ->first();

        # all child categories from category.
        $cat = DB::table('categories')
                   ->select('parent')
                   ->where('status', 1)
                   ->get();             
                   
        if(count($cat)>0) {           
            foreach($cat as $cats) {
                $a = $cats->parent;
               $aa[] = array($a); 
            }
        } else {
            $a = 0;
            $aa[] = array($a);
        }
        
        # all parent category.
        /*$category = DB::table('categories')
                    ->where('level', '!=', 0)
                    ->WhereNotIn('cat_id', $aa)
                    ->where('status', 1)
                    ->get();*/
        #new code to show all category beg
                    $category = DB::table('categories')
                   ->select('*')
                   ->where('status', 1)
                   ->get();
        #new code to show all category end
     
        # get product information
        $product =  DB::table('product')
                        ->LeftJoin('product_varient', 'product_varient.product_id', '=', 'product.product_id')
                        ->where('product.product_id', $product_id)
                        ->first();

        $productSynonymsNames   =   DB::table('product_synonym_names')
                                        ->where('product_id', $product_id)
                                        ->get();

        # view edit product page.
        return view('store.products.product_edit',compact("email", "category", "store", "logo", "title", "product", "productSynonymsNames"));
    }

    public function st_updateProduct(Request $request,$id)
    {
       // dd($request->all());
        //dd($request->product_id);
         # get login user id.
        $email  = Session::get('bamaStore');

        # get login user information.
        $store = DB::table('store')
                        ->where('email',$email)
                        ->first();

        # Gather form data.
        $category_id        = $request->cat_id;
        $product_id     = $request->product_id;
        $product_name   = $request->product_name;
        $date           = date('d-m-Y');
        $product_image  = $request->product_image;

        $quantity           = $request->quantity;
        $unit               = $request->unit;
        $price              = $request->price;
        $description        = $request->description;
        $date               = date('d-m-Y');
        $mrp                = $request->mrp;
        $discountPercentage = $request->discount_percentage;
        $store_id           = $store->store_id;
        

        # check validation. 
        $this->validate(
                $request,
                [
                    'product_name' => 'required',
                ],
                [
                    'product_name.required' => 'Enter product name.',
                ]
        );

        $getProduct = DB::table('product')
                        ->where('product_id',$product_id)
                        ->first();


        $image = $getProduct->product_image;

        if($request->hasFile('product_image')){
            $product_image  = $request->product_image;
            $fileName       = $product_image->getClientOriginalName();
            $fileName       = str_replace(" ", "-", $fileName);
            $product_image->move('admin-panel/images/product/'.$date.'/', $fileName);
            $product_image  = 'images/product/'.$date.'/'.$fileName;
        } else {
            $product_image = $image;
        }


            $insertproduct = DB::table('product')
                                ->where('product_id', $product_id)
                                ->update([
                                    'cat_id'          =>  $category_id,
                                    'product_store_id'=>  $store_id,
                                    'product_name'    =>  $product_name,
                                    'product_image'   =>  $product_image,
                                ]);

                # store product varient.
                DB::table('product_varient')
                        ->where('product_id', $product_id)
                        ->Update([
                            'product_id'    => $product_id,
                            'quantity'      => $quantity,
                            'varient_image' => $product_image,
                            'unit'          => $unit,
                            'price'         => $price,
                            'mrp'           => $mrp,
                            'description'   => $description,
                            'discount_percentage' => $discountPercentage
                        ]);

            # synonyms name update
            $productSynonymsNames       =   $request->product_synonyms_name;

            $productSynonymsNameRows    =   DB::table('product_synonym_names')
                                                ->where('product_id', $product_id)
                                                ->pluck('id')
                                                ->toArray();

            #insert stynonyms
            if(!empty($productSynonymsNames)) {

            foreach ($productSynonymsNames as $key => $productSynonymsName) {

                if (!is_numeric($productSynonymsName)) {
                    # store Synonyms Name of product.
                    DB::table('product_synonym_names')
                            ->insert([
                                'product_id'    => $product_id,
                                'name'          => $productSynonymsName,
                            ]);
                }
            }
        } //isse synonym
      
            foreach ($productSynonymsNameRows as $key => $productSynonymsNameRow) {

                if($productSynonymsNames){
                    if (in_array($productSynonymsNameRow, $productSynonymsNames)) {
                        # code...
                    } else {
                      if (is_int($productSynonymsNameRow)) {
                        # store Synonyms Name of product.
                        DB::table('product_synonym_names')
                                ->where('id', $productSynonymsNameRow)
                                ->delete();
                      }
                    }

                }else{
                    if (is_int($productSynonymsNameRow)) {
                        # store Synonyms Name of product.
                        DB::table('product_synonym_names')
                                ->where('id', $productSynonymsNameRow)
                                ->delete();
                    }

                }
            }

            return redirect()->back()->withSuccess('Product Updated Successfully');

    }
}
