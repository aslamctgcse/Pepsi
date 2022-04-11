<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;

class DeliveryController extends Controller
{
    public function list(Request $request)
    {
        $title = "Delivery Boy List";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();

           $d_boy = DB::table('delivery_boy')
                    ->LeftJoin('store', 'store.store_id', '=', 'delivery_boy.store_id')
                    ->orderBy('delivery_boy.dboy_id', 'desc')
                    ->paginate(10);
     
    	return view('admin.d_boy.list', compact('title',"admin", "logo","d_boy"));
    }

    
     public function AddD_boy(Request $request)
    {
    
        $title = "Add Delivery Boy";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
           $d_boy = DB::table('delivery_boy')
                    ->get();
        $city =DB::table('city')
              ->get();

         $map1 = DB::table('map_api')
             ->first();
           
         $map = $map1->map_api_key;

         $stores  =   DB::table('store')
                            ->get();

        return view('admin.d_boy.add',compact("d_boy", "admin_email","logo", "admin","title", 'city','map', 'stores'));
     }
    
    public function AddNewD_boy(Request $request)
    {
        $boy_name = $request->boy_name;
        $boy_phone =$request->boy_phone;
        $password = $request->password;
        $boy_loc = $request->boy_loc;
        $city       = $request->city;
        $boy_licence_number     = $request->boy_licence_number;
        $boy_identification     = $request->boy_identification;
        $medical_data           = $request->medical_data;
        $life_insurence_number  = $request->life_insurence_number;
        $store_id  = $request->store_id;
        $status = 1;
        $date=date('d-m-Y');
       
        $addres = str_replace(" ", "+", $boy_loc);
        $address1 = str_replace("-", "+", $addres);
         $mapapi = DB::table('map_api')
                 ->first();
                 
                    
        $chkboyrphon = DB::table('delivery_boy')
                      ->where('boy_phone', $boy_phone)
                      ->first(); 

        if($chkboyrphon){
             return redirect()->back()->withErrors('This Phone Number Is Already Registered With Another Delivery Boy');
        } 
                 
        $key = $mapapi->map_api_key;         
        $response = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".$address1."&key=".$key));
        
        
        $lat = $response->results[0]->geometry->location->lat;
        $lng = $response->results[0]->geometry->location->lng;
    
        
        $this->validate(
            $request,
                [
                    
                    'boy_name' => 'required',
                    'boy_phone' => 'required',
                    'password' => 'required',
                    'boy_loc'=> 'required',
                    'city'=>'required',
                    
                ],
                [
                    'boy_name.required' => 'Enter Boy Name.',
                    'boy_phone.required' => 'Choose Boy Phone.',
                    'password.required' => 'choose password',
                    'boy_loc.required' => 'enter boy location',
                    'city.required' => 'enter boy city',
                ]
        );

        # upload product image
        if($request->hasFile('licence_image')){
            $licence_image  = $request->licence_image;
            $fileName       = $licence_image->getClientOriginalName();
            $fileName       = str_replace(" ", "-", $fileName);
            $licence_image->move('images/delivery_boy/'.$date.'/', $fileName);
            $licence_image = 'images/delivery_boy/'.$date.'/'.$fileName;
        } else {
            $licence_image = '';
        }

        # upload product image
        if($request->hasFile('identification_image')){
            $identification_image  = $request->identification_image;
            $fileName       = $identification_image->getClientOriginalName();
            $fileName       = str_replace(" ", "-", $fileName);
            $identification_image->move('images/delivery_boy/'.$date.'/', $fileName);
            $identification_image = 'images/delivery_boy/'.$date.'/'.$fileName;
        } else {
            $identification_image = '';
        }

        $insert = DB::table('delivery_boy')
                            ->insert([
                                'boy_name'=>$boy_name,
                                'boy_phone'=>$boy_phone,
                                'boy_city'=>$city,
                                'password'=>$password,
                                'boy_loc'=>$boy_loc,
                                'lat'=>$lat,
                                'lng'=>$lng,
                                'status'=>$status,
                                'boy_licence_number'=>$boy_licence_number,
                                'boy_identification'=>$boy_identification,
                                'medical_data'=>$medical_data,
                                'life_insurence_number'=>$life_insurence_number,
                                'identification_image'=>$identification_image,
                                'licence_image'=>$licence_image,
                                'store_id'=>$store_id
                            ]);
        
        if($insert){
            return redirect()->back()->withSuccess('Delivery Boy Added Successfully');
        }
        else{
            return redirect()->back()->withErrors("Something Wents Wrong");
        }
      
    }
    
    public function EditD_boy(Request $request)
    {
         $dboy_id = $request->id;
         $title = "Edit Delivery Boy";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                    
        $d_boy=  DB::table('delivery_boy')
            ->where('dboy_id', $dboy_id)
            ->first();
        $city =DB::table('city')
              ->get();
              
         $map1 = DB::table('map_api')
             ->first();
         $map = $map1->map_api_key; 

         $stores  =   DB::table('store')
                            ->get();

        return view('admin.d_boy.edit',compact("d_boy","admin_email","admin","logo","title","city","map", 'stores'));
    }

    public function UpdateD_boy(Request $request)
    {
        $dboy_id = $request->id;
        $boy_name = $request->boy_name;
        $boy_phone =$request->boy_phone;
        $password = $request->password;
        $boy_loc = $request->boy_loc;
        $city =$request->city;
        $boy_licence_number     = $request->boy_licence_number;
        $boy_identification     = $request->boy_identification;
        $medical_data           = $request->medical_data;
        $life_insurence_number  = $request->life_insurence_number;
        $store_id  = $request->store_id;
        $addres = str_replace(" ", "+", $boy_loc);
        $address1 = str_replace("-", "+", $addres);
        $date=date('d-m-Y');
        
        $chkboyrphon = DB::table('delivery_boy')
                          ->where('boy_phone', $boy_phone)
                          ->where('dboy_id','!=',$dboy_id)
                         ->first(); 

        if($chkboyrphon){
            return redirect()->back()->withErrors('This Phone Number Is Already Registered With Another Delivery Boy');
        } 
        
         $mapapi = DB::table('map_api')
                 ->first();
                 
        $key = $mapapi->map_api_key;         
        $response = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".$address1."&key=".$key));
        
        $lat = $response->results[0]->geometry->location->lat;
        $lng = $response->results[0]->geometry->location->lng;
        
         $this->validate(
            $request,
                [
                    
                    'boy_name' => 'required',
                    'boy_phone' => 'required',
                    'password' => 'required',
                    'boy_loc'=> 'required',
                    'city'=>'required'
                    
                ],
                [
                    'boy_name.required' => 'Enter Boy Name.',
                    'boy_phone.required' => 'Choose Boy Phone.',
                    'password.required' => 'choose password',
                    'boy_loc.required' => 'enter boy location',
                    'city.required' => 'enter boy city'
                ]
        );

         $dBoyData = [
                        'boy_name'=>$boy_name,
                        'boy_phone'=>$boy_phone,
                        'boy_city'=>$city,
                        'password'=>$password,
                        'boy_loc'=>$boy_loc,
                        'lat'=>$lat,
                        'lng'=>$lng,
                        'boy_licence_number'=>$boy_licence_number,
                        'boy_identification'=>$boy_identification,
                        'medical_data'=>$medical_data,
                        'life_insurence_number'=>$life_insurence_number,
                        'store_id'=>$store_id
                    ];

        # upload product image
        if($request->hasFile('licence_image')){
            $licence_image  = $request->licence_image;
            $fileName       = $licence_image->getClientOriginalName();
            $fileName       = str_replace(" ", "-", $fileName);
            $licence_image->move('images/delivery_boy/'.$date.'/', $fileName);
            $dBoyData['licence_image'] = 'images/delivery_boy/'.$date.'/'.$fileName;
        }

        # upload product image
        if($request->hasFile('identification_image')){
            $identification_image  = $request->identification_image;
            $fileName       = $identification_image->getClientOriginalName();
            $fileName       = str_replace(" ", "-", $fileName);
            $identification_image->move('images/delivery_boy/'.$date.'/', $fileName);
            $dBoyData['identification_image'] = 'images/delivery_boy/'.$date.'/'.$fileName;
        }

        try {
            
            $updated = DB::table('delivery_boy')
                       ->where('dboy_id', $dboy_id)
                        ->update($dBoyData);

            return redirect()->back()->withSuccess('Delivery Boy Updated Successfully');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors("Something Wents Wrong");
        }

    }
    
    
    
    public function DeleteD_boy(Request $request)
    {
        $dboy_id = $request->id;

    	$delete=DB::table('delivery_boy')
                    ->where('dboy_id', $dboy_id)
                    ->delete();

        if($delete)
        {
            return redirect()->back()->withSuccess('Deleted Successfully');
        }
        else
        {
           return redirect()->back()->withErrors('Unsuccessfull Delete'); 
        }
    }

}