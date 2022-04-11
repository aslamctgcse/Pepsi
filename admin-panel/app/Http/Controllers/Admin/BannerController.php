<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;

class BannerController extends Controller
{
    public function bannerlist(Request $request)
    {
        $title = "Banner List";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
        
        $city = DB::table('banner')
                ->get();
        //dd($city);
                
        return view('admin.banner.bannerlist', compact('title','city','admin','logo'));    
        
        
    }
    public function banner(Request $request)
    {
        $title = "Add Banner";

         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
        
        $city = DB::table('banner')
                ->get();

        $category = DB::table('categories')
                    ->where('parent', 0)
                    ->Where('status', 1)
                    ->get();
                            
                
        return view('admin.banner.addbanner', compact('title','category','city','admin','logo'));    
        
        
    }
    public function banneradd(Request $request)
    {
        $title = "Add Banner";
        
        $banner = $request->banner;
        $image = $request->image;
        $catId  = $request->parent_cat_id;
        
        
        $this->validate(
            $request,
                [
                    
                    'banner'=>'required',
                    'image'=>'required',
                ],
                [
                    
                    'banner.required'=>'Banner Name Required',
                    'image.required'=>'Image Required',

                ]
        );
        
         if($request->hasFile('image')){
            $image = $request->image;
            $fileName = date('dmyhisa').'-'.$image->getClientOriginalName();
            $fileName = str_replace(" ", "-", $fileName);
            $image->move('images/banner/', $fileName);
            $image = 'images/banner/'.$fileName;
        }
        else{
            $image = 'N/A';
        }
    	 $insert = DB::table('banner')
                    ->insert([
                        'banner_name'=>$banner,
                        'category_id'=>$catId,
                        'banner_image'=>$image
                        ]);
     if($insert){
         return redirect()->back()->withSuccess('Added Successfully');
     }else{
         return redirect()->back()->withErrors('Something Went Wrong');
     }

    }
    
    public function banneredit(Request $request)
    {
         $title = "Update Banner";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
        $banner_id = $request->banner_id;
        
        $city = DB::table('banner')
                ->where('banner_id',$banner_id)
                ->first();
        $category = DB::table('categories')
                    ->where('parent', 0)
                    ->Where('status', 1)
                    ->get();        
                
        return view('admin.banner.banneredit', compact('title','category','city','admin','logo'));    
        
        
    }
    
    public function bannerupdate(Request $request)
    {
        $title = "Home";
        $banner_id = $request->banner_id;
        $banner = $request->banner;
        $catId  = $request->parent_cat_id;
       $old_reward_image=$request->old_image;
        
        $this->validate(
            $request,
                [
                    
                    'banner'=>'required',
                ],
                [
                    
                    'banner.required'=>'Banner Name Required',

                ]
        );
        
        $getBanner = DB::table('banner')
                        ->where('banner_id', $banner_id)
                        ->first();

        $image = $getBanner->banner_image;
        

        if($request->hasFile('image')){
            if(file_exists($image)){
                unlink($image);
            }
            $new_image = $request->image;
            $fileName = date('dmyhisa').'-'.$new_image->getClientOriginalName();
            $fileName = str_replace(" ", "-", $fileName);
            $new_image->move('images/banner/', $fileName);
            $new_image = 'images/banner/'.$fileName;
        }
        else{
            $new_image = $getBanner->banner_image;
        }

        
    	 $insert = DB::table('banner')
    	            ->where('banner_id',$banner_id)
                    ->update([
                        'banner_name'=>$banner,
                        'category_id'=>$catId,
                        'banner_image'=>$new_image,
                        ]);
     
   if($insert){
         return redirect()->back()->withSuccess('Updated Successfully');
     }else{
         return redirect()->back()->withErrors('Something Went Wrong');
     }

    }
    
    public function bannerdelete(Request $request)
    {
        $banner_id = $request->society_id;

    	$delete=DB::table('banner')->where('banner_id',$banner_id)->delete();
        if($delete){
             return redirect()->back()->withSuccess('Deleted Successfully');
         }else{
             return redirect()->back()->withErrors('Something Went Wrong');
         }
    }
}