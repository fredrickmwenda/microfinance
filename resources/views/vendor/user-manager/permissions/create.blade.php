@extends('layouts.backend')
<!-- START CONTAINER FLUID -->
@section('content')
        <div class="container-fluid">
       	  <ul class="breadcrumb">
              <li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i>MasQan</a></li>
              <li><a href="{{ route('tickets.index') }}"><i class="fa fa-home"></i>Support Tickets</a> </li>
              <li><a href="#">Ticket Editor</a></li>
          </ul>
            <div class="container-fluid container-fixed-lg bg-white">
                <div class="row">
                    <div class="col-sm-3">
                        <!-- START PANEL -->
                        <div class="panel panel-transparent">
                            <div class="panel-body">
                                <h3>Got An Issue You Need Addressed?</h3>
                                <p>Create a ticket below and our team will be happy to assist you </p>

                             </div>
                        </div>
                         <!-- END PANEL -->
                    </div>
                              <div class="col-sm-7">
                                <!-- START PANEL -->
                                <div class="panel panel-transparent">
                                  <div class="panel-body">

                                      {!! Form::open(['route' => 'tickets.store']) !!}

                                      @include('tickets.fields')

                                      {!! Form::close() !!}
                                  </div>
                                </div>
                                <!-- END PANEL -->
                              </div>
                            </div>
          </div>
                          

</div>
@endsection

