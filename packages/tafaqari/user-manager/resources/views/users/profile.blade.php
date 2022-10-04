@extends('layouts.backend')

@section('content')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->

        <!-- Start content -->
        <div class="content">
            <div class="container-fluid col-lg-9">

                <div class="row">
                    <div class="col-md-12">
                        <div class="p-0 text-center">
                            <div class="member-card">
                                <div class="thumb-xl member-thumb m-b-10 center-page">
                                    @if(Auth::user()->image)
                                        <img src="{{ Auth::user()->image->getAsBase64() }}" alt="user" class="rounded-circle img-thumbnail">
                                    @else
                                        <img src="{{ asset('avatars/default.jpg')}}" alt="user" class="rounded-circle">
                                    @endif
                                    <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                                </div>

                                <div class="">
                                    <h5 class="m-b-5 mt-3">{{ $user->name }}</h5>
                                    <p class="text-muted">Joined {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</p>
                                </div>

                            </div>

                        </div> <!-- end card-box -->

                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div class="m-t-30">
                    <ul class="nav nav-tabs tabs-bordered">
                        <li class="nav-item">
                            <a href="#home-b1" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-b1" data-toggle="tab" aria-expanded="true" class="nav-link">
                                Settings
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="home-b1">
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- Personal-Information -->
                                    <div class="panel panel-default panel-fill">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Personal Information</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="m-b-20">
                                                <strong>Full Name</strong>
                                                <br>
                                                <p class="text-muted">{{ $user->first_name }}{{ $user->last_name }} </p>
                                            </div>
                                            <div class="m-b-20">
                                                <strong>Mobile</strong>
                                                <br>
                                                <p class="text-muted">{{ $user->phone }}</p>
                                            </div>
                                            <div class="m-b-20">
                                                <strong>Email</strong>
                                                <br>
                                                <p class="text-muted">{{ $user->email }}</p>
                                            </div>
                                            <div class="about-info-p m-b-0">
                                                <strong>Location</strong>
                                                <br>
                                                <p class="text-muted">{{ $user->location }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Personal-Information -->

                                    <!-- Social -->
                                    <div class="panel panel-default panel-fill">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Social</h3>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="social-links list-inline mb-0">
                                                <li class="list-inline-item">
                                                    <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="{{ $user->facebook }}" data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="{{ $user->twitter }}" data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="{{ $user->skype }}" data-original-title="Skype"><i class="fa fa-skype"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Social -->
                                </div>


                                <div class="col-md-8">
                                    <!-- Personal-Information -->
                                    <div class="panel panel-default panel-fill">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Biography</h3>
                                        </div>
                                        <div class="panel-body">
                                            <h5 class="font-14 mb-3 text-uppercase">About</h5>
                                            <p>{{ $user->bio? $user->bio : 'No bio set yet' }}</p>
                                            <h5 class="font-14 mb-3 m-t-10 text-uppercase">Roles</h5>
                                            @if($user->roles)
                                                @forelse($user->roles as $role)
                                                    {{ $role->name }} {{ $role->name }} since {{ \Illuminate\Support\Carbon::parse($role->created_at)->diffForHumans() }}
                                                @empty
                                                    No special roles assigned
                                                @endforelse
                                            @endif
                                            <h5 class="font-14 mb-3 m-t-10 text-uppercase">Permissions</h5>

                                            @if($user->roles)
                                                <ul>
                                                    @forelse($user->roles as $role)
                                                        @foreach($role->permissions as $permission)
                                                            <li>
                                                                {{ $permission->name }} <small class="label label-sm label-info">{{ \Illuminate\Support\Carbon::parse($role->created_at)->diffForHumans() }}</small>
                                                            </li>

                                                        @endforeach
                                                    @empty
                                                        No special permissions assigned
                                                    @endforelse
                                                </ul>

                                            @endif

                                        </div>
                                    </div>
                                    <!-- Personal-Information -->

                                </div>

                            </div>
                        </div>
                        <div class="tab-pane" id="profile-b1">
                            <!-- Personal-Information -->
                            <div class="panel panel-default panel-fill">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Edit Profile</h3>
                                </div>
                                <div class="panel-body">
                                    {{ Form::model($user, array('route' => array('profile.save', Auth::id()), 'method' => 'PATCH', 'role' => 'form')) }}

                                        <div class="form-group">
                                            <label for="FullName">Full Name</label>
                                            <input type="text" value="{{ $user->name }}" id="FullName" name="name" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="Email">Email</label>
                                            <input type="email" value="{{ $user->email }}" readonly name="email" id="Email" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="Username">Phone</label>
                                            <input type="text" value="{{ $user->phone }}" id="Phone" name="phone" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="Password">Location</label>
                                            <input type="text" placeholder="Location" value="{{ $user->location }}" id="Location" name="location" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="AboutMe">About Me</label>
                                            <textarea style="height: 125px" id="AboutMe" name="bio" class="form-control">{{ $user->bio }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="Facebook">Facebook</label>
                                            <input type="text" placeholder="Facebook" value="{{ $user->facebook }}" id="Facebook" name="facebook" class="form-control">
                                        </div>

                                    <div class="form-group">
                                        <label for="Twitter">Twitter</label>
                                        <input type="text" placeholder="Twitter" value="{{ $user->twitter }}" id="Twitter" name="twitter" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="Skype">Skype</label>
                                        <input type="text" placeholder="Skype" value="{{ $user->skype }}" id="Skype" name="skype" class="form-control">
                                    </div>


                                        <button class="btn btn-primary waves-effect waves-light w-md" type="submit">Save</button>

                                    {{ Form::close() }}

                                </div>
                            </div>
                            <!-- Personal-Information -->
                        </div>
                    </div>
                </div>

            </div> <!-- container -->


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->
        </div>
@endsection
