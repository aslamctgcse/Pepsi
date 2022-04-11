<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Traits\SendMail;
use App\Traits\SendSms;
use Razorpay\Api\Api;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Redirect;


class PayoutController extends Controller
{
    use SendMail;
    use SendSms;
    private $razorpayid="rzp_test_cn7PNije60Rutl";
    private $razorpaykey="r05UHvc4aYYIGrlyduGijDIG";

    public function pay_req(Request $request)
    {
         $title = "Store Payment Request";
         $admin_email=Session::get('bamaAdmin');
    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();
    	 $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
        
         $total_earnings=DB::table('payout_requests')
                            ->join('store', 'payout_requests.store_id', '=', 'store.store_id')
                            ->join('store_bank', 'payout_requests.store_id', '=', 'store_bank.store_id')
                           ->join('sub_orders','payout_requests.store_id','=','sub_orders.store_id')
                           ->leftJoin('store_earning','payout_requests.store_id','=','store_earning.store_id')
                           ->select('store.store_id','store.store_name', 'store.phone_number','store.address','store.email','store_earning.paid','payout_requests.payout_amt', 'payout_requests.complete','payout_requests.req_id','store_bank.ac_no', 'store_bank.ifsc','store_bank.holder_name','store_bank.bank_name', 'store_bank.upi', DB::raw('SUM(sub_orders.total_price)-SUM(sub_orders.total_price)*(store.admin_share)/100 as sumprice'))
                           ->groupBy('store.store_id','store.store_name', 'store.phone_number','store.address','store.email','store_earning.paid','store.admin_share','payout_requests.payout_amt', 'payout_requests.complete','payout_requests.req_id','store_bank.ac_no', 'store_bank.ifsc','store_bank.holder_name','store_bank.bank_name', 'store_bank.upi')
                           ->where('sub_orders.order_status','Completed')
                           ->where('payout_requests.complete', 0)
                           ->paginate(10);
      //dd($total_earnings);                     
                        
    	return view('admin.store.payoutRequest', compact('title',"admin", "logo","total_earnings"));
    }
    
    
     public function store_pay(Request $request)
    {
        dd($request->amt);
        $req_id = $request->req_id;
        
        $st = DB::table('payout_requests')
            ->where('req_id',$req_id) 
            ->first();
        $store_id=$st->store_id;
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
        $amt = $request->amt;
        $check = DB::table('store_earning')
                ->where('store_id',$store_id)
                ->first();
        $check2 = DB::table('store')
                ->where('store_id',$store_id)
                ->first();        
        $store_phone =  $check2->phone_number;
        
        if($check){
        $new_amount = $check->paid + $amt;    
        $update = DB::table('store_earning')
                ->where('store_id',$store_id)
                ->update(['paid'=>$new_amount]);
        }
        else{
         $update = DB::table('store_earning')
                ->insert(['store_id'=>$store_id,'paid'=>$amt]);   
                
                 DB::table('payout_requests')
                 ->where('req_id', $req_id)
                ->update(['complete'=>1]);
        }
        if($update){
             $sendmsg = $this->sendpayoutmsg($amt,$store_phone);
                
                $store_name = $check2->store_name;
                $user_email = $check2->email;  
                $app_name = $logo->name;
            /////send mail
               
          //  $welcomeMail = $this->payoutMail($amt,$store_name,$user_email,$app_name); 
            
            
            
             return redirect()->back()->withSuccess('Amount of '.$amt.' marked paid successfully against your request.');
        }
        else{
             return redirect()->back()->withErrors('Something Wents Wrong');
        }
    }


    //added code for payment beg
    public function initiate(Request $request){
      //dd($request->all());
         $storeId = $request->storeId;
         $admin_email=Session::get('bamaAdmin');
         $admin= DB::table('admin')
                   ->where('admin_email',$admin_email)
                   ->first();
         //$storeId = json_decode($request->all()['storeId']);
         $title="Payment Gateway";
         $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
         if ($request->isMethod('post')) {
          
            $api= new Api($this->razorpayid,$this->razorpaykey);
           

                  //generate random reciept id
                  $recieptid=Str::random(20);
                  //create order
                  $order = $api->order->create(array(
                    'receipt' => $recieptid,
                    'amount' => $request->all()['amt'] * 100,
                    'currency' => 'INR'
                    )
                  );
                  //lets return the response
                  //lets create the razorpay payment page

                  $response=[
                   'orderId' =>$order['id'],
                   'razorpayId' =>$this->razorpayid,
                   'amount' =>$request->all()['amt'],
                   'currency' => 'INR',
                   'payment_req_id' =>$request->all()['payment_req_id'],
                   'name' =>$request->all()['store_name'],
                   'email' => $request->all()['store_email'],
                   'phone' => $request->all()['store_phone'],
                   'store_phone' => $request->all()['store_phone'],
                   'store_ac_no' => $request->all()['store_ac_no'],
                   'store_ifsc' => $request->all()['store_ifsc'],
                   'store_holder_name' => $request->all()['store_holder_name'],
                   'store_bank_name' => $request->all()['store_bank_name'],
                   'store_upi' => $request->all()['store_upi'],
                   'description' => 'Dealwy Online Payment',
                   "paymenttype" => 'Online',
                   
                   ];
                  //dd($response);
                }
         
        return view('admin.store.payment-page',compact('response','logo','title','admin'));

    }

     //paymentend
    //complete pament
    public function complete(Request $request){
        //dd('make payment');
        // "rzp_paymentid" => "pay_HkZyC6Kyqa5PzC"
        // "rzp_orderid" => "order_HkZxq7AFh6UZvk"
        // "rzp_signature" => "1f3986c8989b7d6c0920dcf8e9f167d8c65473ba7e5429691145d25fa3909801"
        // "store_name" => "Vishal Mega Mart"
        // "store_phone" => "9999123456"
        // "store_email" => "mart@gmail.com"
        // "payment_req_id" => "3"
        // "store_ac_no" => "12345679123"
        // "store_ifsc" => "DEMO1234"
        // "store_holder_name" => "DEMO"
        // "store_bank_name" => "DEMO"
        // "store_upi" => "demo@ybl"
        // "amount" => "271.6"
        // "paymenttype" => "Online"
        //dd($request->payment_req_id);
        $paymentId = $request->rzp_paymentid;
        $payment_order_Id = $request->rzp_orderid;
        $store_name = $request->store_name;
        $store_phone = $request->store_phone;
        $store_email = $request->store_email;
        $store_ac_no = $request->store_ac_no;
        $store_ifsc = $request->store_ifsc;
        $store_ac_no = $request->store_ac_no;
        $store_holder_name = $request->store_holder_name;
        $store_bank_name = $request->store_bank_name;
        $store_upi = $request->store_upi;
        $payment_req_id = $request->payment_req_id;
        $req_id = $request->payment_req_id;
        $amount = $request->amount;
        
        
        $st = DB::table('payout_requests')
            ->where('req_id',$req_id) 
            ->first();
        $store_id=$st->store_id;
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
        $check = DB::table('store_earning')
                ->where('store_id',$store_id)
                ->first();
        $check2 = DB::table('store')
                ->where('store_id',$store_id)
                ->first();        
        $store_phone =  $check2->phone_number;
        
        if($check){
        $new_amount = $check->paid + $amount;    
        $update = DB::table('store_earning')
                ->where('store_id',$store_id)
                ->update(['paid'=>$new_amount]);
        }
        else{
         $update = DB::table('store_earning')
                ->insert(['store_id'=>$store_id,'paid'=>$amount]);   
                
                 DB::table('payout_requests')
                 ->where('req_id', $req_id)
                ->update(['complete'=>1]);
        }
        if($update){
             $sendmsg = $this->sendpayoutmsg($amount,$store_phone);
                
                $store_name = $check2->store_name;
                $user_email = $check2->email;  
                $app_name = $logo->name;
            /////send mail
               
          //  $welcomeMail = $this->payoutMail($amt,$store_name,$user_email,$app_name); 
            
            
            
             return redirect('/payout_req')->withSuccess('Amount of '.$amount.' marked paid successfully against your request.');
        }
        else{
             return redirect('/payout_req')->withErrors('Something Wents Wrong');
        }

    }    
       
}
