<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Order;
use App\Orderproduct;
use DB;
use Session;
use Mail;
//use PDF;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    //
    public function productlist(Request $request){
    	
    	$productlist=Product::all();
        //$productlist=Product::where('stock','=',1)->get();
        
        
        $comboarray=array();
        foreach($productlist as $productlists){
           // dd($productlists->combo);
           
            if(isset($productlists->combo)){
                if($productlists->combo == 'offer'){
                    $combo='a';
                }
                if($productlists->combo == 'combo'){
                    $combo='b';
                }
                if($productlists->combo == 'others'){
                    $combo='c';
                }
                 if($productlists->combo == ''){
                    $combo='d';
                }
                if($productlists->combo == 'daily_breakfast'){
                    $combo='e';
                }
                if($productlists->combo == 'daily_fresh_fruit'){
                    $combo='f';
                }
                if($productlists->combo == 'daily_vegg'){
                    $combo='g';
                }
                if($productlists->combo == 'daily_poltry_form'){
                    $combo='h';
                }
                 if($productlists->combo == ''){
                    $combo='i';
                }
               
                
               //$comboarray[$productlists->combo][]= $productlists;
               $comboarray[$combo][]= $productlists;
            }  
        }       
       //dd($comboarray); 
      ksort($comboarray);  

    	return view('products.products',compact('productlist','comboarray')); 

    }
    // public function addtocart(request $request){
    // 	$

    // }
    //added code for add to cart beg
    public function addtocart(Request $request,$id,$type=''){



 if(!empty($id)){
 $pid=$id;  //dd($pid);
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

//inc dec qty beg
        //if(!empty($type) && $type == 'inc'){
        if($type == 'inc'){


            foreach($cartpids['pid'] as $k=>$v){
                 if($pid==$k){
                
                 $tempids['pid'][$k]=$v+1;
              
                 }
            }

            
        }
        if($type == 'dec'){


            foreach($cartpids['pid'] as $k=>$v){
                 if($pid==$k){
                
                 $tempids['pid'][$k]=$v-1;
              
                 }
            }

            
        }
    //inc dec qty end

    //added code for dec qty beg
         //if(!empty($type) && $type == 'dec'){
     //     if(isset($type) && $type == 'dec'){


     //        foreach($cartpids['pid'] as $k=>$v){
     //     if($pid==$k){
     //        $tempids['pid'][$k]=$v-1;
     //        //$newid=false;
     //     }else{
     //        $tempids['pid'][$k]=$v;
     //     }
     // }

            
     //    }

    //added code for dec qty end

    Session::put('cartpids', $tempids); //session put
     $cartpids = Session::get('cartpids'); //get  session again updated

    //added code 13 oct total item beg 
 $ids=array();
 $qty=array();
 $totalcartqty=0; //to show cart total qty on top
 //$totalcartitem=0; //to show cart total qty on top
 $products=array();
//  $productcount = 0;

 if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
     foreach ($cartpids['pid'] as $k=>$v){
         $ids[$k]=$k;
         $qty[$k]=$v;
         $totalcartqty+=$v;
     }
     if(is_array($ids) && count($ids)>0){
         $products=Product::select('*')->whereIn('id',$ids)->get();
        // $productcount=Product::select('*')->whereIn('id',$ids)->distinct()->count(); //added for count
         $productcount=Product::select('*')->whereIn('id',$ids)->count(); //added for count
        // $productcount=count($productcount); //added for count
     }
 }

 Session::put('totalcartqty', $productcount);
 $totalitem=Session::get('totalcartqty');
    //added code 13 oct total item end
 
return response()->json($totalitem);
 }else{
return response()->json('0');
 }
}
    //added code for add to cart end



//addede code to show cart item beg
public function cart(Request $request){
   // dd('vhhgv');

     $cartpids = Session::get('cartpids'); 
     //added code to update cart items after remove product beg
    if(isset($this->cartpids)){
        //dd($this->cartpids);
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
         $products=Product::select('*')->whereIn('id',$ids)->get();
         $productcount=Product::select('*')->whereIn('id',$ids)->distinct()->count(); //added for count
        // $productcount=count($productcount); //added for count
     }
 }
 //dd($productcount);
 //Session::put('totalcartqty', $totalcartqty);
 Session::put('totalcartqty', $productcount);

 //return view('carts.cart',compact('ids','qty','products'));
 return view('carts.cart',compact('ids','qty','products','totalcartqty'));
}
//addede code to show cart item end

//added code for checkout beg
public function checkout(Request $request){

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
         $products=Product::select('*')->whereIn('id',$ids)->get();
         
         //manipulate order total beg
         foreach($products as $product){
            $ordertotal+=$product->pprice * $qty[$product->id];
            
         }
        
         //manipulate order total end
     }
 //form submit beg
    $orderno='';
    if(isset($request->submit)){
        //insert order
        $insorder=new Order;
        $insorder->customer_name=$request->ufname.' '.$request->ulname;
        $insorder->customer_email=$request->uemail;
        $insorder->customer_address=$request->ushippaddr;
        $insorder->total=$request->ordertotal;
        $insorder->save();
        //insert orderproducts
        //dd($request->all());
        $year= substr(date("Y"),-2);

      $month=date('m');

       $date=date('d');
        
       $orderno=$year.$month.$date.'-'.$insorder->id;
        
        //order total beg
    $i=1;
    $html='<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border:1px solid #000;width:500px;" align="center">
    <tr><th colspan="5">ORDER NO :'.' '. $orderno.'</th></tr>
    <tr><th >S.No</th><th>Item</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>';
    $ordertotal=0;
    if(($cartpids) && is_array($cartpids['pid']) && count($cartpids['pid'])>0){
     foreach ($cartpids['pid'] as $k=>$v){
         $ids[$k]=$k;
         $qty[$k]=$v;
     }
     if(is_array($ids) && count($ids)>0){
         $products=Product::select('*')->whereIn('id',$ids)->get();
         //dd($products);
         
         //manipulate order total beg
         foreach($products as $product){
            $ordertotal+=$product->pprice * $qty[$product->id];
            $html.='<tr><td>'.$i.'</td><td>'.$product->pname.'</td><td>'.$product->pprice.'</td><td>'.$qty[$product->id].'</td><td>'.$product->pprice * $qty[$product->id].'</td></tr>
            ';
            $i++;
         }
         $html.='<tr><td colspan="4">Total</td><td>'.$ordertotal.'</td></tr>';
         //manipulate order total end
     }
 }
  $html.='<tr style="text-align: left;"><td colspan="5"><p><b>Name: </b> '.ucfirst($request->ufname).' '.$request->ulname .'</p><p><b>Phone: </b>'.$request->uphone.'</p>
        <p><b>Email: </b>'.$request->uemail.'</p><p><b>Address: </b>'.$request->ushippaddr .'</p></td></tr></table>';

        $data['html']='<h2 style="text-align:center">Order Description</h2>
        <h3 style="text-align:center">Cart Items</h3>'.$html;
        $data['email']=$request->uemail;
        //$data['name']=$request->ulname;
        $data['name']=$request->ufname.' '.$request->ulname;

        $email = $request->uemail;


 
    //order total end
 $datescv=date('d-F-Y');
        $i=1;
         $pimgurl= url('/img/logo.png');

        //added code to make pdf beg
        //     $orderhtm='<table border="1" cellpadding="2" cellspacing="0" style=" border:1px solid #000;font-size:11px;width: 600px;font-size: 20px;" align="center">';
        // $orderhtm.='
        //     <tr><th colspan="5" style="padding-top:10px"><center><img src="'. $pimgurl.'"></center><br /></th></tr>
        //      <tr><th colspan="5" style="background:yellow;"><center><b>Invoice '.$orderno.'</b></th></tr>
        //     <tr>
             
        //      <td><b>Date</b></td>
        //      <td colspan="4">'.$datescv.'</td>
        //     </tr>
           
        //    <tr>
        //      <td colspan><b>Name</b></td><td  colspan="4">'.$request->ufname.' '.$request->ulname.'</td> 
        //    </tr>
        //    <tr>
        //       <td><b>Address</b></td><td  colspan="4">'.$request->ushippaddr.'</td>
        //    </tr>
        //    <tr>
        //     <td><b>Phone Number</b></td><td  colspan="4">'.$request->uphone.'</td>
        //    </tr> 
        //    <tr style="background: #8a8a80;">
        //     <th>S.NO.</th>
        //     <th>Items</th>
        //     <th>Rate</th>
        //     <th>Quantity</th>
        //     <th>Amit(Rs)</th>
        //    </tr>
        //     ';
            
         
           //added code to make pdf end
         $timer = time();
     // $filename = "sfs-".$insorder->id.".csv"; 
      $filename = $orderno.".csv"; 
      $dirorder=public_path()."/order_csv/".$filename;
      //$downloadfile="/images/download/".$filename;
     // $downloadfile="/images/download/".$filename;
        $fhl=fopen($dirorder,"w");
       
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
        'address'=>'Address',
         'b'=>$request->ushippaddr,
        'a'=>'',
        'c'=>'',
        'd'=>'',
        
           
            ));
           fputcsv($fhl, array(
        'bl4'=>'',
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
        //
            
            fputcsv($fhl, array('sno'=>'S.No',
            'product'=>'Items',
            'productunit'=>'Unit',
           
            'rate'=>'Rate',
            'quantity'=>'Quantity',
            'amount'=>'Amt(Rs)'
            
            ));


        foreach($products as $product){
            $subt=$product->pprice * $qty[$product->id];
        $insoproduct=new Orderproduct;
        $insoproduct->product_name=$product->pname;
        $insoproduct->product_price=$product->pprice;
        $insoproduct->product_qty=$qty[$product->id];
        $insoproduct->order_id=$insorder->id;
        $insoproduct->save();
        //
        // $orderhtm.='<tr>
        //     <td>'.$i.'</td>
        //     <td>'.$product->pname.'(' .$product->quantity.''.$product->unit.')'.'</td>
        //     <td>'.$product->pprice.'</td>
        //     <td>'.$qty[$product->id].'</td>
        //     <td>'.$subt.'</td>
        //    </tr>';
        //
        //added code for putcsv beg dsdsf 
          
            fputcsv($fhl, array('sno'=>$i,
           // 'product'=>$product->pname.'(' .$product->quantity.''.$product->unit.')',
            'product'=>$product->pname,
            'productunit'=>$product->quantity.''.$product->unit,
            'rate'=>$product->pprice,
            'quantity'=>$qty[$product->id],
              
             
            'amount'=> $subt
            ));

          
        
        //end end csv
            $i++;

    }  
    fputcsv($fhl, array(
        'total'=>'Total',
        'a'=>'',
        'b'=>'',
        'c'=>'',
        'e'=>'',
        'tvalue'=>$request->ordertotal,
        'd'=>'',
           
            )); 
       //       $orderhtm.='<tr style="background: #8a8a80;">
       //        <td><b>Total</b></td>
       //        <td colspan="4" style="text-align:right;"><b>'.$request->ordertotal.'</b></td>
       //       <tr>';      
       // $orderhtm.='</table>';  
       //added code to save pdf beg
        if(!file_exists(public_path().'/order_csv/'.$orderno.'.pdf')){
             $my_file = public_path().'/order_csv/'.$orderno.'.pdf';
            $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
            fclose($handle);
         }
////$pdf= PDF::loadHTML($orderhtm)->save(public_path().'/order_csv/'.$orderno.'.pdf');
        // sleep(15);
       //added code to save pdf end
        //echo $orderhtm;
       // dd('here');
        //fclose($file);            
            fclose($fhl);
           // dd($dirorder);
           // $data['fn']=$filename;  
           // $data['fn']=$filenamebcbj;  Email credentials:-sfsdailyneeds@gmail.com

            $emails=array();
            $allMails = [$email,'sfsdailyneeds@gmail.com']; 
            $emails['fl']=public_path()."/order_csv/".$filename;
            /////////////////////////////
           $a=  Mail::send('email.reply', ['data' => $data,'emails' => $emails] , function ($message) use ($email,$emails,$allMails)
        {

            $message->from($email, '');
            $message->subject('Order summary');
           
           // $message->to(['sfsdailyneeds@gmail.com']); 
            $message->to($allMails);   
            
            $message->attach($emails['fl']);
          
        
        }); 
        //send email from admin to user beg
         Mail::send('email.reply', ['data' => $data,'emails' => $emails], function ($message) use ($email,$emails)
         {

            $message->from('sfsdailyneeds@gmail.com', '');
            $message->subject('Order summary');
            $message->to($email);
            $message->attach($emails['fl']);
           // $message->send(new MyDemoMail())

        });
        /*  Mail::send('email.reply', ['data' => $data,'emails' => $emails], function ($message) use ($email,$emails)
        {

            $message->from($email, 'SFSdailyneeds');
            $message->subject('Order summary');
            $message->to('sfsdailyneeds@gmail.com');
            $message->attach($emails['fl']);
          

        });*/


            ////////////////////////////////////////////
        

        //added code to insert order data in orders and orderproducts table end
        Session::forget('cartpids');
        Session::forget('totalcartqty');

        return redirect('/checkout')->with('msg','order placed successfully.check your email');
    }
    
    //form end
 //dd($filename);
    
    //return view('checkout.checkout',compact('cartpids','ordertotal'));
    return view('checkout.checkout',compact('cartpids','ordertotal'));


}
//added code for checkout end
//added code for checkout end
//added code to insert product beg
public function addproduct(Request $request){
     //dd('hhdf');
   // dd(Product::all());
    //insert product beg
    if(isset($request->productsubmit)){
        //dd($request->all());
        $insproduct= new Product;
        $insproduct->pname=$request->productname;
        $insproduct->pprice=$request->productprice;
        $insproduct->combo=$request->combo;
        $insproduct->pdesc=$request->mrp;
        $insproduct->unit=$request->unit;
        $insproduct->stock=1;
        $insproduct->quantity=$request->quantity;
        
        //insert image beg
        $name='img/logo.png';
        if ($request->hasFile('productimage')) {
                $files = $request->productimage;
                $name = time() . "_" . $files->getClientOriginalName();
               //$image = $files->move(public_path() . '/../img', $name);
               $image = $files->move(public_path() . '/img/product', $name);
                $insproduct->pimage = 'img/product/'.$name;
            }
        //insert image end 

           $insproduct->save();
           return redirect('/add-product')->with('msg','Product has been added successfully');

    }
    //insert product end
    
    $productlist=Product::orderBY('id', 'desc')->get();
    	
    return view('admin.products.addproduct', compact('productlist'));

}


public function editProductForm($id) {
    
    $product = Product::find($id);
    return view('admin.products.editproduct', compact('product'));
}

//added code for checkout end
//added code to insert product beg
public function editproduct(Request $request, $id)
{
    $product = Product::find($id);
   // dd($product);
    
    //insert product beg
    if(isset($request->productsubmit)){
        //dd($request->all());
        // $insproduct= new Product;
        
        $product->pname=$request->productname;
        $product->pprice=$request->productprice;
        $product->combo=$request->combo;
        $product->pdesc=$request->mrp;
         $product->unit=$request->unit;
        $product->quantity=$request->quantity;

        //insert image beg
        $name='img/logo.png';
        if ($request->hasFile('productimage')) {
                $files = $request->productimage;
                $name = time() . "_" . $files->getClientOriginalName();
               //$image = $files->move(public_path() . '/../img', $name);
               $image = $files->move(public_path() . '/img/product', $name);
            $product->pimage = 'img/product/'.$name;
        }

           $product->save();

           return redirect('/add-product')->with('msg','Product has been updated successfully');

    }
    //insert product end
    
    $productlist=Product::orderBY('id', 'desc')->get();
    	
    return view('admin.products.addproduct', compact('productlist'));

}

public function deleteProduct($id) {
    
    $product = Product::find($id)->delete();
    return redirect('/add-product')->with('msg','Product has been deleted successfully');
}

public function orderExport() {
    
    $transactions = Order::get();
    $today_date=date('Y-m-d');
        \Excel::create('Order'.$today_date, function($excel) use ($transactions) {
            $excel->sheet('sheet1', function($sheet) use ($transactions) {
            $sheet->setRightToLeft(true);
            $sheet->fromArray($transactions, null, 'A1', false, true);
             });
         })->download('xls'); 
         
    return redirect()->back();
}

//added code to insert product end
//added code to show orders list with producr detail beg
public function orders(Request $request){
    $orderdatas= DB::table('orders')->select('orderproducts.*','orders.*','orders.id as oid')
    ->join('orderproducts','orders.id','=','orderproducts.order_id')
    ->orderBy('orders.id', 'DESC')
    ->get();
    $orderdetail=array();
    //dd($orderdata);
    foreach($orderdatas as $orderdata){
      $orderdetail[$orderdata->oid][]=$orderdata;
      $userdetail[$orderdata->oid]=$orderdata;
    }

//dd($orderdetail);
    return view('admin.orders.orders',compact('orderdetail','userdetail'));
}


    // added code to remove product beg
    public function removeproduct(Request $request,$id)
    {
        $cartpids = Session::get('cartpids'); 

        unset($cartpids['pid'][$id]); 
        Session::put('cartpids', $cartpids);
         $cartpids = Session::get('cartpids'); 
       // return $cartpids;
         //////////////////
         
         /////////////////////


    }
    //single product page beg
    public function productDetail(Request $request,$id)
    {
      dd('here single');
        // $singleproduct=Products::where('id','$id')->first();
        // dd($singleproduct); 

        return view('products.single-product');
    }
    //single product page end


//added code to mange out of stock beg
 public function managestock(Request $request){
//dd($request->all());
    
    if($request->managestock == 1) {  
      
    $updatestock=DB::table('products')
                 ->where('id','=',$request->productid)
                 ->update(['stock' => 0]);
             }else{
                $updatestock=DB::table('products')
                 ->where('id','=',$request->productid)
                 ->update(['stock' => 1]);
             }
        //return redirect()->with('msg','product updated successfully');
        return redirect('/add-product')->with('msg','stock updated');
 }
//added code to mange out of stock end




//added code to show orders list with producr detail end
}
