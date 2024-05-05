@extends('Layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
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
                    <form action="" method="post" id="userForm" name="userForm">
                        @csrf
                        @method('PUT')
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-1">User / Edit</h3>
                            <div class="mb-4">
                                <label for="name" class="mb-2">Name*</label>
                                <input type="text" placeholder="Enter Name" name="name" id="name" class="form-control"
                                    value="{{ $user->name }}">
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="mb-2">Email*</label>
                                <input type="text" placeholder="Enter Email" name="email" id="email" class="form-control"
                                    value="{{ $user->email }}">
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="designation" class="mb-2">Designation</label>
                                <input type="text" placeholder="Designation" name="designation" id="designation"
                                    class="form-control" value="{{ $user->designation }}">
                            </div>
                            <div class="mb-4">
                                <label for="mobile" class="mb-2">Mobile</label>
                                <input type="text" placeholder="Mobile" name="mobile" id="mobile" class="form-control"
                                    value="{{ $user->mobile }}">
                            </div>
                        </div>
                        <div class="card-footer p-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>

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
    $("#userForm").submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: '{{ route('admin.users.update', $user->id) }}',
            type: 'PUT', // Use PUT method
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status == false) {
                    var errors = response.errors;

                    // Clear previous error messages
                    $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

                    // Display new error messages, if any
                    if (errors.name) {
                        $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.name);
                    }
                    if (errors.email) {
                        $("#email").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.email);
                    }
                } else {
                    // Clear any previous error messages
                    $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

                    // Redirect to profile page after successful update
                    window.location.href = '{{ route('admin.users') }}';
                }
            }
        });
    });
</script>

@endsection
