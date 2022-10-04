@extends('layouts.app')

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
                            <div class="member-card m-0 bg-soft-success pt-5 pb-5">
                                <div class="thumb-xl member-thumb mb-2 center-page">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar->getAsBase64() }}" alt="user" class="rounded-circle img-thumbnail">
                                    @else
                                        <img src="{{ asset('assets/images/users/avatar.jpg')}}" alt="user" class="avatar-lg m-0">
                                    @endif
                                    <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                                </div>

                                <div class="">
                                    <h5 class="mt-1">{{ $user->first_name }}{{ $user->last_name }} </h5>
                                    <p class="text-muted">Joined {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</p>
                                </div>

                            </div>

                        </div> <!-- end card-box -->

                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div class="m-t-30">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Your Profile</h4>
                            <p class="card-title-desc">View & Edit Profile Details</p>

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#navtabs-home" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block">Home</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#navtabs-profile" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                        <span class="d-none d-sm-block">Edit Profile</span>
                                    </a>
                                </li>
                                <!--
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#navtabs-messages" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                        <span class="d-none d-sm-block">Messages</span>
                                    </a>
                                </li>
                                -->
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3 text-muted">
                                <div class="tab-pane active" id="navtabs-home" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <!-- Personal-Information -->
                                            <div class="card card-default card-fill">
                                                <div class="card-heading">
                                                    <h3 class="card-title">Personal Information</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="m-b-20">
                                                        <strong>Full Name</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $user->first_name }}{{ $user->last_name }}</p>
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
                                                        <strong>Bran ch</strong>
                                                        <br>
                                                        <p class="text-muted">{{ $user->branch->name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Personal-Information -->
                                        </div>


                                        <div class="col-md-8">
                                            <!-- Personal-Information -->
                                            <div class="card card-default card-fill">
                                                <div class="card-heading">
                                                    <h3 class="card-title">Biography</h3>
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="font-14 mb-3 text-uppercase">About</h5>
                                                    <p>{{ $user->bio? $user->bio : 'No bio set yet' }}</p>
                                                    <h5 class="font-14 mb-3 mt-4 text-uppercase">Roles</h5>
                                                    @if($user->roles)
                                                        <ul class="list-group">
                                                            @forelse($user->roles as $role)
                                                                <li class="font-size-16 list-group-item">{{ ucfirst($role->name) }}  <span class="badge bg-info p-1 small float-end">assigned {{ Carbon::parse($role->created_at)->diffForHumans() }}</span></li>
                                                            @empty
                                                                No special roles assigned
                                                            @endforelse
                                                        </ul>

                                                    @endif
                                                    <h5 class="font-14 mb-3 mt-3 text-uppercase">Permissions</h5>

                                                    @if($user->roles)
                                                        <ul class="list-group">
                                                            @forelse($user->roles as $role)
                                                                @foreach($role->permissions as $permission)
                                                                    <li class="font-size-14 m-0 p-1 list-group-item">
                                                                        {{ ucfirst($permission->name) }}
                                                                        <span class="badge bg-info p-1 small float-end">assigned {{ Carbon::parse($permission->created_at)->diffForHumans() }}</span>
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
                                <div class="tab-pane" id="navtabs-profile" role="tabpanel">
                                    <p class="mb-0">
                                        <!-- Personal-Information -->
                                    <div class="card card-default card-fill">
                                        <div class="card-heading">
                                            <h3 class="card-title">Edit Profile</h3>
                                        </div>
                                        <div class="card-body">
                                            {{ Form::model($user, array('route' => array('profile.save', Auth::id()), 'method' => 'POST', 'role' => 'form')) }}

                                            <div class="form-group row m-2">
                                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                                                <div class="col-md-6">
                                                    <input readonly id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ $user->first_name }}" required autocomplete="name" autofocus>

                                                    @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row m-2">
                                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                                                <div class="col-md-6">
                                                    <input readonly id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ $user->last_name }}" required autocomplete="name" autofocus>

                                                    @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row m-2">
                                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                                <div class="col-md-6">
                                                    <input id="email" readonly type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}" required autocomplete="email">

                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row m-2">
                                                <label for="phone" class="col-md-4 col-form-label text-md-right">Phone Number</label>

                                                <div class="col-md-6">
                                                    <input id="phone" type="text" class="form-control @error('name') is-invalid @enderror" name="phone_" value="{{ $user->phone }}" required autocomplete="off" autofocus>

                                                    @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row m-2">
                                                <label for="name" class="col-md-4 col-form-label text-md-right">National Id</label>

                                                <div class="col-md-6">
                                                    <input readonly id="national_id" type="text" class="form-control @error('name') is-invalid @enderror" name="national_id" value="{{ $user->national_id }}" required autocomplete="name" autofocus>

                                                    @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- <div class="form-group row m-2">
                                                <label for="branch_id" class="col-md-4 col-form-label text-md-right">Branch</label>
                                                <div class="col-md-6">
                                                    {{ Form::text('branch', null, ['class' => 'form-control',
                        
                                                        'readonly' => true
                        
                                                    ]) }}
                                                </div>
                                            </div> -->
                        
                        
                        
   
                        
                                            <div class="form-group row m-2">
                                                <label for="branch_id" class="col-md-4 col-form-label text-md-right">Branch</label>
                                                <div class="col-md-6">
                                                    @php $branches = (\App\Models\Branch::pluck('name', 'id')) @endphp
                                                    @if (isset($branches))
                                                        {!! Form::select('branch_id', $branches, null, [
                                                            'class' => 'form-control select-2',
                                                            'data-msg-required' => 'Please pick a branch',
                                                            'placeholder' => 'Pick one',
                                                            'readonly' =>true,
                                                            // 'required' => true,
                                                        ]) !!}
                                                    @endif
                                                </div>
                                            </div>
                        


                                            <button class="btn btn-primary mt-3 waves-effect waves-light w-md" type="submit">Save</button>

                                            {{ Form::close() }}

                                        </div>
                                    </div>
                                    <!-- Personal-Information -->
                                    </p>
                                </div>
                                <div class="tab-pane" id="navtabs-messages" role="tabpanel">
                                    <p class="mb-0">
                                        Etsy mixtape wayfarers, ethical wes anderson tofu before they
                                        sold out mcsweeney's organic lomo retro fanny pack lo-fi
                                        farm-to-table readymade. Messenger bag gentrify pitchfork
                                        tattooed craft beer, iphone skateboard locavore carles etsy
                                        salvia banksy hoodie helvetica. DIY synth PBR banksy irony.
                                        Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh
                                        mi whatever gluten yr.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div> <!-- container -->


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->
        </div>
@endsection
