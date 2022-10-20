@extends('layouts.backend.app')

@section('content')
<div class="card">
	<div class="card-body">
		<div class="row mb-30">
			<div class="col-lg-6">
				<h4>{{ __('Admin') }}</h4>
			</div>
			<div class="col-lg-6">
			</div>
		</div>
		<br>
		<div class="card-action-filter">
			<form method="post" id="basicform" action="#">
				@csrf
				<div class="row">
					<div class="col-lg-6">
						<div class="d-flex">
							<div class="single-filter">
								<div class="form-group">
									<select class="form-control selectric" name="status">
                                        <option disabled selected>Select Action</option>
										<option value="1">Active<option>
										<option value="0">Deactivate</option>
										<option value="delete">Delete Permanently</option>
									</select>
								</div>
                            </div>
                            @can('user.edit')
							<div class="single-filter">
								<button type="submit" class="btn btn-primary btn-lg ml-2">{{ __('Apply') }}</button>
                            </div>
                            @endcan
						</div>
					</div>
					<div class="col-lg-6">
						@can('user.create')
						<div class="add-new-btn">
							<a href="{{ route('admin.users.create') }}" class="btn btn-primary float-right">{{ __('Add New') }}</a>
						</div>
						@endcan
					</div>
				</div>
			</div>
			<div class="table-responsive custom-table">
				<table class="table" id="users-table">
					<thead>
						<tr>
							<th>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input checkAll" id="selectAll">
									<label class="custom-control-label checkAll" for="selectAll"></label>
								</div>
							</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
							<th> {{ __('Branch') }}</th>
                            <th>{{ __('Status') }}</th>                          
                            <th>{{ __('Role') }}</th>
							<th> {{ __('Action') }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($users as $row)
						<tr>
							<td>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" name="ids[]" class="custom-control-input" id="customCheck{{ $row->id }}" value="{{ $row->id }}">
									<label class="custom-control-label" for="customCheck{{ $row->id }}"></label>
								</div>
							</td>
							<td>
                                {{ $row->first_name }} {{ $row->last_name }}
                                <!-- @can('user.edit')
								<div class="hover">
									<a href="{{ route('admin.users.edit',$row->id) }}">{{ __('Edit') }}</a>									
                                </div>
                                @endcan -->
                            </td>
                            <td>
                               {{ $row->email }}                               
							</td>
							
                            <td>
                               {{ $row->phone }}                               
							</td>

							<td>
								@php
									$branch = App\Models\Branch::where('id',$row->branch_id)->first();
								@endphp
								{{ $branch->name }}

							</td>
							
                            <td>
                            @if($row->status== "active")
                            <span class="badge badge-success">{{ __('Active') }}</span>
                            @else
                            <span class="badge badge-danger">{{ __('Deactive') }}</span>
                            @endif
                            </td>
							<td>
								@php
								$roles = $roles->where('id',$row->role_id)->first();
								$role = $roles->name;
								@endphp
								<span class="badge badge-primary">{{ $role }}</span>
							</td>

							<td>
							    <div class="dropdown d-inline">
									<button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown">
										{{ __('Action') }}
									</button>
									<div class="dropdown-menu">
										@can('user.edit')
										<a class="dropdown-item has-icon" href="{{ route('admin.users.edit',$row->id) }}"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>
										@endcan
										@can('user.delete')
										<a class="dropdown-item has-icon" href="{{ route('admin.users.delete',$row->id) }}"><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
										@endcan
									</div>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</form>
			</table>
		</div>
	</div>
</div>
@endsection

@push('js')
<!-- make the table a  datatable -->
<script>
	$(document).ready(function() {
		$('#users-table').DataTable({
			"order": [[ 0, "desc" ]]
		});
	});
</script>

@endpush