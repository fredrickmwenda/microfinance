@extends('layouts.backend.app')

@push('css')
<link rel="stylesheet" href="{{ asset('Backend/admin/assets/css/select2.min.css') }}">
@endpush

@section('content')
<div class="row">
	<div class="col-lg-9">      
		<div class="card">
			<div class="card-body">
				<h4>{{ __('Add Admin') }}</h4>
				<form method="post" action="{{ route('admin.users.store') }}" class="basicform_with_reset">
					@csrf
					<div class="pt-20">
						<div class="form row">
							<div class="form-group col-md-6">
								<label for="first_name">{{ __('First Name') }}</label>
								<input type="text" class="form-control" id="first_name" name="first_name" placeholder="{{ __('First Name') }}" value="{{ old('first_name') }}">
							</div>
							<div class="form-group col-md-6">
								<label for="last_name">{{ __('Last Name') }}</label>
								<input type="text" class="form-control" id="last_name" name="last_name" placeholder="{{ __('Last Name') }}" value="{{ old('last_name') }}">
							</div>
						</div>
							<!-- <div class="form-group">
								<label for="name">{{ __('First Name') }}</label>
								<input type="text" required class="form-control" name="name" placeholder="Enter admin name" >
							</div>
						</div> -->
						<div class="form row">
							<div class="form-group col-md-6">
								<label for="email">{{ __('Email') }}</label>
								<input type="email" required class="form-control" name="email" placeholder="Enter email"  value = "{{ old('email') }}">
							</div>
							<div class="form-group col-md-6">
								<label for="phone">{{ __('Phone') }}</label>
								<input type="text" required class="form-control" name="phone" placeholder="Enter phone" value = "{{ old('phone') }}">
							</div>
						</div>

						<div class="form row">
							<div class="form-group col-md-6">
							    <label for="email">{{ __('National Id') }}</label>
								<input type="number" required class="form-control" name="national_id" placeholder="Enter national id" value = "{{ old('national_id') }}">
							</div>
							<div class="form-group col-md-6">
								<label for="status">{{ __('Branch') }}</label>
							    <select required name="branch_id" id="branch_id" class="form-control" value = "{{ old('branch_id') }}">
									<option value="">Select Branch</option>
									@foreach ($branches as $branch)
										<option value="{{ $branch->id }}">{{ $branch->name }}</option>
									@endforeach
							    </select>
							</div>
						</div>
	

						
						<div class="form row">
							<div class="form-group col-md-6">
								<label for="password">{{ __('Password') }}</label>
								<!-- <input type="password" required class="form-control" name="password" placeholder="Enter password" > -->
								<div class="input-group" id="show_hide_password">
									<input type="password" required class="form-control" name="password" placeholder="Enter password" >
									<div class="input-group-append">
										<span class="input-group-text toggle-password" id="basic-addon2" style="cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="password_confirmation">{{ __('Confirm Password') }}</label>
								<div class="input-group" id="show_hide_password">
									<input type="password" required class="form-control" name="password_confirmation" placeholder="Enter confirm password" >
									<div class="input-group-append">
										<span class="input-group-text toggle-password" id="basic-addon2" style="cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
									</div>
								</div>
								<!-- <input type="password" required class="form-control" name="password_confirmation" placeholder="Enter confirm password" > -->
							</div>
						</div>
                        
						<div class="form row">
							<div class="form-group col-md-6">
								<label >{{ __('Assign Roles') }}</label>
								<select required name="role_id" id="roles" class="form-control" value = "{{ old('role_id') }}">
									<option value="">Select Role</option>
									@foreach ($roles as $role)
										<option value="{{ $role->id }}">{{ $role->name }}</option>
									@endforeach
								</select>
							</div>

							<!--users status active or inactive-->
							<div class="form-group col-md-6">
								<label for="status">{{ __('Status') }}</label>
								<select required name="status" id="status" class="form-control" value = "{{ old('status') }}">
									<option value="">Select Status</option>
									<option value="active">Active</option>
									<option value="inactive">Inactive</option>
								</select>
							</div>



					</div>
				</div>
			</div>
		</div>
	<div class="col-lg-3">
		<div class="single-area">
			<div class="card">
				<div class="card-body">
					<div class="btn-publish">
						<button type="submit" class="btn btn-primary col-12 basicbtn"><i class="fa fa-save"></i> {{ __('Save') }}</button>
					</div>
				</div>
			</div>
		</div>	
	</div>
</form>
@endsection
@push('js')
<script src="{{ asset('Backend/admin/assets/js/select2.min.js') }}"></script>
<!-- on  click eye icon show password -->
<script text="text/javascript">
	$(document).ready(function(){
        $(".toggle-password").click(function () {
            event.preventDefault();
            console.log('clicked');
            $("#show_hide_password i").toggleClass("fa-eye fa-eye-slash");
            if($("#show_hide_password input").attr("type") == "text"){
                $("#show_hide_password input").attr("type", "password");
            }else if($("#show_hide_password input").attr("type") == "password"){
                $("#show_hide_password input").attr("type", "text");
            }
        });
	});
</script>
@endpush