<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Admin;
use Mail;
use Auth;
use Session;
use Redirect;
use Image;
use DB;
use URL;
class UserController extends Controller
{
    
    
    public function index()
   {
       
   }

   public function store(Request $request)
   {
       
   
   }

   public function update(Request $request, $id)
   {
    //    dd($request->all());
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        DB::beginTransaction();
        try {
            $response['data']=User::where('id',$id)
       ->update(
        [
            'name' => $request->name,
            'email' => $request->email,
            'userType' => $request->userType,
        
        ]
    );
    DB::commit();
    $response['status'] = true;
} catch (\Exception $e) {
    $response['data']=$e->getMessage();
    $response['status'] = false;
    DB::rollback();
}
return response()->json($response);
}
    
   public function destroy($id)
    {
        $response=array();
        $response['status']=false;
        $response['data']  = Admin::find($id);
        if($response['data'])
        {
                $response['data']=$response['data']->delete();
                $response['status']=true;
           
        }
        else
        {
            $response['data']="Data Not deleted";  
        }
        return response()->json($response);
    }


    public function logout()
    {  
        Auth::logout();
        Session::flush();
        return Redirect::to('/admin');
    }

    public function profile()
    {
       
        $data=Auth::guard('admin')->user();
        return $data;
    }

    public function changePass(Request $request)
    {
        

        $request->validate([
            'newPassword' => ['required'],
            'confirmPassword' => ['same:newPassword'],
        ]);
        if(!Hash::check($request->oldPassword,Auth::guard('admin')->user()->password))
        {
            return response()->json(
                [
                    'status'=> false,
                    'message'=> 'Current Password dose not matched'
                ], 200);
        }
        else
        {                     
            $update=Admin::find(Auth::guard('admin')->user()->id)->update(['password'=> Hash::make($request->newPassword)]);  
            if($update)   
            {
                return response()->json(
                    [
                        'status'=> true,
                        'message'=> 'Successfuly Changed'
                    ], 200);
            } 
            else
            {
                return response()->json(
                    [
                        'status'=> false,
                        'message'=> 'Failed, Try again'
                    ], 200);
            }

        }
    }

    public function updateUser(Request $request)
    {
        $update=Admin::where('id',$request->id)->update(
            [
                'name' => $request->name
            ]);
        return $update;
    }

    public function updatepassword(Request $request)
   {
        $update=Admin::where('id',$request->id)->update(
           [
               'password'=>bcrypt($request->password),
           ]
        );
        // $data['password'] =$request->password;
        // $data['email'] =$request->email;
        // Mail::send('mailview', $data, function($message) use ($request) {
        //     $message->to( $request->email , $request->name )
        //     ->subject('Password for SimplistiQ Solution Login');
        // });
       return $update;
   }

   public function avatar(Request $request)
   {
       $user_id = Auth::guard('admin')->user()->id;
       //return $request->all();
       $request->file('myFile')->store('public/uploads/avatar');
       $pic= '/storage/uploads/avatar/'.$request->myFile->hashName();   
       Image::make('storage/uploads/avatar/'.$request->myFile->hashName())->fit(600, 400, function($constraint) {
        $constraint->aspectRatio();})->save('storage/uploads/avatar/'.$request->myFile->hashName());              
       $update=Admin::where('id', $user_id)->update([
           'image' => URL::to('/'). $pic
       ]);
       if($update)
       {
        return response()->json([
            'data' => $pic,
            'status' => true
        ],200);
        }
        return response()->json([
            'data' => 'Failed',
            'status' => false
        ],200);
   }
}
