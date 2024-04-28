@extends('Layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                            <li class="breadcrumb-item active">Account Settings</li>
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

                        <form action="" method="post" id="userForm" name="userForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body p-4">
                                <h3 class="fs-4 mb-1">My Profile</h3>
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
                                <div class="mb-4">
                                    <label for="resume" class="mb-2">Upload Resume (PDF only)</label>
                                    <input type="file" id="resume" name="resume" accept="application/pdf" class="form-control">
                                    <small class="text-muted">Only PDF files are accepted. (PDF file, max 2MB)</small>
                                </div>
                                @if ($user->resume)
                                    <div class="mb-4">
                                        <p>Download Resume:</p>
                                        <a href="{{ url('/Resumes/' . $user->resume) }}" download="{{ $user->name }}_resume.pdf">Download Resume</a>
                                    </div>
                                @endif

                            </div>
                            <div class="card-footer p-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>

                    <div class="card border-0 shadow mb-4">
                        <form action="" method="POST" id="ChangePasswordForm" name="ChangePasswordForm">
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-1">Change Password</h3>
                            <div class="mb-4">
                                <label for="" class="mb-2">Old Password*</label>
                                <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">New Password*</label>
                                <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Confirm Password*</label>
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control">
                                <p></p>
                            </div>
                        </div>
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
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

    var formData = new FormData(this); // Construct FormData object from the form

    $.ajax({
        url: '{{ route('account.updateProfile') }}',
        type: 'POST', // Changed to POST since file uploads are not supported in PUT requests
        data: formData, // Use FormData object for the data
        dataType: 'json',
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting contentType
        success: function(response) {
            if (response.status == false) {
                var errors = response.errors;

                // Corrected field names in the following conditions
                if (errors.name) {
                    $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                        .html(errors.name);
                } else {
                    $("#name").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback')
                        .html('');
                }
                if (errors.email) {
                    $("#email").addClass('is-invalid').siblings('p').addClass(
                            'invalid-feedback')
                        .html(errors.email);
                } else {
                    $("#email").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback')
                        .html('');
                }
            } else {
                $("#name").removeClass('is-invalid').siblings('p').removeClass(
                        'invalid-feedback')
                    .html('');
                $("#email").removeClass('is-invalid').siblings('p').removeClass(
                        'invalid-feedback')
                    .html('');
                window.location.href = '{{ route('account.profile') }}';
            }
        }
    });
});


        $("#ChangePasswordForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('account.updatePassword') }}',
                type: 'post',
                data: $("#ChangePasswordForm").serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == false) {
                        var errors = response.errors;

                        // Corrected field names in the following conditions
                        if (errors.old_password) {
                            $("#old_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors.old_password);
                        } else {
                            $("#old_password").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html('');
                        }
                        if (errors.new_password) {
                            $("#new_password").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors.new_password);
                        } else {
                            $("#new_password").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html('');
                        }
                        if (errors.confirm_password) {
                            $("#confirm_password").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors.confirm_password);
                        } else {
                            $("#confirm_password").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html('');
                        }
                    } else {
                        $("#old_password").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html('');
                        $("#new_password").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback')
                        .html('');
                        $("#confirm_password").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html('');
                        window.location.href = '{{ route('account.profile') }}';
                    }
                }
            });
        });

    </script>
@endsection
