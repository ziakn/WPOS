<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Session;
use App\ClassifiedCategory;
use App\Admin;
use Redirect;

class CreateAdminController extends Controller
{
    
    public function index(Request $request)
    {
        $data=Admin::orderBy('id','DESC');
        if(isset($request->show) && !empty($request->show))
        {
            $show=$request->show;
            $data=$data->paginate($show);
        }
        else
        {
            $data=$data->get();
        }
        return $data;
    }

   
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        $auth=Auth::user();
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        DB::beginTransaction();
        try {
        $user=new Admin;
        $user->name=$request->input('name');
        $user->email=$request->input('email');
        $user->password=bcrypt($request->input('password'));
        $user->save();
        // $data['password'] =$request->password;
        // $data['email'] =$request->email;


        // Mail::send('mailview', $data, function($message) use ($request) {
        //     $message->to( $request->email , $request->name )
        //     ->subject('Password for SimplistiQ Login');
        // });

               $response['data']=Admin::find($user->id);
                DB::commit();
                $response['status'] = true;
              }
            catch (\Exception $e) {
                $response['data']=$e->getMessage();
                $response['status'] = false;
                DB::rollback();
            }
            return response()->json($response);
    }

    
    public function show($id)
    {
        //
    }

  
    public function edit(Request $request, $id)
    {
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        DB::beginTransaction();
        try {
            $response['data']=Admin::where('id',$id)
            ->update([
                'password' => bcrypt($request->password),
            ]);         
            DB::commit();
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['data']=$e->getMessage();
            $response['status'] = false;
            DB::rollback();
        }
            
        return response()->json($response);
    }

  
    public function update(Request $request, $id)
    {
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        DB::beginTransaction();
        try {
            $response['data']=Admin::where('id',$id)
            ->update(
                [
                    'name' => $request->name,
                    'email' => $request->email,
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

    public function updatepassword(Request $request)
    {
        // dd($request->all());
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        DB::beginTransaction();
        try {
            $response['data']=Admin::where('id',$request->id)
            ->update([
                'password' => bcrypt($request->password),
            ]);         
            DB::commit();
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['data']=$e->getMessage();
            $response['status'] = false;
            DB::rollback();
        }
            
        return response()->json($response);
    }
}
