<?php

namespace App\Http\Controllers;

use App\Models\job;
use App\Models\JobType;
use App\Models\Category;
use App\Models\SavedJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Mail\ResetPasswordEmail;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


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
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',       // must contain at least one lowercase letter
                'regex:/[A-Z]/',       // must contain at least one uppercase letter
                'regex:/[0-9]/',       // must contain at least one digit
                'regex:/[@$!%*?&]/',   // must contain a special character
            ],
            'confirm_password' => 'required|same:password'
        ], [
            'name.required' => 'The old password field is required.',
            'new_password.required' => 'The new password field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already exist.',
            'password.min' => 'The new password must be at least 8 characters long.',
            'password.regex' => 'The new password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.same' => 'The new password and confirmation password must match.',
            'confirm_password.required' => 'The confirm password field is required.',
            'confirm_password.same' => 'The confirm password must match the new password.',

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
            'resume' => 'nullable|mimes:pdf|max:2048', // PDF file, max 2MB
        ]);

        if ($validator->passes()) {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;

            if ($request->hasFile('resume')) {
                // Delete old Resume file if it exists
                if ($user->resume) {
                    File::delete(public_path('Resumes/' . $user->resume));
                }

                $resumeFile = $request->file('resume');
                $ext = $resumeFile->getClientOriginalExtension();
                $resumeName = time() . '-' . $resumeFile->getClientOriginalName(); // Use timestamp + original file name
                $resumeFile->move(public_path('Resumes'), $resumeName);
                $user->resume = $resumeName;
            }

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
            File::delete(public_path('/Profile_pic/' . Auth::user()->image));

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

    public function postJob()
    {

        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobTypes = JobType::where('status', 1)->get();
        return view('account.job.postJob', ['categories' => $categories], ['jobTypes' => $jobTypes]);
    }


    public function processPostJob(Request $request)
    {

        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobNature' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required|min:3|max:75'
        ]);

        if ($validator->passes()) {
            $job = new job();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobNature;
            $job->user_id = $id;
            $job->Vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibilites = $request->responsibility;
            $job->qualification = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->website;
            $job->save();

            session()->flash('success', 'Job added successfully.');

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

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }


    public function myjobs()
    {
        $job = Job::where('user_id', Auth::user()->id)
            ->with('jobType', 'applications')
            ->orderBy('id', 'desc')
            ->paginate(10);


        return view('account.job.myjobs', ['jobs' => $job]);
    }

    public function editJob(Request $request, $id)
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobTypes = JobType::where('status', 1)->get();
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();

        if ($job == null) {
            abort(404);
        }
        return view('account.job.editJob', ['categories' => $categories, 'jobTypes' => $jobTypes, 'job' => $job]);
    }

    public function processEditJob(Request $request, $id)
    {


        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobNature' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required|min:3|max:75'
        ]);

        if ($validator->passes()) {
            $job =  job::find($id);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobNature;
            $job->user_id = Auth::user()->id;
            $job->Vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibilites = $request->responsibility;
            $job->qualification = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->website;

            $job->save();

            session()->flash('success', 'Job Updated successfully.');

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

    public function deleteJob(Request $request)
    {

        $job = job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();

        if ($job == null) {
            session()->flash('error', 'Either job deleted or not found.');
            return response()->json([
                'status' => false,
            ]);
        }

        job::where('id', $request->jobId)->delete();
        session()->flash('success', 'Job deleted Successfully');
        return response()->json([
            'status' => true,
        ]);
    }

    public function myJobApplications()
    {

        $jobApplications = JobApplication::where('user_id', Auth::user()->id)
            ->with('job', 'job.jobType', 'job.applications')->orderBy('appiled_date', 'desc')
            ->paginate(10);

        return view('account.job.my-job-applications', ['jobApplications' => $jobApplications]);
    }

    public function deleteAppliedJob(Request $request)
    {

        $jobApplication = JobApplication::where([
            'id' => $request->id,
            'user_id' => Auth::user()->id
        ])->first();

        if ($jobApplication == null) {
            session()->flash('error', 'Job application not found.');
            return response()->json([
                'status' => false,
            ]);
        }

        JobApplication::find($request->id)->delete();
        session()->flash('success', 'Job Application deleted Successfully');
        return response()->json([
            'status' => true,
        ]);
    }

    public function savedJob()
    {

        // $savedjob=SavedJob::where('user_id',Auth::user()->id)
        //                  ->with('job','job.jobType','job.applications')->orderBy('appiled_date','desc')
        //

        $savedjob = SavedJob::where([
            'user_id' => Auth::user()->id
        ])->with('job', 'job.jobType', 'job.applications')->orderBy('created_at', 'desc')->paginate(10);


        return view('account.job.saved-job', ['savedjob' => $savedjob]);
    }

    public function deleteSavedJob(Request $request)
    {

        $savejob = SavedJob::where([
            'id' => $request->id,
            'user_id' => Auth::user()->id
        ])->first();

        if ($savejob == null) {
            session()->flash('error', 'Saved job not found.');
            return response()->json([
                'status' => false,
            ]);
        }

        SavedJob::find($request->id)->delete();
        session()->flash('success', 'Saved job remove Successfully');
        return response()->json([
            'status' => true,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',       // must contain at least one lowercase letter
                'regex:/[A-Z]/',       // must contain at least one uppercase letter
                'regex:/[0-9]/',       // must contain at least one digit
                'regex:/[@$!%*?&]/',   // must contain a special character
            ],
            'confirm_password' => 'required|same:new_password'
        ], [
            'old_password.required' => 'The old password field is required.',
            'new_password.required' => 'The new password field is required.',
            'new_password.min' => 'The new password must be at least 8 characters long.',
            'new_password.regex' => 'The new password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'new_password.same' => 'The new password and confirmation password must match.',
            'confirm_password.required' => 'The confirm password field is required.',
            'confirm_password.same' => 'The confirm password must match the new password.',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        //Check old password is correct or not
        if (Hash::check($request->old_password, Auth::user()->password) == false) {
            session()->flash('error', 'Your old password is incorrect');
            return response()->json([
                'status' => true,
            ]);
        }

        //update new password
        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        session()->flash('success', 'Password updated successfully');
        return response()->json([
            'status' => true,
        ]);
    }

    public function forgotPassword()
    {
        return view('account.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email not exists',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        $user = User::where('email', $request->email)->first();

        // Send Email Here
        $mailData = [
            'token' => $token,
            'user' => $user,
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

        return redirect()->route('account.forgotPassword')->with('success', 'Reset password email has been sent to your mail id.');
    }

    public function resetPassword($tokenstring)
    {
        $token = DB::table('password_reset_tokens')->where('token', $tokenstring)->first();

        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid token');
        }
        return view('account.resetPassword', ['tokenstring' => $tokenstring]);
    }

    public function processResetPassword(Request $request)
    {

        $token = DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid token');
        }

        $validator = Validator::make($request->all(), [
            'NewPassword' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',       // must contain at least one lowercase letter
                'regex:/[A-Z]/',       // must contain at least one uppercase letter
                'regex:/[0-9]/',       // must contain at least one digit
                'regex:/[@$!%*?&]/',   // must contain a special character
            ],
            'ConfirmPassword' => 'required|same:NewPassword'
        ], [
            'NewPassword.required' => 'The new password field is required.',
            'NewPassword.min' => 'The new password must be at least 8 characters long.',
            'NewPassword.regex' => 'The new password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'NewPassword.same' => 'The new password and confirmation password must match.',
            'ConfirmPassword.required' => 'The confirm password field is required.',
            'ConfirmPassword.same' => 'The confirm password must match the new password.',

        ]);

        if ($validator->fails()) {
            return redirect()->route('account.resetPassword', $request->token)->withErrors($validator);
        }

        User::where('email',$token->email)->update([
            'password'=>Hash::make($request->NewPassword)
        ]);

        return redirect()->route('account.login')->with('success', 'Your Have successfully change your password');
    }
}
