<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;

class QueryController extends Controller
{
    public function list(Request $request)
    {

        $title = "Query List";
         $admin_email=Session::get('bamaAdmin');

    	 $admin= DB::table('admin')
    	 		   ->where('admin_email',$admin_email)
    	 		   ->first();

    	  $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();

           $queries = DB::table('contact_us')
                   ->orderBy('created_at','desc')
                    //->paginate(20); //commented by me
                    ->get(); //added by azwar
           
        
    	return view('admin.query.list', compact('title',"admin", "logo","queries"));
    }

    
    /**
     * Query Detail.
     * 
     * @param $request
     */
    public function queryDetails(Request $request)
    {
      $query_id = $request->id;
      $title = "Query";
      $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();

      $details  =   DB::table('contact_us')
                       ->where('id', $query_id)
                       ->get();
      $admin_email=Session::get('bamaAdmin');

      $admin= DB::table('admin')
             ->where('admin_email',$admin_email)
             ->first();                 

      if ($details) {   

        return view('admin.query.list_view', compact('details','logo','title','admin', 'query_id'));
      } else {
        return redirect()->back()->withErrors('Something Wents Wrong');   
      }
    }
}