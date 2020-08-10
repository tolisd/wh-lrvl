<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Product;
use App\Type;


class ToolController extends Controller
{
    //Tool(s) are A Type of Product(s)

    public function view_tools(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $type = DB::table('types')->where('name', '=', 'tool')->get();
            $tools = DB::table('products')->where('type_id', '=', '1')->get();  //this line is not correct..


            return view('tools_view', ['tools' => $tools]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    //if you need to create a new tool, then simply create a new Product where type='tool';
    //the same for update & delete


    //WHICH tools are chrarged (and perhaps to WHOM via user_id)
    public function view_charged_tools(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){


            return view('charged_tools_view');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    //A Warehouse Worked CAN view WHICH tools he was CHARGED with.
    public function view_my_charged_tools(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseWorker'])){


            return view('charged_tools_view');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }



}
