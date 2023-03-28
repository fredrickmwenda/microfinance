@extends('layouts.backend.app')

@push('css')
    <!-- select2 css -->
	<link href="{{asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/styles.css')}}" rel="stylesheet" type="text/css" />

@endpush
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"> User Profile</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Users</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="card mb-5 mb-xl-10">
                <div class="card-body pt-9 pb-0">
                    <div class="d-flex flex-wrap flex-sm-nowrap ">
                        <div class="me-7 mb-4">
                            <!--Profile picture-->
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                   @if(!empty(Auth::user()->avatar))
                                    <img src="{{ asset('assets/images/profile/'.Auth::user()->avatar) }}" class="rounded-circle" width="150"  alt="" />
                                    @else
                                    <img src="{{ asset('assets/backend/admin/assets/img/avatar/avatar-1.png') }}" class="rounded-circle" width="150" alt="">
                                    @endif
                                @if (session()->has('user_id'))
                                    <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                                @else
                                    <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-danger rounded-circle border border-4 border-body h-20px w-20px"></div>
                                @endif
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $user->name }}</a>
                                        <span class="badge badge-light-success fw-bolder fs-8 px-4 py-2" style="color: black;">Active</span>
                                    </div>

                                    <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <!--get rounded small user icon-->
                                            <span class="me-1">
                                                <i class="bx bx-user fs-7"></i>
                                            </span>
                                            @php 
                                             
                                             $role = \Spatie\Permission\Models\Role::where('id', $user->role_id)->get();
                                             
                                             $role= $role[0]['name'];
                                             
                                            @endphp
                                            {{$role}}
                                        </a>

 

                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <span class="me-1">
                                                <i class="bx bx-envelope fs-7"></i>
                                            </span>
                                            {{ $user->email }}
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap flex-stack">
                                <!--phone number-->
                                <span class="fw-bolder text-gray-400 me-2">
                                    Phone: {{ $user->phone }}
                                </span>
                            </div>
                        </div>
                        

                    </div>

                </div>
            </div>


        </div>
    </div> 
</div>

@endsection


@push('js')


@endpush