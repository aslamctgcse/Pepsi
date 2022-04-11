<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Carbon\Carbon;

class DeliveryTypeController extends Controller
{
   
    
        
    
      public function deliveryType(Request $request)
    {
        
        $title="Delivery Type";
    	 
    	$admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();	
                
            $delivery_types = DB::table('delivery_type')
                ->get();   
         return view('admin.settings.delivery_type',compact("admin_email","admin",'title','logo','delivery_types'));
      

    }

    public function AddDeliveryType(Request $request)
    {
    
        $title = "Add Delivery Type";
         $admin_email=Session::get('bamaAdmin');
       $admin= DB::table('admin')
             ->where('admin_email',$admin_email)
             ->first();
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
           $deliveryType = DB::table('delivery_type')
                    ->get();
        
        
        return view('admin.settings.add_delivery_type',compact("deliveryType", "admin_email","logo", "admin","title"));
     }

      public function AddNewDeliveryType(Request $request)
    {
      
        $delivery_type = $request->del_type_name;
        $status = 1;
        $time = $request->del_type_time;
        $fee = $request->fee_amount;
        if($fee==NULL){
          $fee=0;
        }

        $this->validate(
            $request,
                [
                    
                    'del_type_name' => 'required',
                    'fee_amount'=>'numeric',
                   // 'cat_image' => 'required|mimes:jpeg,png,jpg|max:400',
                ],
               /* [
                    'del_type_time.required' => 'Enter category name.',
                ],
                */
                [
                    'del_type_fee.required' => 'Enter fee amount.',
                    'del_type_fee.number' => 'Enter fee amount in digits.',
                ]
        );

        $insertCategory = DB::table('delivery_type')
                            ->insert([
                                'delivery_type'=>$delivery_type,
                                'time'=>$time,
                                'fee'=>$fee,
                                'status'=>$status,                               
                            ]);
          
        
        if($insertCategory){
            return redirect()->back()->withSuccess('Delivery Type Added Successfully');
        }
        else{
            return redirect()->back()->withErrors("Something Wents Wrong");
        }
      
    }
    
    public function EditDeliveryType(Request $request)
    {
       
         $delivery_type_id = $request->id;

         $title = "Edit Delivery Type";
         $admin_email=Session::get('bamaAdmin');
       $admin= DB::table('admin')
             ->where('admin_email',$admin_email)
             ->first();
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
          $deliveryType = DB::table('delivery_type')
                    ->where('id',$delivery_type_id)
                    ->first();
          //dd($deliveryType);          

        return view('admin.settings.edit_delivery_type',compact("deliveryType","admin_email","admin","logo","title"));
    }


 
    public function updateDeliveryType(Request $request)
    {
        $id = $request->delivery_type_id;
        $typeName = $request->del_type_name;
        $time = $request->del_type_time;
        $fee = $request->fee_amount;
        if($fee==NULL){
          $fee=0;
        }
        $this->validate(
            $request,
                [
                    'del_type_name'=>'required',
                    'delivery_type_id'=>'required',
                    'fee_amount'=>'numeric',
                ],
                [
                    'del_type_name.required' =>'Enter type name',
                    'delivery_type_id'=>'Enter delivery type id',
                ]
        );
        
        
        $check = DB::table('delivery_type')
                ->where('delivery_type', $typeName)
                ->where('id','!=',$id)
               ->get();

           
       
    
      if(count($check)>0){
         return redirect()->back()->withErrors('Delivery type name already exist');

      }
      else{
        //dd(count($check)>0);
          $update = DB::table('delivery_type')
                ->where('id', $id)
                ->update([
                  'delivery_type'=> $typeName,
                  'time'=> $time,
                  'fee'=> $fee
                ]);
        return redirect()->back()->withSuccess('Updated Successfully');       
          
      }
     if($update){
        return redirect()->back()->withSuccess('Updated Successfully');
     }
     else{
         return redirect()->back()->withErrors('Something Wents Wrong');
     }
    }


    /**
     * Block unblock delivery type
     * 
     * @param $request
     */
    public function deliveryTypeUnblock(Request $request)
    {
        # get delivery type id.
        $id = $request->id;
  
        $deliveryType = DB::table('delivery_type')
                    ->where('id', $id)
                    ->update(['status' => 0]);
                    
        if($deliveryType) {   
            return redirect()->back()->withSuccess('Delivery Type Blocked Successfully');
        } else {
          return redirect()->back()->withErrors('Something Wents Wrong');   
        }
    }

    /**
     * Block unblock delivery type
     * 
     * @param $request
     */
    public function deliveryTypeblock(Request $request)
    {
        # get delivery type id.
        $id = $request->id;
  
        $deliveryType = DB::table('delivery_type')
                    ->where('id', $id)
                    ->update(['status' => 1]);
                    
        if($deliveryType) {   
            return redirect()->back()->withSuccess('Delivery Type Active Successfully');
        } else {
          return redirect()->back()->withErrors('Something Wents Wrong');   
        }
    }
      
     
     
}
