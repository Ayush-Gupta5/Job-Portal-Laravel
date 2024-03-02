<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\job;
use App\Models\JobType;

class JobsController extends Controller
{
    // this method show jpb page
    public function index(Request $request){
        $categories=Category::where('status',1)->get();
        $jobTypes=jobType::where('status',1)->get();
        $jobs=job::where('status',1);

    //Search using Keyword
    if(!empty($request->keyword)){
        $jobs=$jobs->where(function($query)use($request){
            $query->orWhere('title','Like','%'.$request->keyword.'%');
            $query->orWhere('keywords','Like','%'.$request->keyword.'%');
        });
    }

    //Searching using Location
    if(!empty($request->location)){
        $jobs=$jobs->where('location',$request->location);
    }

    //Searching using category
    if(!empty($request->category_id)){
        $jobs=$jobs->where('category_id',$request->category_id);
    }

    //Searching using JobType
    $jobTypeArray=[];
    if(!empty($request->job_type_id)){
       $jobTypeArray= explode(',',$request->job_type_id);

       $jobs=$jobs->whereIn('job_type_id',$jobTypeArray);

    }

    //Searching using experience
    if(!empty($request->experience)){
        $jobs=$jobs->where('experience',$request->experience);
    }

    $jobs=$jobs->with('jobType')->orderBy('created_at','DESC')->Paginate(9);


    return view('jobs',['categories'=>$categories,'jobTypes'=>$jobTypes,'jobs'=>$jobs,'jobTypeArray'=> $jobTypeArray]);
    }

    #this method will show job detail page
    public function detail($id){
        $jobDetail=job::where(['id'=> $id,'status'=> 1])->with(['jobType','jobCategory'])->first();

        if ($jobDetail==null){
            abort(404);
        }
        return view('jobDetail',['jobDetails'=>$jobDetail]);
    }


}
