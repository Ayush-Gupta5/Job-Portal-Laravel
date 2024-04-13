<?php

namespace App\Http\Controllers;

use App\Models\job;
use App\Models\User;
use App\Models\JobType;
use App\Models\Category;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Mail\JobNotificationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    // this method show jpb page
    public function index(Request $request)
    {
        $categories = Category::where('status', 1)->get();
        $jobTypes = jobType::where('status', 1)->get();
        $jobs = job::where('status', 1);

        //Search using Keyword
        if (!empty($request->keyword)) {
            $jobs = $jobs->where(function ($query) use ($request) {
                $query->orWhere('title', 'Like', '%' . $request->keyword . '%');
                $query->orWhere('keywords', 'Like', '%' . $request->keyword . '%');
            });
        }

        //Searching using Location
        if (!empty($request->location)) {
            $jobs = $jobs->where('location', $request->location);
        }

        //Searching using category
        if (!empty($request->category_id)) {
            $jobs = $jobs->where('category_id', $request->category_id);
        }

        //Searching using JobType
        $jobTypeArray = [];
        if (!empty($request->job_type_id)) {
            $jobTypeArray = explode(',', $request->job_type_id);

            $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
        }

        //Searching using experience
        if (!empty($request->experience)) {
            $jobs = $jobs->where('experience', $request->experience);
        }

        $jobs = $jobs->with('jobType')->orderBy('created_at', 'DESC')->Paginate();


        return view('jobs', ['categories' => $categories, 'jobTypes' => $jobTypes, 'jobs' => $jobs, 'jobTypeArray' => $jobTypeArray]);
    }

    #this method will show job detail page
    public function detail($id)
    {
        $jobDetail = job::where(['id' => $id, 'status' => 1])->with(['jobType', 'jobCategory'])->first();

        if ($jobDetail == null) {
            abort(404);
        }

        $count = 0;
        if (Auth::user()) {
            $count = SavedJob::where([
                'user_id' => Auth::user()->id,
                'job_id' => $id
            ])->count();
        }

        //fetch applicant
        $applications = JobApplication::where('job_id', $id)->with('user')->get();



        return view('jobDetail', ['jobDetails' => $jobDetail,
                                  'count' => $count,
                                  'applications' => $applications]);
    }

    public function applyjob(Request $request)
    {
        $id = $request->id;

        $job = job::where('id', $id)->first();

        //job not found in db
        if ($job == null) {
            session()->flash('error', 'Job does not exist');
            return response()->json([
                'status' => false,
                'message' => 'Job does not exist'
            ]);
        }

        //you can not apply on your own job
        $employer_id = $job->user_id;

        if ($employer_id == Auth::user()->id) {
            session()->flash('error', 'You can not apply on your own job');
            return response()->json([
                'status' => false,
                'message' => 'You can not apply on your own job'
            ]);
        }

        //you can not apply on a job twice
        $jobApplicationCount = JobApplication::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();

        if ($jobApplicationCount > 0) {
            session()->flash('error', 'You already applied on this job');
            return response()->json([
                'status' => false,
                'message' => 'You already applied on this job'
            ]);
        }
        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->appiled_date = now();
        $application->save();

        //Send Notificatiom Email to employer
        $employer = User::where('id', $employer_id)->first();
        $mailData = [
            'employer' => $employer,
            'user' => Auth::user(),
            'job' => $job
        ];
        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));

        $message = "You have successfully applied";
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function saveJob(Request $request)
    {

        $id = $request->id;

        $job = Job::find($id);


        if ($job == null) {
            session()->flash('error', 'Job not found');
            return response()->json([
                'status' => false,
            ]);
        }

        // Check if user already save the job
        $count = SavedJob::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();


        if ($count > 0) {
            session()->flash('error', 'You already saved this job');
            return response()->json([
                'status' => false
            ]);
        }

        $employer_id = $job->user_id;

        if ($employer_id == Auth::user()->id) {
            session()->flash('error', 'You can not saved your own job');
            return response()->json([
                'status' => false,
            ]);
        }

        $savedJob = new SavedJob;
        $savedJob->job_id = $id;
        $savedJob->user_id = Auth::user()->id;
        $savedJob->save();

        session()->flash('success', 'You have successfully saved the job');

        return response()->json([
            'status' => true
        ]);
    }
}
