@extends('layouts.backend')
<!-- START CONTAINER FLUID -->
@section('content')
    <div class="container  container-fixed-lg full-height">
        <!-- START PAGE CONTENT WRAPPER -->
        <div class="full-height m-t-50 align-items-center">
            <div class="row full-width">
                <div class="col-md-6">
                    <!-- START Lock Screen User Info -->
                    <div class="d-flex justify-content-start align-items-center">
                        <div class="">
                            <div class="thumbnail-wrapper circular d48 m-r-10 ">
                                <img width="53" height="53" data-src-retina="{{ asset('avatars/default.jpg') }}" data-src="{{ asset('avatars/default.jpg') }}" alt="" src="{{ asset('avatars/default.jpg') }}">
                            </div>
                        </div>
                        <div class="">
                            <h5 class="logged hint-text no-margin">
                                Profile Page
                            </h5>
                            <h2 class="name no-margin">{{ $profile->user->name }}</h2>
                        </div>
                    </div>
                    <!-- END Lock Screen User Info -->
                </div>
                <div class="col-md-6">
                    <div class="card card-transparent">
                        <div class="card-block no-margin">
                            <address class="m-b-20">
                                <strong>{{ $profile->organization->name }}</strong>
                                <br>Department: {{ $profile->department->name }}
                                <br>Region: {{ $profile->region->name }}
                                <br>
                                <abbr title="Email">E:</abbr>{{ $profile->user->email }}
                            </address>
                        </div>
                    </div>

                </div>
            </div>
            <div class="clearfix"></div>

        </div>
        <div class="row">
            @if($profile->employee)
                <div class="col-md-12">
                    <div class="card card-transparent ">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-tabs-fillup hidden-sm-down" data-init-reponsive-tabs="dropdownfx">
                            <li class="nav-item">
                                <a href="#" class="active" data-toggle="tab" data-target="#slide1" aria-expanded="true"><span>Employee Summary</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="#" data-toggle="tab" data-target="#slide2" aria-expanded="true"><span>Contract Summary</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="#" data-toggle="tab" data-target="#slide3" class="" aria-expanded="false"><span>Personal Information</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="#" data-toggle="tab" data-target="#slide4" class="" aria-expanded="false"><span>Govt Numbers</span></a>
                            </li>
                        </ul><div class="nav-tab-dropdown cs-wrapper full-width hidden-md-up"><div class="cs-select cs-skin-slide full-width" tabindex="0"><span class="cs-placeholder">Hello World</span><div class="cs-options"><ul><li data-option="" data-value="#slide1"><span>Home</span></li><li data-option="" data-value="#slide2"><span>Profile</span></li><li data-option="" data-value="#slide3"><span>Messages</span></li></ul></div><select class="cs-select cs-skin-slide full-width" data-init-plugin="cs-select"><option value="#slide1" selected="">Home</option><option value="#slide2">Profile</option><option value="#slide3">Messages</option></select><div class="cs-backdrop"></div></div></div>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane slide-left active" id="slide1" aria-expanded="true">
                                <div class="row column-seperation">
                                    <div class="col-lg-4 m-3">
                                        <div class="container-xs-height">
                                            <div class="row-xs-height">
                                                <div class="social-user-profile col-xs-height text-center col-top">
                                                    <div class="thumbnail-wrapper d48 circular bordered b-white">
                                                        <img alt="Avatar" width="55" height="55" data-src-retina="{{ asset('avatars/default.jpg') }}" data-src="{{ asset('avatars/default.jpg') }}" src="{{ asset('avatars/default.jpg') }}">
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-xs-height p-l-20">
                                                    <h3 class="no-margin p-b-5">{{ $profile->employee->name }}</h3>
                                                    <p class="no-margin fs-12 m-b-5">Department: {{  $profile->employee->department->name ?? 'Undefined' }}
                                                    </p>
                                                    <p class="m-t-5"><code>Number: {{ $profile->employee_number }}</code></p>
                                                    <p class="m-t-5"><i class="fa fa-birthday-cake"></i>  {{ $profile->employee->date_of_birth ?? 'Undefined' }} </p>
                                                    <p class="m-t-5"><i class="fa fa-genderless"></i>  {{ $profile->employee->gender ?? 'Undefined' }} </p>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <h3 class="semi-bold">{{ $profile->employee->position->position ?? 'undefined' }}</h3>
                                        <p>{{ $profile->employee->position? str_limit($profile->employee->position->description,500) : 'No Job Description' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane slide-left" id="slide2" aria-expanded="false">
                                <div class="row">
                                    <div class="col-lg-4 m-3">
                                        <div class="container-xs-height">
                                            <div class="row-xs-height">
                                                <div class="social-user-profile col-xs-height text-center col-top">
                                                    <div class="thumbnail-wrapper d48 circular bordered b-white">
                                                        <img alt="Avatar" width="55" height="55" data-src-retina="{{ asset('avatars/default.jpg') }}" data-src="{{ asset('avatars/default.jpg') }}" src="{{ asset('avatars/default.jpg') }}">
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-xs-height p-l-20">
                                                    <h3 class="no-margin p-b-5">{{ $profile->employee->name }}</h3>
                                                    <p class="no-margin fs-12 m-b-5">Department: {{  $profile->employee->department->name ?? 'Undefined' }}
                                                    </p>
                                                    <p class="m-t-5"><code>Number: {{ $profile->employee_number }}</code></p>
                                                    <p class="m-t-5"><i class="fa fa-birthday-cake"></i>  {{ $profile->employee->date_of_birth ?? 'undefined' }} </p>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <ul class="list-group m-t-20">
                                            <li class="list-group-item">Start: {{ $profile->employee->contract? $profile->employee->contract->start: 'Undefined' }}</li>
                                            <li class="list-group-item">End: {{ $profile->employee->contract? $profile->employee->contract->end: 'Undefined' }}</li>
                                            <li class="list-group-item">Salary: {{ Config::get('company.currency') }}{{ $profile->employee->contract? number_format($profile->employee->contract->salary): 'Undefined' }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane slide-left" id="slide3" aria-expanded="false">
                                <div class="row">
                                    <div class="col-lg-4 m-3">
                                        <div class="container-xs-height">
                                            <div class="row-xs-height">
                                                <div class="social-user-profile col-xs-height text-center col-top">
                                                    <div class="thumbnail-wrapper d48 circular bordered b-white">
                                                        <img alt="Avatar" width="55" height="55" data-src-retina="{{ asset('avatars/default.jpg') }}" data-src="{{ asset('avatars/default.jpg') }}" src="{{ asset('avatars/default.jpg') }}">
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-xs-height p-l-20">
                                                    <h3 class="no-margin p-b-5">{{ $profile->employee->name }}</h3>
                                                    <p class="no-margin fs-12 m-b-5">Department: {{  $profile->employee->department->name ?? 'Undefined' }}
                                                    </p>
                                                    <p class="m-t-5"><code>Number: {{ $profile->employee->employee_number }}</code></p>
                                                    <p class="m-t-5"><i class="fa fa-birthday-cake"></i>  {{ $profile->employee->date_of_birth ?? 'undefined' }} </p>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <ul class="list-group m-t-20">
                                            <li class="list-group-item">Religion: {{ $profile->employee->religion? $profile->employee->religion->name: 'Undefined' }}</li>
                                            <li class="list-group-item">Marital Status: {{ ucfirst($profile->employee->marital_status) ??  'Undefined' }}</li>
                                            <li class="list-group-item">Blood Group: {{ ucfirst($profile->employee->blood_type) ??  'Undefined' }}</li>
                                            <li class="list-group-item">Next Of Kin: {{ $profile->employee->next_of_kin ?? 'Undefined' }}</li>
                                            <li class="list-group-item">Next Of Kin: {{ $profile->employee->next_of_kin_number ?? 'Undefined' }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane slide-left" id="slide4" aria-expanded="false">
                                <div class="row">
                                    <div class="col-lg-4 m-3">
                                        <div class="container-xs-height">
                                            <div class="row-xs-height">
                                                <div class="social-user-profile col-xs-height text-center col-top">
                                                    <div class="thumbnail-wrapper d48 circular bordered b-white">
                                                        <img alt="Avatar" width="55" height="55" data-src-retina="{{ asset('avatars/default.jpg') }}" data-src="{{ asset('avatars/default.jpg') }}" src="{{ asset('avatars/default.jpg') }}">
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-xs-height p-l-20">
                                                    <h3 class="no-margin p-b-5">{{ $profile->employee->name }}</h3>
                                                    <p class="no-margin fs-12 m-b-5">Department: {{  $profile->employee->department->name ?? 'Undefined' }}
                                                    </p>
                                                    <p class="m-t-5"><code>Number: {{ $profile->employee_number }}</code></p>
                                                    <p class="m-t-5"><i class="fa fa-birthday-cake"></i>  {{ $profile->employee->date_of_birth ?? 'undefined' }} </p>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <ul class="list-group m-t-20">
                                            <li class="list-group-item">PIN: {{ $profile->employee->pin ?? 'Undefined' }}</li>
                                            <li class="list-group-item">NSSF: {{ ucfirst($profile->employee->nssf_number) ??  'Undefined' }}</li>
                                            <li class="list-group-item">NHIF: {{ ucfirst($profile->employee->nhif_number) ??  'Undefined' }}</li>
                                            <li class="list-group-item">HELB: {{ $profile->employee->helb_number ?? 'Undefined' }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
@endsection
