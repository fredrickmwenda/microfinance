@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Roles'])
@endsection

@section('content')
<div class="card"  >
	<div class="card-body">
		<div class="row mb-30">
			<div class="col-lg-6">
				<h4>{{ __('Roles') }}</h4>
			</div>
			<div class="col-lg-6">
				<div class="add-new-btn">
				</div>
			</div>
		</div>
		<br>
		<div class="card-action-filter">
			<form method="post" class="basicform_with_reload"  action="{{ route('admin.role.destroy') }}">
				@csrf
				<div class="row">
					<div class="col-lg-6">
						@can('role.delete')
						<div class="d-flex">
							<div class="single-filter">
								<div class="form-group">
									<select class="form-control selectric" name="status">
										<option value="publish">{{ __('Select Action') }}</option>
										<option value="delete">{{ __('Delete Permanently') }}</option>
									</select>
								</div>
							</div>
							<div class="single-filter">
								<button type="submit" class="btn btn-primary btn-lg ml-2 basicbtn">{{ __('Apply') }}</button>
							</div>
						</div>
						@endcan
					</div>
					<div class="col-lg-6">
						@can('role.create')
						<a href="{{ route('admin.role.create') }}" class="btn btn-primary float-right">{{ __('Add New Role') }}</a>
						@endcan
					</div>
				</div>
			</div>
			<div class="table-responsive custom-table">
				<table class="table">
					<thead>
						<tr>
							<th class="am-select" width="10%">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input checkAll" id="selectAll">
									<label class="custom-control-label checkAll" for="selectAll"></label>
								</div>
							</th>
							<th>{{ __('Name') }}</th>
							<th>{{ __('Permissions') }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($roles as $page)
						<tr>
							<th>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" name="ids[]" class="custom-control-input" id="customCheck{{ $page->id }}" value="{{ $page->id }}">
									<label class="custom-control-label" for="customCheck{{ $page->id }}"></label>
								</div>
							</th>
							<td>
								{{ $page->name }}
								@can('role.edit')
								<div class="hover">
									<a href="{{ route('admin.role.edit',$page->id) }}">{{ __('Edit') }}</a>
								</div>
								@endcan
							</td>
							<td>
								@foreach ($page->permissions as $perm)
								<span class="badge badge-primary mr-1 mb-2">
									{{ $perm->name }}
								</span>
								@endforeach
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

@section('script')
<script src="{{ asset('admin/js/form.js') }}"></script>
@endsection