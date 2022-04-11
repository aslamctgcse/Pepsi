<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Carbon\Carbon;

class SettingsController extends Controller
{
   
     public function app_details(Request $request)
    {
        
        $title="Edit App Details";
    	 
    	$admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->first();	
         return view('admin.settings.app_details',compact("admin_email","admin",'title','logo'));
      

    }
 
    public function updateappdetails(Request $request)
    {
        $this->validate(
            $request,
                [
                    'app_name' => 'required',
                ],
                [
                    'app_name.required' => 'Enter App Name.',
                ]
        );
        
        
        $check = DB::table('tbl_web_setting')
               ->first();
        $app_name = $request->app_name;
          $date = date('d-m-Y');
        if($check){
        $oldapplogo = $check->icon;
        $oldfavicon = $check->favicon;
        }
        
         if($request->hasFile('app_icon')){
            $app = $request->app_icon;
            $fileName = $app->getClientOriginalName();
            $fileName = str_replace(" ", "-", $fileName);
            $app->move('images/app_logo/'.$date.'/', $fileName);
            $app = 'images/app_logo/'.$date.'/'.$fileName;
        }
        else{
            $app = $oldapplogo;
        }
        if($check->favicon != NULL){
        
         if($request->hasFile('favicon')){
            $favicon = $request->favicon;
            $fileName = $favicon->getClientOriginalName();
            $fileName = str_replace(" ", "-", $fileName);
            $favicon->move('images/app_logo/favicon/'.$date.'/', $fileName);
            $favicon = 'images/app_logo/favicon/'.$date.'/'.$fileName;
        }
        else{
            $favicon = $oldfavicon;
        }
        }
        else{
            if($request->hasFile('favicon')){
            $favicon = $request->favicon;
            $fileName = $favicon->getClientOriginalName();
            $fileName = str_replace(" ", "-", $fileName);
            $favicon->move('images/app_logo/favicon/'.$date.'/', $fileName);
            $favicon = 'images/app_logo/favicon/'.$date.'/'.$fileName;
        }
        else{
            $favicon = $oldapplogo;
        } 
        }
      if($check){
        

        $update = DB::table('tbl_web_setting')
                ->update(['name'=> $app_name,'icon'=> $app, 'favicon'=>$favicon]);
    
      }
      else{
          $update = DB::table('settings')
                ->insert(['name'=> $app_name, 'favicon'=>$favicon ]);
      }
     if($update){
        return redirect()->back()->withSuccess('Updated Successfully');
     }
     else{
         return redirect()->back()->withErrors('Something Wents Wrong');
     }
    }
    
    
    
     public function msg91(Request $request)
    {
        
        $title="SMS/OTP By";
    	 
    	$admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->first();	
                
          $msg91 = DB::table('msg91')
                ->first();   
          $twilio = DB::table('twilio')
                ->first(); 
            $smsby = DB::table('smsby')
                ->first();        
         return view('admin.settings.msg91',compact("admin_email","admin",'title','logo','msg91','twilio','smsby'));
      

    }
 
    public function updatemsg91(Request $request)
    {
         $sender = $request->sender_id;
        $api_key = $request->api;
        $this->validate(
            $request,
                [
                    'sender_id' => 'required',
                    'api'=>'required',
                ],
                [
                    'sender_id.required' => 'Enter Sender ID.',
                    'api.required' =>'Enter api key',
                ]
        );
        
        
        $check = DB::table('msg91')
               ->first();
       
    
      if($check){
        $update = DB::table('msg91')
                ->update(['sender_id'=> $sender,'api_key'=> $api_key,'active'=>1]);
    
      }
      else{
          $update = DB::table('msg91')
                ->insert(['sender_id'=> $sender,'api_key'=> $api_key,'active'=>1]);
      }
     if($update){
         $ue = DB::table('smsby')
                ->update(['msg91'=> 1,'twilio'=> 0,'status'=>1]);
         $deactivetwilio = DB::table('twilio')
                ->update(['active'=>0]);        
        return redirect()->back()->withSuccess('Updated Successfully');
     }
     else{
         return redirect()->back()->withErrors('Nothing to Update');
     }
    }
    
    
    
      public function mapapi(Request $request)
    {
        
        $title="Google Map API";
    	 
    	$admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->first();	
                
            $map = DB::table('map_api')
                ->first();   
         return view('admin.settings.map_api',compact("admin_email","admin",'title','logo','map'));
      

    }
 
    public function updatemap(Request $request)
    {
        $api_key = $request->api;
        $this->validate(
            $request,
                [
                    'api'=>'required',
                ],
                [
                    'api.required' =>'Enter api key',
                ]
        );
        
        
        $check = DB::table('map_api')
               ->first();
       
    
      if($check){
        

        $update = DB::table('map_api')
                ->update(['map_api_key'=> $api_key]);
    
      }
      else{
          $update = DB::table('map_api')
                ->insert(['map_api_key'=> $api_key]);
      }
     if($update){
        return redirect()->back()->withSuccess('Updated Successfully');
     }
     else{
         return redirect()->back()->withErrors('Something Wents Wrong');
     }
    }
     
  
    public function fcm(Request $request)
    {
        
        $title="Update FCM";
    	 
    	$admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->first();	
                
            $fcm = DB::table('fcm')
                ->first();   
         return view('admin.settings.fcm',compact("admin_email","admin",'title','logo','fcm'));
      

    }
 
    public function updatefcm(Request $request)
    {
        $fcm    = $request->fcm;
        $fcm2   = $request->fcm2;
        $fcm3   = $request->fcm3;
        $this->validate(
            $request,
                [
                    'fcm'=>'required',
                    'fcm2'=>'required',
                    'fcm3'=>'required',
                ],
                [
                    'fcm.required' =>'Enter User App FCM server key',
                    'fcm2.required'=>'Enter Store App FCM server key',
                    'fcm3.required'=>'Enter Store App FCM server key',
                ]
        );
        
        $check = DB::table('fcm')
               ->first();
        
        try {
            
            if($check){
            
                $update =   DB::table('fcm')
                                ->update([
                                    'server_key'        =>  $fcm,
                                    'store_server_key'  =>  $fcm2,
                                    'driver_server_key' =>  $fcm3
                                ]);
        
            } else {
                
                $update =   DB::table('fcm')
                                ->insert([
                                    'server_key'        => $fcm,
                                    'store_server_key'  => $fcm2,
                                    'driver_server_key' => $fcm3
                                ]);
            }
            
            return redirect()->back()->withSuccess('FCM server Keys Updated Successfully');
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something Wents Wrong');
        }
        
        // if($update){
        //     return redirect()->back()->withSuccess('FCM server Keys Updated Successfully');
        // } else {
        //     return redirect()->back()->withErrors('Something Wents Wrong');
        // }
    }
  
  
    public function del_charge(Request $request)
    {
        
        $title="Delivery Charge Setting";
    	 
    	 $admin_email=Session::get('bamaAdmin');
    	   $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                  ->first();	
                
            $del_charge = DB::table('freedeliverycart')
                              ->get();

         return view('admin.settings.del_charge',compact("admin_email","admin",'title','logo','del_charge'));
      

    }
 
    public function updatedel_charge(Request $request)
    {
        $delCharges = $request->delCharge;
        // $min_cart_value = $request->min_cart_value;
        // $this->validate(
        //     $request,
        //         [
        //             'del_charge'=>'required',
        //             'min_cart_value'=>'required',
        //         ],
        //         [
        //             'del_charge.required' =>'Enter delivery charge',
        //             'min_cart_value.required'=>'Enter Minimum Cart Value'
        //         ]
        // );
        
        try {
          
            foreach ($delCharges as $key => $delChargesValue) {
                $update = DB::table('freedeliverycart')
                            ->where('id', $delChargesValue['del_charge_id'])
                            ->update([
                                'min_cart_value'=> $delChargesValue['min_cart_value'],
                                'del_charge'    => $delChargesValue['del_charge']
                              ]);
            }

            return redirect()->back()->withSuccess('Delivery Charge Updated Successfully');
        } catch (\Exception $e) {
          
             return redirect()->back()->withErrors('Something Wents Wrong');
        }

        // $check = DB::table('freedeliverycart')
        //             ->first();
       
    
         //  if($check){
            

         //    $update = DB::table('freedeliverycart')
         //            ->update(['min_cart_value'=> $min_cart_value,
         //            'del_charge'=>$del_charge]);
        
         //  }
         //  else{
         //      $update = DB::table('freedeliverycart')
         //            ->insert(['min_cart_value'=> $min_cart_value,
         //             'del_charge'=>$del_charge]);
         //  }
         // if($update){
         //    return redirect()->back()->withSuccess('Delivery Charge Updated Successfully');
         // }
         // else{
         //     return redirect()->back()->withErrors('Something Wents Wrong');
         // }
    }

   //bulk order discount offer 
    public function bulk_order_discount(Request $request)
    {
        
        $title="Bulk Order Discount Setting";
       
       $admin_email=Session::get('bamaAdmin');
         $admin= DB::table('admin')
             ->where('admin_email',$admin_email)
             ->first();
        $logo = DB::table('tbl_web_setting')
                  ->first();  
                
        $bulkOrder_discount = DB::table('bulk_order_discount')
                              ->get();

         return view('admin.settings.bulk_order_discount',compact("admin_email","admin",'title','logo','bulkOrder_discount'));
      

    }
 
    public function updatebulk_order_discount(Request $request)
    {
        $bulkDiscounts = $request->bulkDiscount; 
       // dd($bulkDiscounts);    
        try {
          
            foreach ($bulkDiscounts as $key => $bulkDiscountsValue) {
                $update = DB::table('bulk_order_discount')
                            ->where('bulk_discount_id', $bulkDiscountsValue['bulk_discount_id'])
                            ->update([
                                'bulk_order_min_amount'=> $bulkDiscountsValue['bulk_order_min_amount'],
                                'bulk_order_max_amount'=> $bulkDiscountsValue['bulk_order_max_amount'],
                                'bulk_order_discount'    => $bulkDiscountsValue['bulk_order_discount']
                              ]);
            }

            return redirect()->back()->withSuccess('Bulk Order Discount Updated Successfully');
        } catch (\Exception $e) {
          
             return redirect()->back()->withErrors('Something Wents Wrong');
        }

    }

    //end bulk order discount offer

    //online payment discount offer 
    public function online_payment_discount(Request $request)
    {
        
        $title=" Online Payment Discount Setting";
       
       $admin_email=Session::get('bamaAdmin');
         $admin= DB::table('admin')
             ->where('admin_email',$admin_email)
             ->first();
        $logo = DB::table('tbl_web_setting')
                  ->first();  
                
            $onlinePayment_discount = DB::table('online_payment_discount')
                              ->get();

         return view('admin.settings.online_payment_discount',compact("admin_email","admin",'title','logo','onlinePayment_discount'));
      

    }
 
    public function updateonline_payment_discount(Request $request)
    {
        $onlineDiscounts = $request->onlineDiscount;     
        try {
          
            foreach ($onlineDiscounts as $key => $onlineDiscountsValue) {
                $update = DB::table('online_payment_discount')
                            ->where('online_pay_discount_id', $onlineDiscountsValue['online_pay_discount_id'])
                            ->update([
                                'online_payment_min_amount'=> $onlineDiscountsValue['online_payment_min_amount'],
                                'online_payment_max_amount'=> $onlineDiscountsValue['online_payment_max_amount'],
                                'online_payment_discount'    => $onlineDiscountsValue['online_payment_discount']
                              ]);
            }

            return redirect()->back()->withSuccess('Online Payment Discount Updated Successfully');
        } catch (\Exception $e) {
          
             return redirect()->back()->withErrors('Something Wents Wrong');
        }

    }

    //end online payment discount offer
     
        
    
      public function currency(Request $request)
    {
        
        $title="Currency";
    	 
    	$admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();	
                
            $currency = DB::table('currency')
                ->first();   
         return view('admin.settings.currency',compact("admin_email","admin",'title','logo','currency'));
      

    }
 
    public function updatecurrency(Request $request)
    {
        $currency_sign = $request->currency_sign;
        $currency_name = $request->currency_name;
        $this->validate(
            $request,
                [
                    'currency_sign'=>'required',
                    'currency_name'=>'required',
                ],
                [
                    'currency_sign.required' =>'Enter Currency Sign',
                    'currency_name'=>'Enter Currency Name',
                ]
        );
        
        
        $check = DB::table('currency')
               ->first();
       
    
      if($check){
        

        $update = DB::table('currency')
                ->update(['currency_sign'=> $currency_sign, 'currency_name'=> $currency_name]);
    
      }
      else{
          $update = DB::table('currency')
                ->insert(['currency_sign'=> $currency_sign, 'currency_name'=> $currency_name]);
      }
     if($update){
        return redirect()->back()->withSuccess('Updated Successfully');
     }
     else{
         return redirect()->back()->withErrors('Something Wents Wrong');
     }
    }
     
         public function prv(Request $request)
    {
        
        $title="Edit Payout Request Validation";
    	 
    	$admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();	
                
            $prv = DB::table('payout_req_valid')
                ->first();   
         return view('admin.settings.payoutreq_validation',compact("admin_email","admin",'title','logo','prv'));
      

    }
 
    public function updateprv(Request $request)
    {
        $min_amt = $request->min_amt;
        $min_days = $request->min_days;
        $this->validate(
            $request,
                [
                    'min_amt' => 'required',
                    'min_days'=>'required',
                ],
                [
                    'min_amt.required' => 'Enter minimum amount.',
                    'min_days.required' =>'Enter minimum days',
                ]
        );
        
        
        $check = DB::table('payout_req_valid')
               ->first();
       
    
      if($check){
        

        $update = DB::table('payout_req_valid')
                ->update(['min_amt'=> $min_amt,'min_days'=> $min_days]);
    
      }
      else{
          $update = DB::table('payout_req_valid')
                ->insert(['min_amt'=> $min_amt,'min_days'=> $min_days]);
      }
     if($update){
        return redirect()->back()->withSuccess('Updated Successfully');
     }
     else{
         return redirect()->back()->withErrors('Something Wents Wrong');
     }
    } 
     
     
}
