<?php

namespace App\Imports;
use DB;
use App\Product;
//use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

/*class ImportProduct implements ToModel
{*/
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    /*public function model(array $row)
    {
        return new Product([
            //
        ]);
    }*/
/*}*/


class ImportProduct implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        
        $i= 0;
        //dd(($rows));
        $err_msg_array=[];
        foreach ($rows as $row) 
        {
               if ($i == 0) {
                    // return null;
                }
                else
                {

                // $data=Customer::create([
             //            'first_name'     => $row[0],
                //        'last_name'    => $row[1], 
                //        'mobile_number'    => $row[2], 
                //         'email'    => $row[3], 
                //        'password' => \Hash::make('12345'),
             //        ]);
                    $digitPattern = '/^\+?\d[0-9]{0,5}/';
                    $emailPattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,5}$/';
                    $check=Product::where('product_name',$row[0])->first();
                    //$checkEmail=Product::where('email',$row[3])->first();
                    //dd(($row[1]));
                    //dd(preg_match($digitPattern, $row[1]));

                    #csv Export validation 
                    if(!isset($row[0]) || !isset($row[2]) || !isset($row[3]) || !isset($row[4]) || !isset($row[5]) || !isset($row[6]) || !isset($row[7]) || !isset($row[8]))
                    {
     
                        $err_msg_array[$i][]="Row ".$i.". Field is missing.";

                    }else if(preg_match($digitPattern, $row[2])!=true)
                    {
                        //dd(preg_match($digitPattern, $row[1])!=1);
     
                        $err_msg_array[$i][]="Row ".$i.". Please enter only number in category.";

                    }else if(preg_match($digitPattern, $row[3])!=true)
                    {
     
                        $err_msg_array[$i][]="Row ".$i.". Please enter only number in Quantity.";

                    }else if(preg_match($digitPattern, $row[5])!=true)
                    {
     
                        $err_msg_array[$i][]="Row ".$i.". Please enter only number in MRP.";

                    }else if(preg_match($digitPattern, $row[6])!=true)
                    {
     
                        $err_msg_array[$i][]="Row ".$i.". Please enter only number in Discount.";

                    }else if($check)
                    {
                        $err_msg_array[$i][]="Row ".$i.". product name already exist.";

                    }
                    /*$check=Customer::where('mobile_number',$row[2])->first();
                    $checkEmail=Customer::where('email',$row[3])->first();
                    if($check)
                    {

                        $err_msg_array[$i][]="Mobile number already used.";
                        

                    }else if($checkEmail)
                    {

                        $err_msg_array[$i][]="email id already used.";

                    }*/
                    else
                    {
                        $insertproduct = DB::table('product')
                            ->insertGetId([
                                'product_name' =>  $row[0],
                                'cat_id'  =>  $row[2],
                                'product_image' =>  "assets\img\default_image.png",
                                'product_approved_status' =>1,
                            ]); 

                        if($insertproduct)
                        {
                                /*$addmember=new CustomerCardDeatils();
                                $addmember->customer_id=$addproduct->id;
                                $addmember->card_level='gold';                
                                $addmember->merchant_id=\Auth::user()->id;
                                $addmember->card_number=$this->getNewCardNumber1(\Auth::user()->id,$addproduct->id);
                                $addmember->save();
                                $this->sendpassword($row[2],$password);*/

                                # store product varient.
                                DB::table('product_varient')
                                ->insert([
                                    'product_id'    => $insertproduct,
                                    'quantity'      => $row[3],
                                    'varient_image' => "assets\img\default_image.png",
                                    'unit'          => $row[4],
                                    'price'         => $row[7],
                                    'mrp'           => $row[5],
                                    'description'   => $row[8],
                                    'discount_percentage' => $row[6]
                                ]);

                                $productSynonymsNames   =  $row[1];
                                $productSynonymsNames = explode(',', $productSynonymsNames);
                                

                                if (!is_null($productSynonymsNames) && $productSynonymsNames[0]!='') {
  
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
                                //return redirect()->back()->withSuccess('Product Added Successfully');

                        }
                    }
                 
                }
            $i++;
        }
        //dd(isset($err_msg_array) && count($err_msg_array));

        if(isset($err_msg_array) && count($err_msg_array)){
            return redirect()->back()->withErrors($err_msg_array);
        }else{
            return redirect()->back()->withSuccess('Product uploaded Successfully');
        }
        
        //dd(($err_msg_array));
        //return redirect()->route('import.users')->with('error', 'list of error');
        return 1;
    }
 }   
