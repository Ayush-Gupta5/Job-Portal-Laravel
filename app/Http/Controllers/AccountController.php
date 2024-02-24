<?php

namespace App\Http\Controllers;

use App\Models\job;
use App\Models\JobType;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AccountController extends Controller
{
    public function register()
    {
        return view('account.register');
    }

    public function processRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required'
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            Session()->flash('success', 'You have registerd successfully');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function login()
    {
        return view('account.login');
    }

    public function authenticate(Request $request)
    {
        $validator = validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')->with('error', 'Either Email/Password is incorrect');
            }
        } else {
            return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        $id = Auth::user()->id;

        $user = User::where('id', $id)->first();


        return view('account.profile', [
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
        ]);

        if ($validator->passes()) {
            $user = user::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->save();

            session()->flash('success', 'Profile updated successfully');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }



    public function updateProfilePic(Request $request)
    {

        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        if ($validator->passes()) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id . '-' . time() . '.' . $ext;

            $image->move(public_path('/Profile_pic'), $imageName);

            //Delete old Profile Pic
            File::delete(public_path('/Profile_pic/'.Auth::user()->image));

            //Database upload
            User::where('id', $id)->update(['image' => $imageName]);

            session()->flash('success', 'Profile picture update successfully');
            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function postJob(){

        $categories=Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes=JobType::where('status',1)->get();
        return view('account.job.postJob',['categories'=>$categories],['jobTypes'=>$jobTypes]);
    }


    public function processPostJob(Request $request){

        $id = Auth::user()->id;
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
            $job = new job();
            $job->title=$request->title;
            $job->category_id=$request->category;
            $job->job_type_id =$request->jobNature;
            $job->user_id=$id;
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
            $job->save();

            session()->flash('success','Job added successfully.');

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

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }


    public function myjobs()
    {
        $job = Job::where('user_id', Auth::user()->id)
    ->with('jobType')
    ->orderBy('id', 'desc')
    ->paginate(10);


        return view('account.job.myjobs',['jobs'=>$job]);
    }

    public function editJob(Request $request,$id){
        $categories=Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes=JobType::where('status',1)->get();
        $job=Job::where([
            'user_id'=>Auth::user()->id,
            'id'=>$id
        ])->first();

        if($job == null){
            abort(404);
        }
        return view('account.job.editJob',['categories'=>$categories,'jobTypes'=>$jobTypes,'job'=>$job]);
    }

    public function processEditJob(Request $request,$id){


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
            $job->user_id=Auth::user()->id;
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

    public function deleteJob(Request $request){

        $job=job::where([
            'user_id'=>Auth::user()->id,
            'id'=>$request->jobId
        ])->first();

        if($job == null){
            session()->flash('error','Either job deleted or not found.');
            return response()->json([
                'status'=>true,
            ]);
        }

        job::where('id',$request->jobId)->delete();
        session()->flash('success','Job deleted Successfully');
        return response()->json([
            'status'=>true,
        ]);
    }
}
