@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-6">
                    <h4>{{ __('User Profile') }}</h4>
                    </div>
                </div>
                <!-- @if (Session::has('message'))
                <div class="alert alert-danger">{{ Session::get('message') }}</div>
                @endif -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    @if(!empty(Auth::user()->avatar))
                                    <img src="{{ asset('assets/images/profile/'.Auth::user()->avatar) }}" class="rounded-circle" width="150"  alt="" />
                            
                                    @else
                                    <img src="{{ asset('assets/backend/admin/assets/img/avatar/avatar-1.png') }}" class="rounded-circle" width="150" alt="">
                                    @endif
                                    <h4 class="card-title mt-10">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                                    <p class="card-subtitle">{{ Auth::user()->email }}</p>

                                </div>
                                <p>{{ Auth::user()->bio }}</p>

                            </div>

                            <div class="card-footer">
                                <a href="{{ route('logout') }}" class="btn btn-danger btn-block" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">   
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Edit Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Change Password</a>
                            </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="card-body">
                                        <table class="table table-striped">
                                            <tr>
                                            <th>First Name</th>
                                            <td>{{ Auth::user()->first_name }}</td>
                                            </tr>
                                            <tr>
                                            <th>Last Name</th>
                                            <td>{{ Auth::user()->last_name }}</td>
                                            </tr>
                                            <tr>
                                            <th>Email</th>
                                            <td>{{ Auth::user()->email }}</td>
                                            </tr>
                                            <tr>
                                            <th>Phone</th>
                                            <td>{{ Auth::user()->phone }}</td>
                                            </tr>
                                            <tr>
                                            <th>National ID</th>
                                            <td>{{ Auth::user()->national_id }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="card-body">
                                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="first_name">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ Auth::user()->first_name }}">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="last_name">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ Auth::user()->last_name }}">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="phone">Phone</label>
                                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone }}">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="national_id">National ID</label>
                                                    <input type="text" class="form-control" id="national_id" name="national_id" value="{{ Auth::user()->national_id }}">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="profile_picture">Profile Picture</label>
                                                    <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="bio">Bio</label>
                                                <textarea class="form-control" id="bio" name="bio" rows="3">{{ Auth::user()->bio }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Update Profile</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                    <div class="card-body">
                                        <form  id="changePassword">
                                            @csrf
                                            <div class="form-group">
                                                <label for="old_password">Old Password</label>
                                                <input type="password" name="old_password" id="old_password" class="form-control">
                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="password">New Password</label>
                                                <input type="password" name="new_password" id="new_password" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirm Password</label>
                                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary" id="changePasswordBtn">Change Password</button>
                                            
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    // submit the change password form
    $('#changePassword').submit(function(e) {
        e.preventDefault();
        //get the form data values
        var currentPassword = $('#old_password').val();
        console.log(currentPassword);
        var newPassword = $('#new_password').val();
        console.log(newPassword);   
        var confirmPassword = $('#password_confirmation').val();
        console.log(confirmPassword);
        // check that current password, new password and confirm password are not empty
        if (currentPassword == '' || newPassword == '' || confirmPassword == '') {
            console.log('Please fill all fields');
            // use toast from sweetalert
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'All fields are required!',
            })
        } 
        // check that new password and confirm password are equal
        else if (newPassword != confirmPassword) {
            console.log('Passwords do not match');
            // use toast from sweetalert
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Passwords do not match!',
            })
        }
            else {
                console.log('All good');
                // check that current password is correct
                //include the token
                var token = $('input[name=_token]').val();
                
                $.ajax({
                    url: "{{ route('change.password') }}", // url where to submit the request                 
                    type: 'POST',

                    data: {
                        old_password: currentPassword,
                        new_password: newPassword,
                        confirm_password: confirmPassword,
                        _token: token,
                    }, // data to submit
                   //dataType: 'json', // what type of data do we expect back from the server

                    success: function(data) {
                        console.log(data);
                        if (data.success) {
                            console.log(data.success);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Password changed successfully!',
                            })
                            // toast({
                            //     type: 'success',
                            //     title: 'Password Changed Successfully'
                            // })
                            // toastr.success("Password changed successfully");
                             $('#changePassword').trigger("reset");
                            
                        }
                        else {
                            console.log(data.error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Current password is incorrect!',
                            })
                        }
                    },

                    error: function(reject) {
                        console.log(reject);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                    }
                });             
            }
        }
       
    );

    //call function submitForm() when the submit button is clicked
    $('#changePasswordBtn').click(function(e) {
        e.preventDefault();
        //call the function to change the password
        $('#changePassword').submit();
    });


</script>


		<!--end::Page Vendors-->
		<!--begin::Page Scripts(used by this page)-->


@endpush