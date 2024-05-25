@extends('Layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Job Applications</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>
            <div class="col-lg-9">
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
                <div class="card border-0 shadow mb-4">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Job Applications</h3>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Job title</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Employer</th>
                                        <th scope="col">Applied</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($applications->isNotEmpty())
                                    @foreach ($applications as $application)
                                    <tr class="active">
                                        <td>
                                            <div class="job-name fw-500">{{ $application->id }}</div>
                                            <div class="info1">
                                            </div>
                                        </td>
                                        <td>
                                            <p>{{ $application->job->title }}</p>
                                        </td>
                                        <td>{{ $application->user->name }}</td>
                                        <td>{{ $application->employer->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($application->appiled_date)->format('d M, Y')  }}</td>
                                        <td>
                                            <div class="action-dots">
                                                <button href="#" class="btn" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"
                                                        onclick="deleteJobApplication({{ $application->id }})"
                                                            ><i class="fa fa-trash"
                                                                aria-hidden="true"></i>
                                                            Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5">Job application not found</td>
                                    </tr>
                                    @endif


                                </tbody>

                            </table>
                        </div>
                        <div>
                            {{ $applications->links() }}
                        </div>
                    </div>

                </div>

                <div class="card border-0 shadow mb-4">

                </div>

            </div>
        </div>
    </div>
</section>

@endsection
@section('customjs')
<script type="text/javascript">
    function deleteJobApplication(id){
        if(confirm('Are you sure you want to delete?')){
            $.ajax({
                url: '{{ route('admin.jobApplications.destroy') }}',
                type: 'post', // Use uppercase DELETE method
                data: {id: id},
                dataType: 'json',
                success: function(response){
                    // Redirect to admin users page after successful deletion
                    window.location.href = '{{ route('admin.jobApplications') }}';
                }
            });
        }
    }
    </script>

@endsection
