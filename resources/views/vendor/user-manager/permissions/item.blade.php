@extends('layouts.backend')
<!-- START CONTAINER FLUID -->
@section('content')
 
        <div class="container-fluid" >
       	  <ul class="breadcrumb">
                               	  <li>
                                   	  <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i>MasQan</a>
                                        
                                  </li>
                                  <li>
                                   	  <a href="{{ url('tickets') }}">Support Tickets</a>
                                  </li>
                                  <li>
                                  	 <a href="#">Ticket Details</a>
                                  </li>
                              </ul>
          <!-- START ROW -->
          <section id="main" class="clearfix">
                        	
                          <div class="main-content m-t-30 bg-white col-lg-12 col-md-12 col-sm-12" data-aos="fade-in"   data-aos-delay="600" >
<div class="row-fluid">
                                  <div class="col-lg-12 section">                                   
                                      
                                     
                                        

                                      <div class="widget">
                                                                                
                                        

                                          <div class="widget-content row m-t-30">
                                           
                                                     <?php if($ticket){ ?>
                                                            <div class="col-lg-8">
                                                            <div class="panel panel-default hover-stroke">
                                                              <div class="">
                                                                <div class="container-sm-height">
                                                                  <div class="row row-sm-height">
                                                                    <div class="col-sm-9 padding-20  col-sm-height col-top">
                                                                      <p class="font-montserrat bold"><?= $ticket->name; ?></p>
                                                                      <h6 class="font-montserrat no-margin text-uppercase"><?= $ticket->subject; ?></h6>
                                                                      <p class="m-t-10 hint-text text-black"><?= $ticket->message; ?></p>
                                                                    </div>
                                                                    <div class="col-sm-3 col-sm-height col-middle bg-master-lighter">
                                                                      <h4 class="text-center text-primary no-margin">
                                                                               <?= $ticket->status; ?>
                                                                            </h4>
                                                                            <p  class="text-center"><?= $ticket->timestamp; ?></p>
                                                                    </div>
                                                                  </div>
                                                                </div>
                                                              </div>
               											</div>
													    </div>
                                                        <?php if($ticket->status == 'Open' && Session::get('user_account_type') > 5){ ?>
                                                        <div class="col-lg-2">
                                                        <form id="data-form" class="vertical-form pjax-form m-t-20" method="post" action="{{ url('') }}ticket/close">
                                                                    <input type="hidden" name="id" value="<?= $ticket->id; ?>" />
                                                                    <button class="btn btn-success btn-lg" type="submit">Close Ticket</button>
                      
                    										</form>
                                                        </div>
                                                        <?php } ?>
                                                         <?php  } ?>
                                            			<div class="clearfix"></div>
                                                        
                                                        	<div class="panel col-lg-6">
                                                            	<h6 class="font-montserrat text-uppercase">Responses</h6>

                                                           </div>
                                                             
                                                             
		
                                          </div>
                                      </div>
                                  </div>
                              </div>
</div>
</section>

</div>
@endsection
