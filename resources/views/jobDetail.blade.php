@extends('Layouts.app')
@section('main')
    <section class="section-4 bg-2">
        <div class="container pt-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left"
                                        aria-hidden="true"></i> &nbsp;Back to Jobs</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container job_details_area">
            <div class="row pb-5">
                <div class="col-md-8">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card shadow border-0">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">

                                    <div class="jobs_conetent">
                                        <a href="#">
                                            <h4>{{ $jobDetails->title }}</h4>
                                        </a>
                                        <div class="links_locat d-flex align-items-center">
                                            <div class="location">
                                                <p> <i class="fa fa-map-marker"></i> {{ $jobDetails->location }}</p>
                                            </div>
                                            <div class="location">
                                                <p> <i class="fa fa-clock-o"></i> {{ $jobDetails->jobType->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jobs_right">
                                    <div class="apply_now {{ ($count == 1) ? 'saved-job' : '' }}">
                                        <a class="heart_mark " href="javascript:void(0)" onclick="savejob({{ $jobDetails->id }})"> <i class="fa fa-heart-o"
                                                aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            <div class="single_wrap">
                                <h4>Job description</h4>

                                <p>{!! nl2br($jobDetails->description) !!}</p>
                            </div>
                            @if (!empty($jobDetails->responsibilites))
                                <div class="single_wrap">
                                    <h4>Responsibility</h4>
                                    {!! nl2br($jobDetails->responsibilites) !!}
                                </div>
                            @endif
                            @if (!empty($jobDetails->qualification))
                                <div class="single_wrap">
                                    <h4>Qualifications</h4>
                                    {!! nl2br($jobDetails->qualification) !!}
                                </div>
                            @endif
                            @if (!empty($jobDetails->benefits))
                                <div class="single_wrap">
                                    <h4>Benefits</h4>
                                    {!! nl2br($jobDetails->benefits) !!}
                                </div>
                            @endif
                            <div class="border-bottom"></div>
                            <div class="pt-3 text-end">
                                @if (Auth::check())
                                    <a href="#" onclick="savejob({{ $jobDetails->id }})"
                                        class="btn btn-secondary">Save</a>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-primary">Login to Save</a>
                                @endif
                                @if (Auth::check())
                                    <a href="#" onclick="applyjob({{ $jobDetails->id }})"
                                        class="btn btn-primary">Apply</a>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-primary">Login to Apply</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(Auth::user())
                    @if(Auth::user()->id==$jobDetails->user_id)
                    <div class="card shadow border-0 mt-4">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">
                                    <div class="jobs_conetent">
                                            <h4>Applicants</h4>
                                    </div>
                                </div>
                                <div class="jobs_right"></div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            <table class="table table-striped">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile No.</th>
                                    <th>Applied Date</th>
                                </tr>
                                @if($applications->isNotEmpty())
                                @foreach ($applications as $application )
                                <tr>
                                    <td>{{$application->user->name  }}</td>
                                    <td>{{$application->user->email  }}</td>
                                    <td>{{$application->user->mobile  }}</td>
                                    <td>{{ \Carbon\Carbon::parse($application->appiled_date)->format('d M, Y') }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="3">Application not found</td>

                                </tr>
                                @endif
                            </table>



                        </div>
                    </div>
                    @endif
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Job Summery</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Published on:
                                        <span>{{ \Carbon\Carbon::parse($jobDetails->created_at)->format('d M, Y') }}</span>
                                    </li>
                                    <li>Vacancy: <span>{{ $jobDetails->Vacancy }} Position</span></li>
                                    @if (!is_null($jobDetails->salary))
                                        <li>Salary: <span>{{ $jobDetails->salary }} LPA</span></li>
                                    @else
                                        <li>Salary: <span>Not Disclosed</span></li>
                                    @endif
                                    <li>Location: <span>{{ $jobDetails->location }}</span></li>
                                    <li>Job Nature: <span>{{ $jobDetails->jobType->name }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow border-0 my-4">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Company Details</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Name: <span>{{ $jobDetails->company_name }}</span></li>
                                    @if (!empty($jobDetails->company_location))
                                        <li>Locaion: <span>{{ $jobDetails->company_location }}</span></li>
                                    @endif
                                    @if (!empty($jobDetails->company_website))
                                        <li>Webite: <span><a
                                                    href="{{ $jobDetails->company_website }}">{{ $jobDetails->company_website }}</a></span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customjs')
    <script>
        function applyjob(id) {
            if (confirm("Are you sure you want to apply on this job")) {
                $.ajax({
                    url: '{{ route('applyJob') }}',
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = "{{ url()->current() }}";
                    }
                });
            }
        }

        function savejob(id) {
            $.ajax({
                url: '{{ route('saveJob') }}',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    window.location.href = "{{ url()->current() }}";
                }
            });
        }
    </script>
@endsection
