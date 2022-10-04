@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Manage Payment Gateway'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <!-- <div class="card"> -->
        <!--at the center have an icon to add new payment gateway-->
        <div class="text-center" style=" background-color: transparent">
          <a href="{{ route('admin.payment-gateway.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ __('Add New Payment Gateway') }}</a>
        </div>
      <!-- </div> -->
      <!--display all payment gateways in cards with their icons-->
      <div class="row">
        @foreach($payment_gateways as $payment_gateway)
        <div class="col-md-4">
          <div class="card">
            <!--have 3 dots on the left of the card to edit or delete the payment gateway-->
            <div class="card-header border-0 pt-5">
              <h3 class="card-title font-weight-bolder">{{ $payment_gateway->name }}</h3>
              <div class="card-header-right">
                <div class="dropdown dropdown-inline" data-toggle="tooltip" title="" data-placement="left" data-original-title="Quick actions">
                  <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-md dropdown-menu-right" >
                    <ul class="navi navi-hover py-2">
                      <li class="navi-item">
                        <!-- View -->
                        <a href="{{ route('admin.payment-gateway.show',$payment_gateway->id) }}" class="navi-link">
                          <span class="navi-icon"><i class="fas fa-eye"></i></span>
                          <span class="navi-text">{{ __('View') }}</span>
                        </a>
                      </li>
                      <li class="navi-item">
                        <!-- Edit -->
                        <a href="{{ route('admin.payment-gateway.edit',$payment_gateway->id) }}" class="navi-link">
                          <span class="navi-icon"><i class="fas fa-edit"></i></span>
                          <span class="navi-text">{{ __('Edit') }}</span>
                        </a>
                      </li>
                      <li class="navi-item">
                        <!-- Delete -->
                        <a href="javascript:;" data-id="{{ $payment_gateway->id }}" class="navi-link delete-confirm">
                          <span class="navi-icon"><i class="fas fa-trash"></i></span>
                          <span class="navi-text">{{ __('Delete') }}</span>
                        </a>
                      </li>
                    </ul>                
                  </div>
                </div>
							</div>
              <!-- <div class="card-header-right">
                <ul class="list-unstyled card-option">
                  <li><i class="ik ik-more-horizontal"></i></li>
                </ul>
              </div> -->
            </div>

            <div class="card-body">
              <!--get the image from the database-->
              <div class="flex-grow-1" style="position: relative;">
              @if(isset($payment_gateway->logo))
              <img src="{{ asset('assets/images/payment-gateways/'.$payment_gateway->logo) }}" alt="image" width="100px" height="100px">
              @endif
              </div>
              
             

              <!--button to  connect to the payment gateway-->
              <!-- <div class="text-center">
                <a href="{{ route('admin.payment-gateway.edit', $payment_gateway->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> {{ __('Edit') }}</a>
              </div> -->
              <hr>
              @if($payment_gateway->status == "active")
              <div class="mt-2">
                <!--disconnect button-->
                <a href="{{ route('admin.payment-gateway.disconnect', $payment_gateway->id) }}" class="btn btn-danger btn-lg w-100"><i class="fa fa-times"></i> {{ __('Disconnect') }}</a>
              </div>
              @else if ($payment_gateway->status == "inactive")
              <div class="text-center">
                <!--connect button-->
                <a href="{{ route('admin.payment-gateway.connect', $payment_gateway->id) }}" class="btn btn-success btn-sm"><i class="fa fa-check"></i> {{ __('Connect') }}</a>
              </div>
              @endif

              <!-- <div class="text-center">
                <a href="{{ route('admin.payment-gateway.edit', $payment_gateway->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> {{ __('Edit') }}</a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteData({{ $payment_gateway->id }})"><i class="fa fa-trash"></i> {{ __('Delete') }}</a>
                <form id="delete-form-{{ $payment_gateway->id }}" action="{{ route('admin.payment-gateway.destroy', $payment_gateway->id) }}" method="POST" style="display: none;">
                  @csrf
                  @method('DELETE')
                </form>
              </div> -->
            </div>
          </div>
        </div>
        @endforeach
    </div>
</div>


@endsection


