<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\item;
use App\category;
use App\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
date_default_timezone_set('Asia/Manila');

class UsersController extends Controller
{
    // for authentication
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            $data = User::latest()->where('usertype', '=', 'admin')->get();
            return DataTables::of($data)
                ->addColumn('name', function($data){
                    $name= $data->fname." ".$data->lname;
                    return $name;
                })
                ->addColumn('created_at', function($data){
                    $date = '<span style="cursor: context-menu" title="'.date_format($data->created_at, 'l, M d, Y h:i:s A').'">'.$data->created_at.'</span>';
                    return $date;
                })
                ->addColumn('action', function($data){
                    $button = '<span name="edit" id="'.$data->id.'" class="edit table-button cursor-pointer mr-3"
                    data-userid="'.$data->id.'"
                    data-username="'.$data->username.'"
                    data-fname="'.$data->fname.'"
                    data-lname="'.$data->lname.'"
                    data-email="'.$data->email.'"
                    data-password="'.$data->password.'"
                    data-toggle="modal" data-target="#modal-edit-user"><a>
                    <i class="fas fa-edit text-danger"></i>
                    </a></span>';
                    $button .= '<span class="table-button cursor-pointer delete" name="delete" id="'.$data->id.'"
                    data-fname="'.$data->fname.'"
                    data-lname="'.$data->lname.'"
                    data-userid="'.$data->id.'"
                    data-toggle="modal" data-target="#modal-delete-user"><a><i class="fas fa-trash text-danger"></i></a></span>';
                    return $button;
                })
                ->rawColumns(['action', 'created_at', 'name'])
                ->make(true);
        }
        return view('pages/users_page');
    }

    public function update(Request $request)
    {
        $updateUser = User::findOrFail($request->input('eID'));
        $updateUser->username =  $request->input('eUsername');
        $updateUser->email = $request->input('eEmail');
        $updateUser->fname = $request->input('eFirstName');
        $updateUser->lname = $request->input('eLastName');
        $updateUser->password = Hash::make($request->input('ePassword'));

        $emailValidation="/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/";

        if(!preg_match($emailValidation, $request->input('eEmail'))){
            return response()->json(['errEmail'=>'Email is invalid']);
        }
        if($request->input('ePassword') != $request->input('eConfPassword')){
            return response()->json(['errPass'=>'Passwords did not match']);
        }
        if(strlen($request->input('ePassword')) < 8){
            return response()->json(['errPassWeak'=>'Password is weak']);
        }

        if(DB::table('users')->where('email', '=', $request->input('eEmail'))->where('id', '!=', $request->input('eID'))->exists()){
            return response()->json(['errEmail'=>'Email exists']);
        }elseif(DB::table('users')->where('username','=', $request->input('eUsername'))->where('id', '!=', $request->input('eID'))->exists()){
            return response()->json(['errUsername'=>'Username already Exists!']);
        }else{
            $updateUser->save();
            return response()->json(['success'=>'Successfully updated']);
        }
    }

    public function destroy(Request $request)
    {
        $deleteUser = $request->input('dID');
        User::find($deleteUser)->delete();
        return back();
    }

    public function insert(Request $request)
    {
        $name = "/^[A-Z][a-z A-Z ]+$/";
        $emailValidation="/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/";
        $number="/^[0-9]+$/";
        $username = $request->input('username');
        $email = $request->input('email');
        $fname = $request->input('fname');
        $lname = $request->input('lname');
        $password = $request->input('password');
        $confPassword = $request->input('confPassword');
        $img = "";

        if(strlen($password) < 8){
            return response()->json(['errPassWeak'=>'Password is weak']);
        }
        if($password != $confPassword){
            return response()->json(['errPass' => 'Passwords did not match!']);
        }

        if(!preg_match($emailValidation, $email)){
            return response()->json(['errEmail' => 'Email is invalid! Please re-enter your email']);
        }




        $user = array('username'=>$username, 'email'=>$email, 'fname'=>$fname, 'lname'=>$lname, 'password'=>Hash::make($password), 'usertype'=>'admin', 'image'=>'default.png', 'created_at'=> NOW());
        if(DB::table('users')->where('email', '=', $email)->exists()){
            return response()->json(['errEmail'=>'Email already exists!']);
        }elseif(DB::table('users')->where('username','=',$username)->exists()){
            return response()->json(['errUsername'=>'Username already Exists!']);
        }else{
            User::insert($user);
            return response()->json(['success'=>'Successfully Inserted!']);
        }

        User::insert($user);


    }






}
