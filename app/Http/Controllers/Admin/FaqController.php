<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Session;
use App\ClassifiedCategory;
use App\Faq;
use Redirect;
class FaqController extends Controller
{
    
    public function index(Request $request)
    {
        $data=Faq::orderBy('id','DESC');    
        if(isset($request->parent_id) && !empty($request->parent_id))
        {
            $data= $data->where('parent_id',$request->parent_id);
        }
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
        
        // dd($request->all());
        $response=array();
        $response['status']=false;
        $response['data'] ='';

        DB::beginTransaction();
        try {
            $create=Faq::create(
                [
                    'question' => $request->question,
                    'answer' => $request->answer,
                    'show_on_pricing' => $request->show_on_pricing,
                ]
            );
            DB::commit();
            
            $response['data']=Faq::find($create->id); 
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['data']=$e->getMessage();
            DB::rollback();
        }

        return response()->json($response);
    }

   
    public function show($id)
    {
        //
    }

    
    public function edit(Request $request,$id)
    {
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        DB::beginTransaction();
        try {
            $response['data']=Faq::where('id',$id)
            ->update([
                'show_on_pricing' => $request->show_on_pricing=="true"?1:0,
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
        // dd($request->all());

        DB::beginTransaction();

        try {
            Faq::where('id',$id)
            ->update(
                [
                    'question' => $request->question,
                    'answer' => $request->answer,
                    'show_on_pricing' => $request->show_on_pricing,
                    
                ]
            );
           
            DB::commit();
            $response['data']=Faq::find($id); 
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['data']=$e->getMessage();
            DB::rollback();
        }

        return response()->json($response);
    }

   
    public function destroy($id)
    {
        $response=array();
        $response['status']=false;
        $response['data'] = Faq::find($id);
        if($response['data'])
        {
                $response['data']=$response['data']->delete();
                $response['status']=true;
        }
        else
        {
            $response['data']="No Data";  
        }
        return response()->json($response);
    }
}
