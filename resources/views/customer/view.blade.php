@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'User View'])
@endsection

@section('content')
<div class="row">
   <div class="col-12">
      <!-- main Section -->
      <div class="row">
        <div class="col-md-5 col-lg-5 col-sm-12">
            <div class="card">
               <div class="card-body">
                  <div class="card profile-widget">
                     <div class="profile-widget-header d-flex ">
                        <img alt="image" src="{{ !empty($user_transactions->image) ? asset($user_transactions->image) : url('https://ui-avatars.com/api/?background=random&name='.$user_transactions->name) }}" class="rounded-circle profile-widget-picture mx-auto">
                     </div>
                  </div>
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Description') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{ __('Name') }}</td>
                        <td>{{ $user_transactions->first_name }}{{ $user_transactions->last_name }}</td>
                      </tr>
                      <tr>
                        <td>{{ __('National ID') }}</td>
                        <td>{{ $user_transactions->national_id }}</td>
                      </tr>
                      <tr>
                        <td>{{ __('Email') }}</td>
                        <td>{{ $user_transactions->email }}</td>
                      </tr>
                      <tr>
                        <td>{{ __('Phone') }}</td>
                        <td>{{ $user_transactions->phone }}</td>
                      </tr>
                      <tr>
                        <td>{{ __('Guarantor') }}</td>
                        <td>{{ $user_transactions->guarantor_first_name }} {{ $user_transactions->guarantor_last_name }}</td>
                      </tr>
                      <tr>
                        <td>{{ __('Status') }}</td>
                        <td>
                          @if ($user_transactions->status == 1)
                            <span class='badge badge-success'>{{ __('Active') }}</span>
                          @else 
                            <span class='badge badge-danger'>{{ __('Deactive') }}</span>
                          @endif
                         </td>
                      </tr>
                      <tr>
                        <td colspan=2>
                          <a href="{{ route('customer.edit', $user_transactions->id) }}" class="btn btn-primary btn-block">{{ __('Edit') }}</a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
               </div>
            </div>
        </div>
         <!-- col 5 -->
         <div class="col-md-7 col-lg-7 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="card card-statistic-1">
                          <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                          </div>
                          <div class="card-wrap">
                            <div class="card-header">
                              <h4>{{ __('TOTAL DEPOSITED') }}</h4>
                            </div>
                            <div class="card-body">
                              
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="card card-statistic-1">
                          <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                          </div>
                          <div class="card-wrap">
                            <div class="card-header">
                              <h4>{{ __('TOTAL WITHDRAWAL') }}</h4>
                            </div>
                            <div class="card-body">
                              
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="card card-statistic-1">
                          <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                          </div>
                          <div class="card-wrap">
                            <div class="card-header">
                              <h4>{{ __('TOTAL FEE') }}</h4>
                            </div>
                            <div class="card-body">
                              
                            </div>
                          </div>
                        </div>
                      </div>
                    </div><!-- End card -->
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <a class="btn btn-primary" href="{{ route('customer.transaction.report', ['withdraw', $customer_id]) }}"> {{ __('Withdraw Report') }}</a>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <a class="btn btn-info" href="{{ route('customer.transaction.report', ['all transaction', $customer_id]) }}">{{ __('Transaction Report') }}</a>
                        </div>
                    </div><!-- End card -->
                    <br><br>
                    <div class="row">
                        <div class="col-12">
                            <h5>{{ __('Add Debit / Credit Balance') }}</h5>
                            <br>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal1" data-whatever="@mdo">{{ __('Balance Debit / Credit') }}</button>
                        </div>
                        <!-- Model start -->
                    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Credits') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form method="POST" action="#"  class="basicform_with_reset">
                              @csrf
                              @method('PUT')
                              <div class="form-group">
                                <label>{{ __('Select Balance Type') }}</label>
                                <select class="form-control" name="bln_type" id="bln_type">
                                
                                  <option value="debit">{{ __('Debit') }}</option>
                                  <option value="credit">{{ __('Credit') }}</option>
                                </select>
                              </div>
                              <div class="form-group">
                                <label>{{ __('Amount') }}</label>
                                <input type="number" class="form-control" name="amount" placeholder="Amount">
                              </div>
                              <div class="form-group">
                                <label>{{ __('Description') }}</label>
                                <textarea required class="form-control" name="description" cols="30" rows="10"></textarea>
                              </div>
                              <button type="submit" class="btn btn-primary basicbtn">{{ __('Add') }}</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  <!-- End Model -->
                    </div><!-- End card -->
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">{{ __('Email Send') }}</button>
                        </div>
                    </div><!-- End card -->
                    <!-- Model start -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">{{ __('Send Email message') }}</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <form method="POST" action="{{ route('customer.transaction.mail', $customer_id) }}" class="basicform">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                  <label for="recipient-name" class="col-form-label">{{ __('Subject') }}</label>
                                  <input type="text" class="form-control" id="recipient-name" name="subject">
                                </div>
                                <div class="form-group">
                                  <label for="message-text" class="col-form-label">{{ __('Message') }}</label>
                                  <textarea name="msg" class="form-control" id="message-text"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary basicbtn">{{ __('Send message') }}</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    <!-- End Model -->
                </div>
            </div>
         </div>
         <!-- col 7 -->
      </div><!-- End Main Row -->

      <!-- Transection Section -->
            <div class="card">
                <div class="card-body">    
                    <div class="row">
                        <div class="col-12">
                            <h5>{{ __('Transactions list') }}</h5>
                            <table class="table">
                               <tbody>
                                    <tr>
                                      <th>{{ __('Trxid') }}</th>
                                      <th>{{ __('Amount') }}</th>
                                      <th>{{ __('Balance') }}</th>
                                      <th>{{ __('Fee') }}</th>
                                      <th>{{ __('Status') }}</th>
                                      <th>{{ __('Type') }}</th>
                                    </tr>
                               </tbody>
                               <tbody>
                                @if(isset($transactions))
                                 @foreach($user_transactions->transactions as $row)
                                    <tr>
                                        <td>{{ $row->trxid }}</td>
                                        <td>{{ $row->amount }}</td>
                                        <td>{{ $row->balance }}</td>
                                        <td>{{ $row->fee }}</td>
                                        @if($row->status == 1)
                                        <td class="text-success">{{ __('Active') }}</td>
                                        @endif
                                        @if($row->status == 0)
                                        <td class="text-danger">{{ __('Inactive') }}</td>
                                        @endif
                                        <td>{{ $row->type }}</td>
                                    </tr>
                                  @endforeach
                                @endif
                               </tbody>
                            </table>
                        </div>
                    </div><!-- End card -->
                </div>
            </div>
   </div><!-- End col 12 -->
</div><!-- End row -->
@endsection