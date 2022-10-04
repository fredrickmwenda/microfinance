@extends('layouts.backend.app')

@push('css')

<link rel="stylesheet" href="{{ asset('assets/backend/admin/assets/css/own.css') }}">



@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Add New Customer') }}</h4>
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
											<!-- <span class="svg-icon pl-6">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"></polygon>
														<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)" x="7.5" y="7.5" width="2" height="9" rx="1"></rect>
														<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)"></path>
													</g>
												</svg>
											</span> -->
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

            <form method="POST" action="{{ route('customer.store') }}" class="basicform_with_reset">
              @csrf
              <div  class="data-wizard active" data-wizard-type="step-content" data-wizard-state="current">
                <div class="card-body">
                  <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('First Name') }}</label>
                        <input type="text" class="form-control" placeholder="First Name" required name="first_name">
                      </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Last Name') }}</label>
                        <input type="text" class="form-control" placeholder="Last Name" required name="last_name">
                      </div>
                    </div>
                  </div>
                  <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Phone') }}</label>
                            <input type="text" class="form-control" placeholder="Phone" required name="phone">
                        </div>
                      </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Email') }}</label>
                        <input type="email" class="form-control" placeholder="Email Address"  name="email">
                      </div>
                    </div>
                  </div>
                  <!-- <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Phone') }}</label>
                            <input type="text" class="form-control" placeholder="Phone" required name="phone_number">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Password') }}</label>
                          <input type="password" class="form-control" placeholder="Password" required name="password">
                        </div>
                      </div>
                  </div> -->
                  <!--national id and branch-->
                  <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('National ID') }}</label>
                        <input type="text" class="form-control" placeholder="National ID" required name="national_id">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Branch') }}</label>
                        <select required name="branch_id" class="form-control" required>
                          <option>-- {{ __('Select Branch') }} --</option>
                          @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <!--end national id and branch-->

                </div>
    
              </div>
              <div class="data-wizard" data-wizard-type="step-content">
                <!--Guarantor details, referee and next of kin details-->
                  <div class="card-body">
                    <div class="form-row">
                      <!--Guarantor first name, last name and phone number, national id number, date of birth and address-->
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor First Name') }}</label>
                          <input type="text" class="form-control" placeholder="Guarantor First Name" required name="guarantor_first_name">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor Last Name') }}</label>
                          <input type="text" class="form-control" placeholder="Guarantor Last Name" required name="guarantor_last_name">
                        </div>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor Phone Number') }}</label>
                          <input type="text" class="form-control" placeholder="Guarantor Phone Number" required name="guarantor_phone">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor National ID Number') }}</label>
                          <input type="text" class="form-control" placeholder="Guarantor National ID Number" required name="guarantor_national_id">
                        </div>
                      </div>
                    </div>
                    <!--guarantor address, guarantor email -->
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor Address') }}</label>
                          <input type="text" class="form-control" placeholder="Guarantor Address" required name="guarantor_address">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Guarantor Email') }}</label>
                          <input type="email" class="form-control" placeholder="Guarantor Email"  name="guarantor_email">
                        </div>
                      </div>
                    </div>
                    <hr>
                    <!--Referee first name, last name and phone number, relationship-->
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Referee First Name') }}</label>
                          <input type="text" class="form-control" placeholder="Referee First Name" required name="referee_first_name">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Referee Last Name') }}</label>
                          <input type="text" class="form-control" placeholder="Referee Last Name" required name="referee_last_name">
                        </div>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Referee Phone Number') }}</label>
                          <input type="text" class="form-control" placeholder="Referee Phone Number" required name="referee_phone">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Referee Relationship') }}</label>
                          <input type="text" class="form-control" placeholder="Referee Relationship" required name="referee_relationship">
                        </div>
                      </div>
                    </div>

                    <hr>
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Next of Kin first name') }}</label>
                          <input type="text" class="form-control" placeholder="Next of kin first name" name="next_of_kin_first_name">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Next of Kin last name') }}</label>
                          <input type="text" class="form-control" placeholder="Next of kin last name" name="next_of_kin_last_name">
                        </div>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Next of Kin phone') }}</label>
                          <input type="text" class="form-control" placeholder="Next of kin phone" name="next_of_kin_phone">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Next of Kin relationship') }}</label>
                          <input type="text" class="form-control" placeholder="Next of kin relationship" name="next_of_kin_relationship">
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

