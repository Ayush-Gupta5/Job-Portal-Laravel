@extends('Layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Post a Job</li>
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
                    <div class="card border-0 shadow mb-4 ">
                        <div class="card-body card-form p-4">
                            <form method="" action="" name="editJobForm" id="editJobForm">
                                <h3 class="fs-4 mb-1">Edit Job Details</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Title<span class="req">*</span></label>
                                        <input type="text" placeholder="Job Title" id="title" name="title"
                                            class="form-control" value="{{ $job->title }}">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6  mb-4">
                                        <label for="" class="mb-2">Category<span class="req">*</span></label>
                                        <select name="category" id="category" class="form-select">
                                            <option value="">Select a Category</option>
                                            @if ($categories->isNotEmpty())
                                                @foreach ($categories as $category)
                                                    <option {{ ($job->category_id == $category->id ) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Job Nature<span class="req">*</span></label>
                                        <select class="form-select" id="jobNature" name="jobNature">
                                            <option value="">Select a Job Nature</option>
                                            @if ($jobTypes->isNotEmpty())
                                                @foreach ($jobTypes as $jobType)
                                                    <option {{ ($job->job_type_id == $jobType->id)? 'selected' : '' }} value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="col-md-6  mb-4">
                                        <label for="" class="mb-2">Vacancy<span class="req">*</span></label>
                                        <input type="number" min="1" placeholder="Vacancy" id="vacancy"
                                            name="vacancy" class="form-control" value={{ $job->Vacancy }}>
                                            <p></p>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Salary</label>
                                        <input type="text" placeholder="Salary" id="salary" name="salary"
                                            class="form-control" value={{ $job->salary }}>
                                        <p></p>
                                    </div>

                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Location<span class="req">*</span></label>
                                        <input type="text" placeholder="location" id="location" name="location"
                                            class="form-control" value="{{ $job->location }}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label for="" class="mb-2">Experience<span class="req">*</span></label>
                                        <select class="form-select" name="experience" id="experience">

                                            <option value="F"{{ ($job->experience == "F")?  'selected'
                                            : '' }}>Fresher</option>
                                            <option value="1"{{ ($job->experience == 1)?  'selected'
                                            : '' }}>1 Years</option>
                                            <option value="2"{{ ($job->experience == 2)?  'selected'
                                            : '' }}>2 Years</option>
                                            <option value="3"{{ ($job->experience == 3)?  'selected'
                                            : '' }}>3 Years</option>
                                            <option value="4"{{ ($job->experience == 4)?  'selected'
                                            : '' }}>4 Years</option>
                                            <option value="5"{{ ($job->experience == 5)?  'selected'
                                            : '' }}>5 Years</option>
                                            <option value="6"{{ ($job->experience == 6)?  'selected'
                                            : '' }}>6 Years</option>
                                            <option value="7"{{ ($job->experience == 7)?  'selected'
                                            : '' }}>7 Years</option>
                                            <option value="8"{{ ($job->experience == 8)?  'selected'
                                            : '' }}>8 Years</option>
                                            <option value="9"{{ ($job->experience == 9)?  'selected'
                                            : '' }}>9 Years</option>
                                            <option value="10"{{ ($job->experience == 10)?  'selected'
                                            : '' }}>10+ Years</option>
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-2">Description<span class="req">*</span></label>
                                        <textarea class="textarea" name="description" id="description" cols="5" rows="5"
                                            placeholder="Description">{{ $job->description }}</textarea>
                                        <p></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-2">Benefits</label>
                                        <textarea class="textarea" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits">{{ $job->benefits }}</textarea>
                                        <p></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-2">Responsibility</label>
                                        <textarea class="textarea" name="responsibility" id="responsibility" cols="5" rows="5"
                                            placeholder="Responsibility">{{ $job->responsibilites }}</textarea>
                                        <p></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-2">Qualifications</label>
                                        <textarea class="textarea" name="qualifications" id="qualifications" cols="5" rows="5"
                                            placeholder="Qualifications">{{ $job->qualification }}</textarea>
                                        <p></p>
                                    </div>



                                    <div class="mb-4">
                                        <label for="" class="mb-2">Keywords</label>
                                        <input type="text" placeholder="keywords" id="keywords" name="keywords"
                                            class="form-control" value="{{ $job->keywords }}">
                                        <p></p>
                                    </div>

                                    <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                                    <div class="row">
                                        <div class="mb-4 col-md-6">
                                            <label for="" class="mb-2">Name<span
                                                    class="req">*</span></label>
                                            <input type="text" placeholder="Company Name" id="company_name"
                                                name="company_name" class="form-control" value="{{ $job->company_name }}">
                                            <p></p>
                                        </div>

                                        <div class="mb-4 col-md-6">
                                            <label for="" class="mb-2">Location</label>
                                            <input type="text" placeholder="Location" id="company_location" name="company_location"
                                                class="form-control" value="{{ $job->company_location }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="" class="mb-2">Website</label>
                                        <input type="text" placeholder="Website" id="website" name="website"
                                            class="form-control" value={{ $job->company_website }}>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="card-footer  p-4">
                                    <button type="submit" class="btn btn-primary">Update Job</button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
    </section>
@endsection

@section('customjs')
    <script type="text/javascript">
        $("#editJobForm").submit(function(e) {
            e.preventDefault();
            $("#button[type='submit']").prop('disable',true)
            $.ajax({

                url: '{{ route("account.processEditJob",$job->id) }}',
                type: 'post',
                data: $('#editJobForm').serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("#button[type='submit']").prop('disable',false)
                    if (response.status == false) {
                        var errors = response.errors;
                        if (errors.title) {
                            $("#title").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.title);
                        }else{
                            $("#title").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.category) {
                            $("#category").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.category);
                        }else{
                            $("#category").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.jobNature) {
                            $("#jobNature").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.jobNature);
                        }else{
                            $("#jobNature").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.vacancy) {
                            $("#vacancy").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.vacancy);
                        }else{
                            $("#vacancy").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.location) {
                            $("#location").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.location);
                        }else{
                            $("#location").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.experience) {
                            $("#experience").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.experience);
                        }else{
                            $("#experience").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.company_name) {
                            $("#company_name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.company_name);
                        }else{
                            $("#company_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if (errors.description) {
                            $("#description").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.description);
                        }else{
                            $("#description").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        }

                    }else{
                        $("#title, #category, #jobNature, #vacancy, #location, #experience, #company_name, #description")
                        .removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');

                    // Redirect to the desired URL on successful submission
                    window.location.href = '{{ route("account.job.myjobs") }}';
                    }
                }
            });
        });
    </script>
@endsection
