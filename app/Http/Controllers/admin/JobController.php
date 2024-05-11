<?php

namespace App\Http\Controllers\admin;

use App\Models\job;
use App\Models\JobType;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index(){
        $jobs=job::orderBy('created_at','DESC')->with('user','applications')->paginate(10);

        return view('admin.jobs.list',['jobs'=>$jobs]);
    }

    public function edit($id){
        $categories=Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes=JobType::where('status',1)->get();
        $job=Job::findOrfail($id);

        if($job == null){
            abort(404);
        }
        return view('admin.jobs.edit',['categories'=>$categories,'jobTypes'=>$jobTypes,'job'=>$job]);
    }

    public function update(Request $request, $id){

        $validator=Validator::make($request->all(),[
            'title'=>'required|min:5|max:200',
            'category'=>'required',
            'jobNature'=>'required',
            'vacancy'=>'required|integer',
            'location'=>'required|max:50',
            'description'=>'required',
            'experience'=>'required',
            'company_name'=>'required|min:3|max:75'
        ]);

        if($validator->passes()){
            $job =  job::find($id);
            $job->title=$request->title;
            $job->category_id=$request->category;
            $job->job_type_id =$request->jobNature;
            $job->Vacancy=$request->vacancy;
            $job->salary=$request->salary;
            $job->location=$request->location;
            $job->description=$request->description;
            $job->benefits=$request->benefits;
            $job->responsibilites=$request->responsibility;
            $job->qualification=$request->qualifications;
            $job->keywords=$request->keywords;
            $job->experience=$request->experience;
            $job->company_name=$request->company_name;
            $job->company_location=$request->company_location;
            $job->company_website=$request->website;
            $job->status=$request->status;
            $job->isFeatured=(!empty($request->isFeatured)) ? $request->isFeatured : 0 ;


            $job->save();

            session()->flash('success','Job Updated successfully.');

            return response()->json([
                'status'=>true,
                'errors'=>[]
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function destroy(Request $request){
        $id = $request->id;

        $user=job::find($id);

        if($user == null){
            session()->flash('error','Job not found');
            return response()->json([
                'status' => false,
            ]);
        }

        $user->delete();
        session()->flash('success','Job deleted successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}
