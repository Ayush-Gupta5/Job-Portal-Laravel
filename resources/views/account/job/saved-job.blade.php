@extends('Layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Saved Jobs</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('sidebar')
            </div>
            <div class="col-lg-9">
                @if (Session::has('success'))
                <div class="alert alert-success mb-2 pb-0">
                    <p>{{ Session::get('success') }}</p>
                </div>
                @endif
                @if (Session::has('error'))
                <div class="alert alert-danger mb-2 pb-0">
                    <p>{{ Session::get('error') }}</p>
                </div>
                @endif
                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <h3 class="fs-4 mb-1">Saved Jobs</h3>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Applicants</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($savedjob->isNotEmpty())
                                        @foreach ($savedjob as $savedjobs )
                                        <tr class="active">
                                            <td>
                                                <div class="job-name fw-500">{{ $savedjobs->job->title }}</div>
                                                <div class="info1">{{ $savedjobs->job->jobType->name }} . {{ $savedjobs->job->location }}</div>
                                            </td>
                                            <td>{{ $savedjobs->job->applications->count() }}  Applications</td>
                                            <td>
                                                @if ($savedjobs->job->status==1)
                                                <div class="job-status text-capitalize">Active</div>
                                                @else
                                                <div class="job-status text-capitalize">Block</div>
                                                @endif

                                            </td>
                                            <td>
                                                <div class="action-dots ">
                                                    <a href="#" class="" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="{{ route("jobsDetail",$savedjobs->job_id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="deleteJob({{ $savedjobs->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5">Saved Job not found</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('customjs')
<script type="text/javascript">
    function deleteJob(id){
        if(confirm('Are you sure you want to remove?')){
            $.ajax({
                url: '{{ route('account.job.deleteSavedJob') }}',
                type:'post',
                data: {id: id},
                dataType: 'json',
                success: function(response){
                    window.location.href='{{ route('account.job.savedJob') }}'
                }
            });
        }
    }
    </script>

@endsection

