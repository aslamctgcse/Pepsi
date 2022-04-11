<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\ImportProduct;
use DB;
use Session;
use Mail;
use Excel;

class ProductController extends Controller
{
    public function list(Request $request)
    {
         
       
        $title = "Product List";
        $admin_email=Session::get('bamaAdmin');
    	$admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();

	    $logo = DB::table('tbl_web_setting')
            ->where('set_id', '1')
            ->first();
           $product = DB::table('product AS productss')
                    ->join('categories as cat','productss.cat_id','=','cat.cat_id')
                     ->join('product_varient as prodVarient','prodVarient.product_id','=','productss.product_id')
                     ->join('store as storesList','storesList.store_id','=','productss.product_store_id')
                   //->paginate(10); //old
                   ->select('productss.product_id as product_id','productss.product_approved_status as product_approved_status','productss.product_image as product_image','productss.status as status','prodVarient.mrp as mrp','prodVarient.price as price','prodVarient.quantity as quantity','prodVarient.unit as unit','productss.product_name as product_name', 'cat.title as title', 'storesList.store_name as storeName')
                   ->orderBy('productss.product_id', 'DESC') 
                   ->get(); //added by me azwar
        //dd($product);

        
    	return view('admin.product.list', compact('title',"admin", "logo","product"));
    }

    
    public function AddProduct(Request $request)
    {
      
          
        # set title of product fro show the create page
        $title = "Add Product";

        # get login user email id.
        $admin_email = Session::get('bamaAdmin');

        # get login user information.
    	$admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
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
        $stores  =   DB::table('store')
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
        return view('admin.product.add', compact("category", "admin_email", "logo", "admin", "title",'stores'));
     }


    public function addBulkProduct(Request $request)
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
    
    public function AddNewProduct(Request $request)
    {


       
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
        $store_id            = $request->store_id;

        $this->validate(
            $request,
                [
                    'cat_id'        => 'required',
                    'store_id'      => 'required',
                    'product_name'  => 'required',
                    'product_image' => 'required|mimes:jpeg,png,jpg|max:1000',
                    'quantity'      => 'required',
                    'unit'          => 'required',
                    'price'         => 'required',
                    'mrp'           => 'required',
                ],
                [
                    'cat_id.required'        => 'Select category',
                    'store_id.required'      => 'Select store',
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
            $product_image->move('images/product/'.$date.'/', $fileName);
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
                                'product_approved_status' =>1,
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
    
    public function EditProduct(Request $request)
    {
       
        # get product id
        $product_id = $request->product_id;

        # set title of product fro show the create page
        $title      = "Edit Product";

        # get login user id.
        $admin_email  = Session::get('bamaAdmin');

        # get login user information.
    	$admin = DB::table('admin')
    	 		        ->where('admin_email',$admin_email)
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
        $stores  =   DB::table('store')
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
        return view('admin.product.edit',compact("admin_email", "category", "admin", "logo", "title", "product", "productSynonymsNames","stores"));
    }

    public function UpdateProduct(Request $request,$id)
    {
       // dd($request->all());
        //dd($request->product_id);

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
        $store_id           = $request->store_id;
        

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
            $product_image->move('images/product/'.$date.'/', $fileName);
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
            }

            return redirect()->back()->withSuccess('Product Updated Successfully');

    }

     /**
     * Block unblock product
     * 
     * @param $request
     */
    public function productUnblock(Request $request)
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
    public function productblock(Request $request)
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
    
    
    
 public function DeleteProduct(Request $request)
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


    /**
     * unapproved product by admin
     * 
     * @param $request
     */
    public function productUnApproved(Request $request)
    {
        # get product id.
        $product_id = $request->id;
  
        $product = DB::table('product')
                    ->where('product_id', $product_id)
                    ->update(['product_approved_status' => 0]);
                    
        if($product) {   
            return redirect()->back()->withSuccess('Product Unapproved Successfully');
        } else {
          return redirect()->back()->withErrors('Something Wents Wrong');   
        }
    }

    /**
     * Approved product by admin
     * 
     * @param $request
     */
    public function productApproved(Request $request)
    {
        # get product id.
        $product_id = $request->id;
  
        $product = DB::table('product')
                    ->where('product_id', $product_id)
                    ->update(['product_approved_status' => 1]);
                    
        if($product) {   
            return redirect()->back()->withSuccess('Product Approved Successfully');
        } else {
          return redirect()->back()->withErrors('Something Wents Wrong');   
        }
    }

}