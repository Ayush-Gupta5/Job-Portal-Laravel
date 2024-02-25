<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\job;
use App\Models\JobType;

class JobsController extends Controller
{
    // this method show jpb page
    public function index(){
        $categories=Category::where('status',1)->get();
    $jobTypes=jobType::where('status',1)->get();
    $jobs=job::where('status',1)->with('jobType')->orderBy('created_at','DESC')->Paginate(9);
    return view('jobs',['categories'=>$categories,'jobTypes'=>$jobTypes,'jobs'=>$jobs]);
    }
}
