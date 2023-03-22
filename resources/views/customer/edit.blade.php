@extends('layouts.backend.app')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/backend/admin/assets/css/own.css') }}">
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Edit Customer')}}, {{$customer->first_name.$customer->last_name }}</h4>
            </div>
            @if ($errors->any())
              <div class="alert alert-danger">
                  <strong>{{ __('Whoops!') }}</strong> {{ __('There were some problems with your input.') }}<br><br>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <div class="container">
              <div class="wizard-nav pt-5 pt-lg-15 pb-10">
								<div class="wizard-steps d-flex flex-column flex-sm-row">
									<div class="wizard-step flex-grow-1 flex-basis-0" data-wizard-type="step" data-wizard-state="current">
										<div class="wizard-wrapper pr-7">
											<div class="wizard-icon">
												<i class="wizard-check ki ki-check"></i>
												<span class="wizard-number">1</span>
											</div>
											<div class="wizard-label">
												<h3 class="wizard-title"><strong>{{ __('Customer Details') }}</strong></h3>
												<!-- <div class="wizard-desc">Account details</div> -->
											</div>
                      <span class="svg-icon svg-icon-xl wizard-arrow">
                        <i class="fas fa-arrow-right"></i>
                      </span>
										</div>
									</div>

									<div class="wizard-step flex-grow-1 flex-basis-0" data-wizard-type="step" data-wizard-state="pending">
										<div class="wizard-wrapper">
											<div class="wizard-icon">
												<i class="wizard-check ki ki-check"></i>
												<span class="wizard-number">2</span>
											</div>
											<div class="wizard-label">
												<h3 class="wizard-title"><strong>Customer Relations</strong></h3>
												<div class="wizard-desc"><strong>Submit Form</strong></div>
											</div>
										</div>
									</div>
									
								</div>
								<!--end::Wizard Steps-->
							</div>
            </div>

            <form method="POST" action="{{ route('customer.update', $customer ) }}"  enctype="multipart/form-data" >
              @csrf
              @method('PUT')
              <div  class="data-wizard active" data-wizard-type="step-content" data-wizard-state="current">
                <div class="card-body">
                  <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('First Name') }}</label>
                        <input type="text" class="form-control" placeholder="First Name" required name="first_name" value="{{$customer->first_name}}" >
                      </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Last Name') }}</label>
                        <input type="text" class="form-control" placeholder="Last Name" required name="last_name"  value="{{$customer->last_name}}" >
                      </div>
                    </div>
                  </div>
                  <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Phone') }}</label>
                            <input type="text" class="form-control" placeholder="Phone" required name="phone"  value="{{$customer->phone}}">
                        </div>
                      </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Email') }}</label>
                        <input type="email" class="form-control" placeholder="Email Address"  name="email"  value="{{$customer->email}}">
                      </div>
                    </div>
                  </div>
    
                  <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('National ID') }}</label>
                        <input type="text" class="form-control" placeholder="National ID" required name="national_id"  value="{{$customer->national_id}}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Branch') }}</label>
                        <select required name="branch_id" class="form-control" required>
                          <option>-- {{ __('Select Branch') }} --</option>
                          @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{$branch->id == $customer->branch->id ? 'selected' : ''}}>{{ $branch->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <!--end national id and branch-->
                  <div class="form-row">
                    <div class="col-lg-12 col-md-6 col-sm-12">
                      <div class="form-group">          
                        <label>{{ __('Customer Passport') }}</label>
                        <input type="file" class="form-control" name="passport_photo" multiple>
                      </div>
                    </div>
                  </div>

                </div>
    
              </div>
              <div class="data-wizard" data-wizard-type="step-content">
                  <div class="card-body">
                    <div class="form-row">
                      <!--Guarantor first name, last name and phone number, national id number, date of birth and address-->
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor First Name') }}</label>
                          <input type="text" class="form-control" placeholder="Guarantor First Name" required name="guarantor_first_name"  value="{{$customer->guarantor_first_name}}">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor Last Name') }}</label>
                          <input type="text" class="form-control" placeholder="Guarantor Last Name" required name="guarantor_last_name"  value="{{$customer->guarantor_last_name}}">
                        </div>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor Phone Number') }}</label>
                          <input type="text" class="form-control" placeholder="Guarantor Phone Number" required name="guarantor_phone"  value="{{$customer->guarantor_phone}}">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor National ID Number') }}</label>
                          <input type="text" class="form-control" placeholder="Guarantor National ID Number" required name="guarantor_national_id" value="{{$customer->guarantor_phone}}">
                        </div>
                      </div>
                    </div>
                    <!--guarantor address, guarantor email -->
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor Address') }}</label>
                          <input type="text" class="form-control" placeholder="Guarantor Address" required name="guarantor_address" value="{{$customer->guarantor_address}}">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor Email') }}</label>
                          <input type="email" class="form-control" placeholder="Guarantor Email"  name="guarantor_email"  value="{{$customer->guarantor_email}}">
                        </div>
                      </div>
                    </div>
                    <hr>
                    <!--Referee first name, last name and phone number, relationship-->
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Referee First Name') }}</label>
                          <input type="text" class="form-control" placeholder="Referee First Name" required name="referee_first_name"  value="{{$customer->referee_first_name}}">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Referee Last Name') }}</label>
                          <input type="text" class="form-control" placeholder="Referee Last Name" required name="referee_last_name"  value="{{$customer->referee_last_name}}">
                        </div>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Referee Phone Number') }}</label>
                          <input type="text" class="form-control" placeholder="Referee Phone Number" required name="referee_phone" value="{{$customer->referee_last_name}}">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Referee Relationship') }}</label>
                          <input type="text" class="form-control" placeholder="Referee Relationship" required name="referee_relationship" value="{{$customer->referee_last_name}}">
                        </div>
                      </div>
                    </div>

                    <hr>
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Next of Kin first name') }}</label>
                          <input type="text" class="form-control" placeholder="Next of kin first name" name="next_of_kin_first_name" value="{{$customer->next_of_kin_first_name}}">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Next of Kin last name') }}</label>
                          <input type="text" class="form-control" placeholder="Next of kin last name" name="next_of_kin_last_name" value="{{$customer->next_of_kin_last_name}}">
                        </div>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Next of Kin phone') }}</label>
                          <input type="text" class="form-control" placeholder="Next of kin phone" name="next_of_kin_phone" value="{{$customer->next_of_kin_phone}}">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Next of Kin relationship') }}</label>
                          <input type="text" class="form-control" placeholder="Next of kin relationship" name="next_of_kin_relationship" value="{{$customer->next_of_kin_relationship}}">
                        </div>
                      </div>


                    </div>             
                  </div>
              </div>

              <div class="d-flex justify-content-between pt-7" style="margin: 0 40px;">
                <div class="mr-2">
                  <button type="button" class="btn btn-light-primary font-weight-bolder font-size-h6 pr-8 pl-6 py-4 my-3 mr-3" data-wizard-type="action-prev" id="customer_prev" >
                  <span class="svg-icon svg-icon-md mr-2">
                    <i class="fas fa-chevron-left"></i>

                  </span>Previous</button>
                </div>
                <div>
                  <button class="btn btn-primary font-weight-bolder font-size-h6 pl-8 pr-4 py-4 my-3" data-wizard-type="action-submit" type="submit" id="kt_login_signup_form_submit_button">Submit 
                    <span class="svg-icon svg-icon-md ml-2">
                      <!--Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Navigation/Right-2.svg-->
                      <i class="fas fa-chevron-right"></i>

                    </span>
                  </button>
                  <button type="button" class="btn btn-primary font-weight-bolder  pl-8 pr-4 py-4 my-3" data-wizard-type="action-next" style="width: 300px; " id="customer_next">Next  
                  <span class="svg-icon svg-icon-md ml-2">
                    <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Navigation/Right-2.svg-->
                    <i class="fas fa-chevron-right"></i>

                    <!--end::Svg Icon-->
                  </span></button>
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@push('js')
<script>
    $(document).ready(function(){
        $('#customer_next').click(function(){
           console.log('clicked');
           //check the input fields with required attribute in data-wizard-state="current" div and if they are empty, add the is-invalid class to the parent div
            var required = $('[data-wizard-state="current"]').find('input[required]');
            // console.log(required);
            var error = false;
            required.each(function(){
                if($(this).val() == ''){
                    error = true;
                }
            });
            if(error){
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all required fields!',
              })
            }
            else{
                //hide this button and show the submit button
                $('#customer_next').hide();
                $('#customer_prev').show();
                $('#kt_login_signup_form_submit_button').show();
                //remove data-wizard-state="current" from the current wizard step and add it to the next wizard step has data-wizard-state="pending"
                $('.wizard-steps').find('[data-wizard-state="current"]').attr('data-wizard-state', 'done').next().attr('data-wizard-state', 'current');
                $('.data-wizard').removeClass('active').next().addClass('active');
            }
        });

        $('#customer_prev').click(function(){
            //hide this button and show the submit button          
            $('#customer_prev').hide();
            $('#kt_login_signup_form_submit_button').hide();
            $('#customer_next').show(); 
            //remove data-wizard-state="current" from the current wizard step and add it to the next wizard step has data-wizard-state="pending"
            $('.wizard-steps').find('[data-wizard-state="current"]').attr('data-wizard-state', 'pending').prev().attr('data-wizard-state', 'current');
            $('.data-wizard').removeClass('active').prev().addClass('active');
        });
    });     
</script>
@endpush
@endsection

