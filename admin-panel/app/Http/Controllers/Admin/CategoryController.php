<?php

namespace App\Http\Controllers\Admin;

use DB;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function list(Request $request)
    {
       
        $title = "Category List";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
           $category = DB::table('categories')
                        ->leftJoin('categories as catt', 'categories.parent', '=' , 'catt.cat_id')
                        ->select('categories.*', 'catt.title as tttt')
                        ->where('categories.parent', '=','0')
                        ->get();
         //dd($category);               
                    
                    
   
    return view('admin.category.list', compact('title',"admin", "logo","category"));
    }


#added code to get parent category beg
    public function parentcategory(Request $request)
    {
       
        $title = "Parent Category List";
         $admin_email=Session::get('bamaAdmin');
         $admin= DB::table('admin')
                   ->where('admin_email',$admin_email)
                   ->first();
          $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
           // $category = DB::table('categories')
           //              ->leftJoin('categories as catt', 'categories.parent', '=' , 'catt.cat_id')
           //              ->select('categories.*', 'catt.title as tttt')
           //              ->paginate(6);

                        $parentcategory = DB::table('categories')
                                    ->select('categories.*')
                                    ->where('parent','=',0)
                                    //->paginate(6); //old commented by me
                                    ->get(); //added by me azwar
                        //dd($parentcategory);
                    
                    
   
    return view('admin.category.parentcategorylist', compact('title',"admin", "logo","parentcategory"));
    }

#added code to get parent category end


#added code for subcategory beg 
    public function subcategory(Request $request,$id='')
    {
       
        $title = "Sub Category List";
         $admin_email=Session::get('bamaAdmin');
         $admin= DB::table('admin')
                   ->where('admin_email',$admin_email)
                   ->first();
          $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                $subcategory = DB::table('categories')
                        ->leftJoin('categories as catt', 'categories.parent', '=' , 'catt.cat_id')
                        ->select('categories.*', 'catt.title as tttt')
                        ->where('categories.parent','!=',0);
               
          #added code for subcategory filter for parent category
         if(!empty($id)){
                $subcategory->where('categories.parent','=',$id);
         }
          #added code for subcategory filter for parent end
         // $subcategory=$subcategory ->paginate(6); //old commented by me
          $subcategory=$subcategory ->get(); //added by me
          //dd($subcategory);
           
                        //->get();

                       /* $subcategory = DB::table('categories')
                                    ->select('categories.*')
                                    ->where('parent','!=',0)
                                    ->paginate(6);*/
                       // dd($subcategory);
                    
                    
   
    return view('admin.category.subcategorylist', compact('title',"admin", "logo","subcategory"));
    }

        #added code for subcategory end




    
     public function AddCategory(Request $request)
    {
    
        $title = "Add Category";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
           $category = DB::table('categories')
                    ->where('level', 0)
                    ->orWhere('level', 1)
                    ->get();
        
        
        return view('admin.category.add',compact("category", "admin_email","logo", "admin","title"));
     }
    
     public function AddNewCategory(Request $request)
    {
      
        $parent_id = $request->parent_id;
        $category_name = $request->cat_name;
        $status = 1;
        $slug = str_replace(" ", '-', $category_name);
        $date=date('d-m-Y');
        $desc = $request->desc;
        if($desc==NULL){
          $desc= $category_name; 
        }
        $category = DB::table('categories')
                  ->where('cat_id', $parent_id)
                  ->first();
    			         
        if($status=="")
        {
            $status=0;
        }
  
    if($category)
        {    
        if($parent_id==$category->cat_id)
            {
                if($category->level==0){
                    $level = 1;
                } 
                elseif($category->level==1){
                    $level = 2;
                }
            }
        }
        else{
           $level = 0; 
        }
        
    
        #image not required

        $this->validate(
            $request,
                [
                    
                    'cat_name' => 'required',
                   // 'cat_image' => 'required|mimes:jpeg,png,jpg|max:400',
                ],
                [
                    'cat_name.required' => 'Enter category name.',
                   // 'cat_image.required' => 'Choose category image.',
                ]
        );

        

        

        if($request->hasFile('cat_image')){
            $category_image = $request->cat_image;
            $fileName = $category_image->getClientOriginalName();
            $fileName = str_replace(" ", "-", $fileName);
            $category_image->move('images/category/'.$date.'/', $fileName);
            $category_image = 'images/category/'.$date.'/'.$fileName;
        }
        else{
            $category_image = 'N/A';
        }

        $insertCategory = DB::table('categories')
                            ->insert([
                                'parent'=>$parent_id,
                                'title'=>$category_name,
                                'slug'=>$slug,
                                'level'=>$level,
                                'image'=>$category_image,
                                'status'=>$status,
                                'description'=>$desc
                               
                            ]);
          //dd($insertCategory);
        
        if($insertCategory){
            return redirect()->back()->withSuccess('Category Added Successfully');
        }
        else{
          //  return redirect()->back()->withErrors("Something Wents Wrong");
        }
      
    }


    public function AddSubCategory(Request $request)
    {
    
        $title = "Add Sub Category";
         $admin_email=Session::get('bamaAdmin');
       $admin= DB::table('admin')
             ->where('admin_email',$admin_email)
             ->first();
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
           $category = DB::table('categories')
                    ->where('level', 0)
                    ->orWhere('level', 1)
                    ->get();
        
        
        return view('admin.category.subcategory_add',compact("category", "admin_email","logo", "admin","title"));
     }
    
     public function AddNewSubCategory(Request $request)
    {
      
        $parent_id = $request->parent_id;
        $category_name = $request->cat_name;
        $status = 1;
        $slug = str_replace(" ", '-', $category_name);
        $date=date('d-m-Y');
        $desc = $request->desc;
        if($desc==NULL){
          $desc= $category_name; 
        }
        $category = DB::table('categories')
                  ->where('cat_id', $parent_id)
                  ->first();
                   
        if($status=="")
        {
            $status=0;
        }
  
    if($category)
        {    
        if($parent_id==$category->cat_id)
            {
                if($category->level==0){
                    $level = 1;
                } 
                elseif($category->level==1){
                    $level = 2;
                }
            }
        }
        else{
           $level = 0; 
        }
        
    
        #image not required

        $this->validate(
            $request,
                [
                    
                    'cat_name' => 'required',
                   // 'cat_image' => 'required|mimes:jpeg,png,jpg|max:400',
                ],
                [
                    'cat_name.required' => 'Enter sub category name.',
                   // 'cat_image.required' => 'Choose category image.',
                ]
        );

        

        

        if($request->hasFile('cat_image')){
            $category_image = $request->cat_image;
            $fileName = $category_image->getClientOriginalName();
            $fileName = str_replace(" ", "-", $fileName);
            $category_image->move('images/category/'.$date.'/', $fileName);
            $category_image = 'images/category/'.$date.'/'.$fileName;
        }
        else{
            $category_image = 'N/A';
        }

        $insertCategory = DB::table('categories')
                            ->insert([
                                'parent'=>$parent_id,
                                'title'=>$category_name,
                                'slug'=>$slug,
                                'level'=>$level,
                                'image'=>$category_image,
                                'status'=>$status,
                                'description'=>$desc
                               
                            ]);
          //dd($insertCategory);
        
        if($insertCategory){
            return redirect()->back()->withSuccess('Sub Category Added Successfully');
        }
        else{
          //  return redirect()->back()->withErrors("Something Wents Wrong");
        }
      
    }
    
    public function EditCategory(Request $request)
    {
       
         $category_id = $request->category_id;
         $title = "Edit Category";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
          $category = DB::table('categories')
                    ->where('level', 0)
                    ->orWhere('level', 1)
                    ->where('cat_id','!=',$category_id)
                    ->get();
                    
        $cat=  DB::table('categories')
            ->where('cat_id', $category_id)
            ->first();

        return view('admin.category.edit',compact("category","admin_email","admin","logo","cat","title"));
    }

    //Edit subcategory 

    public function EditSubCategory(Request $request)
    {
       
         $category_id = $request->category_id;
         $title = "Edit Sub Category";
         $admin_email=Session::get('bamaAdmin');
       $admin= DB::table('admin')
             ->where('admin_email',$admin_email)
             ->first();
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
          $category = DB::table('categories')
                    ->where('level', 0)
                    ->orWhere('level', 1)
                    ->where('cat_id','!=',$category_id)
                    ->get();
                    
        $cat=  DB::table('categories')
            ->where('cat_id', $category_id)
            ->first();

        return view('admin.category.subcategory_edit',compact("category","admin_email","admin","logo","cat","title"));
    }

    public function UpdateCategory(Request $request)
    {
        $category_id = $request->category_id;
         $parent_id = $request->parent_id;
        $category_name = $request->cat_name;
        $status = 1;
        $slug = str_replace(" ", '-', $category_name);
        $date=date('d-m-Y');
          $desc = $request->desc;
        if($desc==NULL){
          $desc= $category_name; 
        }
        $category = DB::table('categories')
                  ->where('cat_id', $parent_id)
                  ->first();
    			         
        if($status=="")
        {
            $status=0;
        }
  
    if($category)
        {    
        if($parent_id==$category->cat_id)
            {
                if($category->level==0){
                    $level = 1;
                } 
                elseif($category->level==1){
                    $level = 2;
                }
            }
        }
        else{
           $level = 0; 
        }
        
    
        
        $this->validate(
            $request,
                [
                    
                    'cat_name' => 'required',
                ],
                [
                    'cat_name.required' => 'Enter category name.',
                ]
        );

       $getCategory = DB::table('categories')
                    ->where('cat_id',$category_id)
                    ->first();

        $image = $getCategory->image;

        if($request->hasFile('cat_image')){
            $category_image = $request->cat_image;
            $fileName = $category_image->getClientOriginalName();
            $fileName = str_replace(" ", "-", $fileName);
            $category_image->move('images/category/'.$date.'/', $fileName);
            $category_image = 'images/category/'.$date.'/'.$fileName;
        }
        else{
            $category_image = $image;
        }

        $insertCategory = DB::table('categories')
                       ->where('cat_id', $category_id)
                            ->update([
                                'parent'=>$parent_id,
                                'title'=>$category_name,
                                'slug'=>$slug,
                                'level'=>$level,
                                'image'=>$category_image,
                                'status'=>$status,
                                'description'=>$desc
                               
                            ]);
        
        if($insertCategory){
            return redirect()->back()->withSuccess('Category Added Successfully');
        }
        else{
            return redirect()->back()->withErrors("Something Wents Wrong");
        }
       
       
       
       
    }

    //update sub category

    public function UpdateSubCategory(Request $request)
    {
        $category_id = $request->category_id;
         $parent_id = $request->parent_id;
        $category_name = $request->cat_name;
        $status = 1;
        $slug = str_replace(" ", '-', $category_name);
        $date=date('d-m-Y');
        $desc = $request->cat_desc;
        if($desc==NULL){
          $desc= $category_name; 
        }
        $category = DB::table('categories')
                  ->where('cat_id', $parent_id)
                  ->first();
                   
        if($status=="")
        {
            $status=0;
        }
  
    if($category)
        {    
        if($parent_id==$category->cat_id)
            {
                if($category->level==0){
                    $level = 1;
                } 
                elseif($category->level==1){
                    $level = 2;
                }
            }
        }
        else{
           $level = 0; 
        }
        
    
        
        $this->validate(
            $request,
                [
                    
                    'cat_name' => 'required',
                ],
                [
                    'cat_name.required' => 'Enter sub category name.',
                ]
        );

       $getCategory = DB::table('categories')
                    ->where('cat_id',$category_id)
                    ->first();

        $image = $getCategory->image;



        if($request->hasFile('cat_image')){
            $category_image = $request->cat_image;
            $fileName = $category_image->getClientOriginalName();
            $fileName = str_replace(" ", "-", $fileName);
            $category_image->move('images/category/'.$date.'/', $fileName);
            $category_image = 'images/category/'.$date.'/'.$fileName;
        }
        else{
            $category_image = $image;
        }

        $insertCategory = DB::table('categories')
                       ->where('cat_id', $category_id)
                            ->update([
                                'parent'=>$parent_id,
                                'title'=>$category_name,
                                'slug'=>$slug,
                                'level'=>$level,
                                'image'=>$category_image,
                                'status'=>$status,
                                'description'=>$desc
                               
                            ]);
        //dd($desc);                    
        
        if($insertCategory){
            return redirect()->back()->withSuccess('Sub Category Added Successfully');
        }
        else{
            return redirect()->back()->withErrors("Something Wents Wrong");
        }
       
       
       
       
    }
    
    
    
 public function DeleteCategory(Request $request)
    {
        $category_id=$request->category_id;

    	$delete=DB::table('categories')->where('cat_id',$request->category_id)->delete();
        if($delete)
        {
        return redirect()->back()->withSuccess('Deleted Successfully');
        }
        else
        {
           return redirect()->back()->withErrors('Unsuccessfull Delete'); 
        }
    }

    /**
     * Block unblock category
     * 
     * @param $request
     */
    public function categoryUnblock(Request $request)
    {
        # get category id.
        $category_id = $request->id;
  
        $category = DB::table('categories')
                    ->where('cat_id', $category_id)
                    ->update(['status' => 0]);
                    
        if($category) {   
            return redirect()->back()->withSuccess('Category Blocked Successfully');
        } else {
          return redirect()->back()->withErrors('Something Wents Wrong');   
        }
    }

    /**
     * Block unblock category
     * 
     * @param $request
     */
    public function categoryblock(Request $request)
    {
        # get category id.
        $category_id = $request->id;
  
        $category = DB::table('categories')
                    ->where('cat_id', $category_id)
                    ->update(['status' => 1]);
                    
        if($category) {   
            return redirect()->back()->withSuccess('Category Active Successfully');
        } else {
          return redirect()->back()->withErrors('Something Wents Wrong');   
        }
    }

}