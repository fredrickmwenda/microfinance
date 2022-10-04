@extends('layouts.app')

@section('content')

    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">

                    @if (!is_null($user))
                        <h4 class="mb-0 font-size-18">
                            List Of User Roles for {{$user->name}}
                        </h4>
                    @else
                        <h4 class="mb-0 font-size-18">
                            Role Logs
                        </h4>
                    @endif

                   

                    {{-- <div class="page-title-right">
                        <button type="button" class="btn btn-outline-danger float-end" data-bs-toggle="modal" data-bs-target="#addNewModal">
                            New Role
                        </button>
                    </div> --}}

                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- start page title -->
        <div class="row">
            <div class="card card-custom gutter-b ">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-condensed table-striped table-responsive-block" >
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>Assigned By</th>
                        <th>Assigned At</th>
                        @if (!$user)
                             <th>Assigned To</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($roles))
                        @foreach ($roles as $key => $role)
                            <tr>

                                <td> {{++$key}} </td>

                                <td>{{ ucfirst($role[0]) }}</td>

                                <td>{{ ucfirst($role[1]) }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                                
                                @if ($role[2] == "0000-00-00 00:00:00")

                                   <td>Not Defined</td>
                                    
                                @else
                                   <td>{{ ucfirst($role[2]) }}</td>
                                    
                                @endif

                                @if (!$user)
                                <td>{{ ucfirst($role[3]) }}</td>
                                @endif

                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>
                {{-- {{ $roles->links() }} --}}
            </div>
        </div>

    </div>
        </div>
    </div>
@endsection

@push('pagelevelscripts')
@endpush