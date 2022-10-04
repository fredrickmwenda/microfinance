@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Manage Fixed Deposit Plans'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
               
                </div>
                <div class="col-lg-6">
                    <div class="add-new-btn">
                        <a href="{{ route('admin.fixed-deposit.create') }}" class="btn btn-primary float-right">Add New FDR plan</a>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
              <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                     
                      <th>{{ __('Title') }}</th>
                      <th>{{ __('Minimum Amount') }}</th>
                      <th>{{ __('Maximum Amount') }}</th>
                      <th>{{ __('Duration') }}</th>
                      <th>{{ __('Created At') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($fdr_plans as $fdr_plan)
                    <tr>
                     
                      <td>{{ $fdr_plan->title }}</td>
                      <td class="align-middle">
                        {{ $fdr_plan->min_amount }}
                      </td>
                      <td class="align-middle">
                        {{ $fdr_plan->max_amount }}
                      </td>
                      <td>
                        {{ $fdr_plan->duration }}
                      </td>
                      <td>
                        {{ $fdr_plan->created_at }}
                      </td>
                      <td>
                        {{ $fdr_plan->status == 0 ? 'Inactive' : 'Active' }}
                      </td>
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.fixed-deposit.show', $fdr_plan->id) }}"><i class="fa fa-eye"></i>{{ __('View') }}</a>
                            <a class="dropdown-item has-icon" href="{{ route('admin.fixed-deposit.edit', $fdr_plan->id) }}"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>

                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $fdr_plan->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>

                            <form class="d-none" id="delete_form_{{ $fdr_plan->id }}" action="{{ route('admin.fixed-deposit.destroy', $fdr_plan->id) }}" method="POST">
                              @csrf
                              @method('DELETE')
                            </form>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $fdr_plans->links('vendor.pagination.bootstrap-4') }}
               
              </div>
          </div>
      </div>
    </div>
</div>
@endsection

@push('js')
  <script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
@endpush


