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
use App\Mail\EmployeeJobAppliedEmail;

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

        $jobs = $jobs->with('jobType')->orderBy('created_at', 'DESC')->Paginate(9);


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

        // Find the job
        $job = Job::find($id);

        // If job not found
        if (!$job) {
            $errorMessage = 'Job does not exist';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        $user = Auth::user();
        $userId = $user->id;

        // User can't apply to their own job
        $employerId = $job->user_id;
        if ($employerId == $userId) {
            $errorMessage = 'You cannot apply to your own job';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        // Check if the user has already applied to this job
        $jobApplicationCount = JobApplication::where([
            'user_id' => $userId,
            'job_id' => $id
        ])->count();

        if ($jobApplicationCount > 0) {
            $errorMessage = 'You have already applied to this job';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        // Save job application
        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = $userId;
        $application->employer_id = $employerId;
        $application->appiled_date = now();
        $application->save();

        // Prepare data for email notifications
        $employer = User::find($employerId);
        $resumePath = public_path('Resumes/' . $user->resume);

        // Send Notification Email to employer
        $mailData = [
            'employer' => $employer,
            'user' => $user,
            'job' => $job
        ];
        Mail::to($employer->email)->send(new JobNotificationEmail($mailData, $resumePath));

        // Send Notification Email to employee
        $employeeMailData = [
            'employer' => $employer,
            'user' => $user,
            'job' => $job
        ];
        Mail::to($user->email)->send(new EmployeeJobAppliedEmail($employeeMailData));

        $successMessage = "You have successfully applied";
        session()->flash('success', $successMessage);
        return response()->json([
            'status' => true,
            'message' => $successMessage
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
