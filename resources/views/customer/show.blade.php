@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-6">
                    <h4>{{ __('Customer Details') }}</h4>
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
                                    <img src="{{ asset('assets/backend/admin/assets/img/avatar/avatar-1.png') }}" class="rounded-circle" width="150" alt="">
                                    <h4 class="card-title mt-10">{{ $customer->first_name }} {{ $customer->last_name }}</h4>
                                    <p class="card-subtitle">{{ $customer->email }}</p>
                                    <!--customer Phone Number-->
                                    <p class="card-subtitle"><span>Phone: </span>{{ $customer->phone }}</p>
                                    <p class="card-subtitle"><span>ID: </span>{{ $customer->national_id }}</p>
                                     <p class="card-subtitle"><span>Branch: </span>{{ $customer->branch->name }}</p>
                                    

                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">   
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Other Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="loans-tab" data-toggle="tab" href="#loans" role="tab" aria-controls="loans" aria-selected="false">Customer Loans</a>
                            </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="card-body">
                                        <table class="table table-striped">
                                            <tr>
                                            <th>Guarantor Name</th>
                                            <td>{{ $customer->guarantor_first_name }} {{ $customer->guarantor_last_name }}</td>
                                            </tr>
                                            <tr>
                                            <th>Guarantor Phone</th>
                                            <td>{{ $customer->guarantor_phone }}</td>
                                            </tr>
                                            <tr>
                                            <th>Guarantor National ID</th>
                                            <td>{{ $customer->guarantor_national_id }}</td>
                                            </tr>
                                            <tr>
                                            <th>Guarantor Address</th>
                                            <td>{{ $customer->guarantor_address }}</td>
                                            </tr>
                                            <tr>
                                            <th>Referee Name</th>
                                            <td>{{ $customer->referee_first_name }} {{ $customer->referee_last_name }}</td>
                                            </tr>
                                            <tr>
                                            <th>Referee Phone</th>
                                            <td>{{ $customer->referee_phone }}</td>
                                            </tr>
                                            <tr>
                                            <th>Referee Relationship</th>
                                            <td>{{ $customer->referee_relationship }}</td>
                                            </tr>
                                            <tr>
                                            <th>Next of Kin Name</th>
                                            <td>{{ $customer->next_of_kin_first_name }} {{ $customer->next_of_kin_last_name }}</td>
                                            </tr>
                                            <tr>
                                            <th>Next of Kin Phone</th>
                                            <td>{{ $customer->next_of_kin_phone }}</td>
                                            </tr>
                                            <tr>
                                            <th>Next of Kin Relationship</th>
                                            <td>{{ $customer->next_of_kin_relationship }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="loans" role="tabpanel" aria-labelledby="loans-tab">
                                    <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="loanPendingTable-2">
                                            <thead>
                                                <tr>
                                                <th>{{ __('Loan ID') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Amount') }} </th>
                                                <th>{{ __('Payable') }} </th>
                                                <th>{{ __('Interest') }}</th>
                                                <th>{{ __('Duration') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Creator')}} </th>
                                                <th> {{__('Start Date')}} </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($loans as $loan)
                                                <tr>
                                                <td>{{ $loan->loan_id }}</td>
                                                <td>
                                                    {{ $loan->customer->first_name }} {{ $loan->customer->last_name }}
                                                </td>
                                                <td>{{ $loan->amount }}</td>
                                                <td>{{ $loan->total_payable }}</td>
                                                <td>{{ $loan->interest }}</td>
                                                <td>{{ $loan->duration }}</td>
                                                <td>                                                    
                                                    <span class="badge badge-uccess">{{ $loan->status }}</span>                                                   
                                                </td>
                                                <!--get the user who created the loan using created_by column-->

                                                <td>{{ $loan->creator->first_name }} {{ $loan->creator->last_name }}</td>
                                                <td>{{ $loan->start_date }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

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