<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use DB;
use Illuminate\Support\Facades\Hash;
date_default_timezone_set('Asia/Manila');

class ProfileController extends Controller
{

    //Retreiving of Data.
    function getData(){
        $data['data'] = DB::table('users')->get()
                        ->where('id', '=', auth()->user()->id);

        if(count($data) > 0){
            return view('pages/profile_page', $data);
        }
        else{
            return view('pages/profile_page');
        }
    }

    public function update(Request $request, $id)
    {
        $updateprofile = user::findOrFail(auth()->user()->id);
        $updateprofile->username =  $request['username'];
        $updateprofile->email = $request['email'];
        $updateprofile->fname = $request['fname'];
        $updateprofile->lname = $request['lname'];
        $updateprofile->password = Hash::make($request['newpassword']);

        if($image = $request->file('image') != NULL){
            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            $image = $request->file('image');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(\public_path("images"), $new_name);

            $updateprofile->image = $new_name;
        }

        $password = DB::table('users')->where('id', auth()->user()->id)->value('password');
        $inputpass = Hash::make($request['curpassword']);

        if ($request['username'] == NULL || $request['email'] == NULL || $request['fname'] == NULL || $request['lname'] == NULL || $request['curpassword'] == NULL) {
            $notification = array(
                'message'=> 'Please fill up required fields!',
                'alert-type' => 'error'
            );
        }
        elseif(!Hash::check($request['curpassword'], $password)){
                $notification = array(
                    'message'=> 'Incorrect Password! Please Contact your Administrator!',
                    'alert-type' => 'error'
                );
        }else{
            $updateprofile->save();
            $notification = array(

                'message'=> 'Profile updated successfully!',
                'alert-type' => 'success'
            );

        }

        return back()->with($notification);

    }

}
