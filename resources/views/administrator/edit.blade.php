@extends('layouts.backend.app')

@push('css')
<link rel="stylesheet" href="{{ asset('Backend/admin/assets/css/select2.min.css') }}">
@endpush

@section('content')
<div class="row">
	<div class="col-lg-9">      
		<div class="card">
			<div class="card-body">
				<h4>{{ __('Edit Admin') }}</h4>
				<form method="post" action="{{ route('admin.users.update',$user->id) }}" class="basicform">
                    @csrf
                    @method('PUT')
					<div class="pt-20">
						<div class="form-group">
							<label for="name">{{ __('First Name') }}</label>
							<input type="text" value="{{ $user->first_name }}" required class="form-control" name="name" placeholder="{{ __('Enter admin name') }}" >
						</div>

						<div class="form-group">
							<label for="name">{{ __('Last Name') }}</label>
							<input type="text" value="{{ $user->last_name }}" required class="form-control" name="last_name" placeholder="{{ __('Enter admin name') }}" >
						</div>
						
						<div class="form-group">
							<label for="email">{{ __('Email') }}</label>
							<input type="email" value="{{ $user->email }}" required class="form-control" name="email" placeholder="{{ __('Enter email') }}" >
						</div>
						
						<div class="form-group">
							<label for="email">{{ __('Phone') }}</label>
							<input type="number" value="{{ $user->phone }}" required class="form-control" name="phone" placeholder="{{ __('Enter phone') }}" >
						</div>

						<div class="form-group">
							<label for="password">{{ __('Password') }}</label>
							<input type="password"  class="form-control" name="password" placeholder="{{ __('Enter password') }}" >
						</div>
						<div class="form-group">
							<label for="password">{{ __('Confirm Password') }}</label>
							<input type="password"  class="form-control" name="password_confirmation" placeholder="{{ __('Confirmation password') }}">
						</div>
						
                        <div class="form-group">
                            <label for="roles">{{ __('Assign Roles') }}</label>
                                <select required name="role" id="role" class="form-control select2" >
									<!--get active role for this user-->
									@foreach($roles as $role)
									   @if($role->id == $user->role_id)
										<option value="{{ $role->id }}" selected>{{ $role->name }}</option>
									   @else
										<option value="{{ $role->id }}">{{ $role->name }}</option>
									   @endif
									@endforeach
                                </select>
                            </div>
                        <div class="form-group">
                        <label>{{ __('Status') }}</label>
							<select name="status" class="form-control">
								<option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
								<option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
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
@endpush