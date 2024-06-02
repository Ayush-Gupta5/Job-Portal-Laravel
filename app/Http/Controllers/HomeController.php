<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\job;
use Illuminate\Http\Request;

class HomeController extends Controller
{
   public function index(){

    $categories=Category::where('status',1)->orderBy('name','ASC')->get();

    $newCategories=Category::where('status',1)->orderBy('name','ASC')->get();


    $featuredjob=job::where('status',1)->orderBy('created_at','DESC')->with('jobType')->where('isFeatured',1)->take(6)->get();

    $latestJob=job::where('status',1)->orderBy('created_at','DESC')->with('jobType')->take(6)->get();

    return view('home',['categories'=>$categories,'featuredjob'=>$featuredjob,'latestJob'=>$latestJob,'newCategories'=>$newCategories]);
   }
}
