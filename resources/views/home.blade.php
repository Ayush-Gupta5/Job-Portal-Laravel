@extends('Layouts.app')

@section('main')
    <section class="section-0 lazy d-flex bg-image-style dark align-items-center " class=""
        data-bg="assets/images/banner5.jpg">
        <div class="container">
            <div class="row">
                <div class="col-12 col-xl-8">
                    <h1>Find your dream job</h1>
                    <p>Thounsands of jobs available.</p>
                    <div class="banner-btn mt-5"><a href="{{ route('jobs') }}" class="btn btn-primary mb-4 mb-sm-0">Explore Now</a></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-1 py-5 ">
        <div class="container">
            <div class="card border-0 shadow p-5">
                <form action="{{ route ("jobs") }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                            <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Keywords">
                        </div>
                        <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                            <input type="text" class="form-control" name="location" id="location"
                                placeholder="Location">
                        </div>
                        <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Select a Category</option>
                                @if ($newCategories->isNotEmpty())
                                    @foreach ($newCategories as $category)
                                        <option  value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class=" col-md-3 mb-xs-3 mb-sm-3 mb-lg-0">
                            <div class="d-grid gap-2">
                                {{-- <a href="jobs.html" class="btn btn-primary btn-block">Search</a> --}}
                                <button type="submit" class="btn btn-primary btn-block">Search</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="section-2 bg-2 py-5">
        <div class="container">
            <h2>Popular Categories</h2>
            <div class="row pt-5">

                @if ($categories->isNotEmpty())
                    @foreach ($categories as $category)
                        <div class="col-lg-4 col-xl-3 col-md-6">
                            <div class="single_catagory">
                                <a href="{{ route('jobs').'?category_id='. $category->id }}">
                                    <h4 class="pb-2">{{ $category->name }}</h4>
                                </a>
                                <p class="mb-0"> <span>0</span> Available position</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <section class="section-3  py-5">
        <div class="container">
            <h2>Featured Jobs</h2>
            <div class="row pt-5">
                <div class="job_listing_area">
                    <div class="job_lists">
                        <div class="row">
                            @if ($featuredjob->isNotEmpty())
                                @foreach ($featuredjob as $fjob)
                                    <div class="col-md-4">
                                        <div class="card border-0 p-3 shadow mb-4">
                                            <div class="card-body">
                                                <h3 class="border-0 fs-5 pb-2 mb-0">{{ $fjob->title }}</h3>
                                                <p>{{ Str::words(html_entity_decode(strip_tags($fjob->description)), 5) }}</p>
                                                <div class="bg-light p-3 border">
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                        <span class="ps-1">{{ $fjob->location }}</span>
                                                    </p>
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                        <span class="ps-1">{{ $fjob->jobType->name }}</span>
                                                    </p>
                                                    @if (!is_null($fjob->salary))
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-inr"></i></span>
                                                            <span class="ps-1">{{ $fjob->salary }} Lacs PA</span>
                                                        </p>
                                                    @else
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                            <span class="ps-1">Not Disclosed</span>
                                                        </p>
                                                    @endif

                                                </div>

                                                <div class="d-grid mt-3">
                                                    <a href="{{ route('jobsDetail', $fjob->id) }}"
                                                        class="btn btn-primary btn-lg">Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-3 bg-2 py-5">
        <div class="container">
            <h2>Latest Jobs</h2>
            <div class="row pt-5">
                <div class="job_listing_area">
                    <div class="job_lists">
                        <div class="row">
                            @if ($latestJob->isNotEmpty())
                                @foreach ($latestJob as $ljob)
                                    <div class="col-md-4">
                                        <div class="card border-0 p-3 shadow mb-4">
                                            <div class="card-body">
                                                <h3 class="border-0 fs-5 pb-2 mb-0">{{ $ljob->title }}</h3>
                                                <p>{{ Str::words(html_entity_decode(strip_tags($ljob->description)), 5) }}</p>
                                                <div class="bg-light p-3 border">
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                        <span class="ps-1">{{ $ljob->location }}</span>
                                                    </p>
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                        <span class="ps-1">{{ $ljob->jobType->name }}</span>
                                                    </p>
                                                    @if (!is_null($ljob->salary))
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-inr"></i></span>
                                                            <span class="ps-1">{{ $ljob->salary }} Lacs PA</span>
                                                        </p>
                                                    @else
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-inr"></i></span>
                                                            <span class="ps-1">Not Disclosed</span>
                                                        </p>
                                                    @endif

                                                </div>

                                                <div class="d-grid mt-3">
                                                    <a href="{{ route('jobsDetail', $ljob->id) }}"
                                                        class="btn btn-primary btn-lg">Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
