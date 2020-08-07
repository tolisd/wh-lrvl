<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Assignment; //the Assignment Model
use App\Import;
use App\Export;

class AssignmentController extends Controller
{

    //VIEW All (Import and Export) Assignments (their counts)
    public function view_all_assignments(Request $request){

        //4 types(roles) of authorised users
        $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){

            $imp_assignments_count = Import::count(); //get row count from 'imports' table
            $exp_assignments_count = Export::count(); //get row count from 'exports' table

            return view('assignments_view', ['imp_assignments_count' => $imp_assignments_count,
                                             'exp_assignments_count' => $exp_assignments_count,]); //sending the $assignment variables (counts) to this Blade View.

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    //VIEW Import Assignments
    public function view_import_assignments(Request $request){

        //4 types(roles) of authorised users
        $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){

            $import_assignments = DB::table('imports')->get();  //get all rows from 'import' table

            //return view('', []);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    //VIEW Export Assignments
    public function view_export_assignments(Request $request){

         //4 types(roles) of authorised users
         $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

         if ($authenticatedUser){

            $export_assignments = DB::table('exports')->get(); //get all rows from 'export' table

            //return view('', []);
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }

    }

    //VIEW a single Import Assignment (by its id)
    public function view_import_assignment_byId(Request $request, $id){

        //4 types(roles) of authorised users
        $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){

           //return view();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    //VIEW a single Export Assignment (by its id)
    public function view_export_assignment_byId(Request $request, $id){

        //4 types(roles) of authorised users
        $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){

          //return view();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }



    //CREATE a New Import Assignment
    public function create_import_assignment(Request $request){

         //4 types(roles) of authorised users
         $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

         if ($authenticatedUser){

            $import_assgnm = new Import();

            //insert 'ImportAssignment' fields here via $request



            $import_assgnm->save();

            return back();
         } else {
            return abort(403, 'Sorry you cannot view this page');
         }

    }

    //CREATE a New Export Assignment
    public function create_export_assignment(Request $request){

         //4 types(roles) of authorised users
         $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

         if ($authenticatedUser){

            $export_assgnm = new Export();

            //insert 'ExportAssignment' fields here via $request



            $export_assgnm->save();

            return back();
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }

    }


    //UPDATE/EDIT an Existing Iport Assignment
    public function update_import_assignment(Request $request, $id){

         //4 types(roles) of authorised users
         $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

         if ($authenticatedUser){

            $import_assgnm = Import::findOrFail($id);

            //insert fields here



            $import_assgnm->update($request->all());


            return back();
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }

    }

    //UPDATE/EDIT an Existing Export Assignment
    public function update_export_assignment(Request $request, $id){

         //4 types(roles) of authorised users
         $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

         if ($authenticatedUser){

            $export_assgnm = Export::findOrFail($id);

            //insert fields here



            $export_assgnm->update($request->all());

            return back();
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }

    }


    //DELETE an Existing Import Assignment (needs the appropriate rights in order to delete it..)
    public function delete_import_assignment(Request $request, $id){

         //4 types(roles) of authorised users
         $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

         if ($authenticatedUser){

            $import_assgnm = Import::findOrFail($id);
            $import_assgnm->delete();

            return back();
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }

    }

    //DELETE an Existing Export Assignment (needs the appropriate rights in order to delete it..)
    public function delete_export_assignment(Request $request, $id){

         //4 types(roles) of authorised users
         $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

         if ($authenticatedUser){

            $export_assgnm = Export::findOrFail($id);
            $export_assgnm->delete();

            return back();
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }

    }

}
