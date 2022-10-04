<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
          <a href="#">{{ env('APP_NAME') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
          <a href="#">{{ Str::limit(env('APP_NAME'),2) }}</a>
        </div>
        <ul class="sidebar-menu">
          @can('dashboard.index')
          <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
            @if(auth()->user()->role_id == 1)
            <a class="nav-link" href="{{ route('admin.dashboard')}}"><i class="fas fa-tachometer-alt"></i> <span>{{ __('Dashboard') }}</span></a>
            @endif
          </li>
          @endcan
          @can('transaction')
          <!--- Transaction Modules --->
          <li class="menu-header">{{ __('Transactions') }}</li>
          
          <li class="nav-item dropdown {{ Request::is('admin/bank_withdraw*') ? 'show active' : '' }} ">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class=" fas fa-money-check-alt"></i> <span>{{ __('Disbursements') }}</span></a>
            <ul class="dropdown-menu">
             
              <li><a class="nav-link" href="{{ route('admin.disburse.create')}}">{{ __('Disburse Create') }}</a></li>
             
             
              <li><a class="nav-link" href="{{ route('admin.disburse.index')}}">{{ __('Disburse List') }}</a></li>
              
              </a></li>
            </ul>
          </li>
          

          <!--- ALl Transaction Modules --->
          <li class="nav-item dropdown {{ Request::is('admin/all/transaction') ? 'show active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-exchange-alt"></i> <span> {{ __('All Transactions') }}</span></a>
                <ul class="dropdown-menu">
                  <li>
                    <a class="nav-link" href="{{route('transaction.index')}}">{{ __('All Transactions') }}</a>
                    <!-- ">{{ __('Transactions List') }}</a> -->
                  </li>
                  <!-- create a transaction -->
                  <li>
                    <a class="nav-link" href="{{route('transaction.create')}}">{{ __('Transactions Create') }}</a>
                  </li>
                </a>
              </li>
            </ul>
          </li>
          @endcan
          <!-- <li class="nav-item dropdown {{ Request::is('admin/e-currency*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-coins"></i> <span>{{ __('E-currency') }}</span></a>
            <ul class="dropdown-menu">
               @can('withdraw.request.approved')
              <li><a class="nav-link" href="#">{{ __('Approved Withdraw') }}</a></li>
              @endcan
              @can('withdraw.request.rejected')
              <li><a class="nav-link" href="#">{{ __('Rejected Withdraw') }}</a></li>
              @endcan
              @can('withdraw.request.index')
              <li><a class="nav-link" href="#">{{ __('Withdraw Request') }}</a></li>
              @endcan
            </ul>
          </li> -->
          <!--- Loan Management Modules --->
          
          <li class="nav-item dropdown {{ Request::is('admin/loan*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-hand-holding-usd"></i> <span>{{ __('Loan Management') }}</span></a>
            <ul class="dropdown-menu">
              @can('loan.management.create')
              <li><a class="nav-link" href="{{ route('loan.calculator') }}">{{ __('loan Calculator ') }}</a></li>
              @endcan

              @can('loan.management.index') 
              <li><a class="nav-link" href="{{ route('loan.index') }}">{{ __('Loan List') }}</a></li>
              @endcan
              @can('loan.request.list')
              <li><a class="nav-link" href="{{ route('loan.create')}}">{{ __('Loan Request') }} </a></li>
              @endcan
              <!--pending loan request-->
              <!-- 'loan.request.pending -->
              <li><a class="nav-link" href="#">{{ __('Loan Pending') }} </a></li>
              <!-- -->
              @can('loan.approved.index')
              <li><a class="nav-link" href="">{{ __('Loan Request Approved') }}
              </a></li>
              @endcan
              @can('loan.rejected.index')
              <li><a class="nav-link" href="#">{{ __('Loan Request Rejected') }}
              </a></li>
              @endcan
              @can('loan.management.returnlist')
              <li><a class="nav-link" href="#">{{ __('Loan Return List') }}
              </a></li>
              @endcan
              <!--deferred loan-->
              <!-- 'loan.deferred.index -->
              <li><a class="nav-link" href="#">{{ __('Deferred Loan') }}
              </a></li>
              <!--  -->

            </ul>
          </li>
          @can('transaction')
          <!-- <li class="{{ Request::is('admin/all/bills') ? 'active' : '' }}">
            <a href="#" class="nav-link"><i class="fas fa-wallet"></i> <span>{{ __('All Bills') }}</span></a>
          </li> -->
          @endcan
<!-- 
          <li class="menu-header">{{ __('Deposits') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/deposit*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-money-bill-alt"></i> <span>{{ __('Deposits') }}</span></a>
            <ul class="dropdown-menu">
              @can('deposit.index')
              <li><a class="nav-link" href="#">{{ __('All Deposits') }}</a></li>
              @endcan
              @can('deposit.complete')
              <li><a class="nav-link" href="#">{{ __('Complete Deposit') }}</a></li>
              @endcan
            </ul>
          </li>
          <li class="nav-item dropdown {{ Request::is('admin/fixed-deposit*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-money-bill-alt"></i> <span> {{ __('Fixed Deposit') }}</span></a>
            <ul class="dropdown-menu">
              @can('fixeddeposit.index')
              <li><a class="nav-link" href="#">{{ __('All Plans') }}</a></li>
              @endcan
              @can('fixeddeposit.request')
              <li><a class="nav-link" href="#">{{ __('In Queue') }}
              </a></li>
              @endcan
              @can('fixeddeposit.complete.index')
              <li><a class="nav-link" href="#">{{ __('Complete Deposit') }}
              </a></li>
              @endcan
               @can('fixeddeposit.failed.index')
              <li><a class="nav-link" href="#">{{ __('Rejected Deposit') }}
              </a></li>
              @endcan
               @can('fixeddeposit.history.index')
              <li><a class="nav-link" href="#">{{ __('History') }}
              </a></li>
              @endcan
            </ul>
          </li> -->
          <!--- Bank Deposit Modules --->
          <!-- <li class="nav-item dropdown {{ Request::is('admin/bank-deposit*') ? 'show active' : '' }} {{ Request::is('admin/bank_deposit')  ?  'show active' : '' }} {{ Request::is('admin/manual_gateway')  ?  'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-university"></i> <span>{{ __('Bank Deposit') }}</span></a>
            <ul class="dropdown-menu">
              @can('deposit.manual.gateway.index')
              <li><a class="nav-link" href="#">{{ __('Manual Gateway') }}</a></li>
              @endcan
              @can('bank.deposit.approve')
              <li><a class="nav-link" href="#">{{ __('Approve Manual Deposit') }}
              </a></li>
              @endcan

               @can('bank.deposit.index')
              <li><a class="nav-link" href="#">{{ __('All Bank Deposits') }}
              </a></li>
              @endcan
               @can('deposit.manual.gateway.index')
              <li><a class="nav-link" href="#">{{ __('Reject Manual Deposit') }}
              </a></li>
              @endcan
               @can('deposit.manual.gateway.index')
              <li><a class="nav-link" href="#">{{ __('Pending Manual Deposit') }}
              </a></li>
              @endcan
            </ul>
          </li> -->
          <!--- Branch Modules --->
          <li class="menu-header">{{ __('Bank') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/branch*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-code-branch"></i> <span>{{ __('Branch') }}</span></a>
            <ul class="dropdown-menu">
              @can('branch.create')
              <li><a class="nav-link" href="{{ route('admin.branch.create') }}">{{ __('Add New Branch') }}</a></li>
              @endcan
              @can('branch.index')
              <li><a class="nav-link" href="{{ route('admin.branch.index') }}">{{ __('Branches List') }}</a></li>
              @endcan
            </ul>
          </li>
          <!-- @can('otherbank.index')
          <li class="{{ Request::is('admin/others-bank') ? 'show active' : '' }}">
            <a class="nav-link" href="#"><i class="fas fa-university"></i> <span>{{ __('Others Bank') }}</span></a>
          </li>
          @endcan
          @can('currency.index')
          <li class="{{ Request::is('admin/currency') ? 'show active' : '' }}">
            <a class="nav-link" href="#"><i class="fas fa-dollar-sign"></i> <span>{{ __('Currency List') }}</span></a>
          </li>
          @endcan -->
          <!-- @can('country.index')
          <li class="{{ Request::is('admin/country') ? 'show active' : '' }}">
            <a class="nav-link" href="#"><i class="fas fa-globe-americas"></i> <span>{{ __('Country List') }}</span></a>
          </li>
          @endcan -->


          @can('option')

          <li class="menu-header">{{ __('Payment Methods & Settings') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/withdraw*') ? 'show active' : '' }}">
            <!-- fas fa-wallet
            fas fa-money-check-alt -->
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-wallet"></i> <span>{{ __('Payment Gateway') }}</span></a>
            <ul class="dropdown-menu">
              @can('withdraw.create')
              <li><a class="nav-link" href="{{ route('admin.payment-gateway.create') }}">{{ __('Payment Gateway Create') }}</a></li>
              @endcan
              @can('withdraw.index')
              <li><a class="nav-link" href="{{ route('admin.payment-gateway.index') }}">{{ __('Payment Gateway List') }}</a></li>
             
              @endcan
            </ul>
          </li>


          @endcan


          
         
        
          <!-- @can('option')
           <li class="menu-header">{{ __('Options') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/option/ownbank*') ? 'show active' : '' }} {{ Request::is('admin/option/billcharge*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-funnel-dollar"></i> <span>{{ __('Interest Charges') }}</span></a>
            <ul class="dropdown-menu">
              <li><a class="nav-link" href="#">{{ __('Own Bank Charge') }}</a></li>
              <li><a class="nav-link" href="#">{{ __('Bill Charge') }}</a></li>
            </ul>
          </li>
      
          <li class="{{ Request::is('admin/gateway/automatic-gateway/index') ? 'active' : '' }}">
            <a class="nav-link" href="#"><i class="fas fa-wallet"></i> <span>{{ __('Payment Gateway') }}</span></a>
          </li>
    
          @endcan -->
          <!--- User management Modules --->
          <li class="menu-header">{{ __('Customer Management') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/users*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i> <span>{{ __('Customers') }}</span></a>
            <ul class="dropdown-menu">
              @can('user.create')
              <li><a class="nav-link" href="{{ route('customer.create') }}">{{ __('Add Customer') }}</a></li>
              @endcan
              @can('user.index')
              <li><a class="nav-link" href="{{ route('customer.index') }}">{{ __('All Customers') }}</a></li>
              @endcan
              @can('user.verified')
              <li><a class="nav-link" href="#">{{ __('Active Customers') }}</a></li>
              @endcan
              @can('user.banned')
              <li><a class="nav-link" href="#">{{ __('Inactive Customers') }}</a></li>
              @endcan
              <!-- @can('user.unverified')
              <li><a class="nav-link" href="#">{{ __('Email Unverified') }}</a></li>
              <li><a class="nav-link" href="#">{{ __('Mobile Unverified') }}</a></li>
              @endcan -->
              
            </ul>
          </li>
          <!--- Website management Modules --->
          <!-- <li class="menu-header">{{ __('Website Management') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/website*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-globe"></i> <span>{{ __('Website') }}</span></a>
            <ul class="dropdown-menu">
              @can('howitworks.index')
              <li><a class="nav-link" href="#">{{ __('How it works') }}</a></li>
              @endcan
              @can('service.index')
              <li><a class="nav-link" href="#">{{ __('Manage Service') }}</a></li>
              @endcan
              @can('service.index')
              <li><a class="nav-link" href="#">{{ __('Manage Feedback') }}</a></li>
              @endcan
             @can('counter')
              <li><a class="nav-link" href="#">{{ __('Manage Counter') }}</a></li>
              @endcan
             
            </ul>
          </li>
          <li class="nav-item dropdown {{ Request::is('admin/latest_news*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fab fa-blogger-b"></i> <span>{{ __('Latest News') }}</span></a>
            <ul class="dropdown-menu">
              @can('news.create')
              <li><a class="nav-link" href="#">{{ __('Add New') }}</a></li>
              @endcan
              @can('news.index')
              <li><a class="nav-link" href="#">{{ __('All News') }}
              </a></li>
              @endcan
            </ul>
          </li>
          <li class="nav-item dropdown {{ Request::is('admin/pages*') ? 'show active' : '' }}">
           <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-copy"></i> <span>{{ __('Pages') }}</span></a>
           <ul class="dropdown-menu">
             @can('news.create')
             <li><a class="nav-link" href="#">{{ __('Add New Page') }}</a></li>
             @endcan
             @can('page.index')
             <li><a class="nav-link" href="#">{{ __('All Pages') }}</a></li>
             @endcan
           </ul>
         </li>
         <li class="nav-item dropdown {{ Request::is('admin/investor*') ? 'show active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-industry"></i> <span>{{ __('Investors') }}</span></a>
          <ul class="dropdown-menu">
            @can('investors.create')
            <li><a class="nav-link" href="#">{{ __('Add New') }}</a></li>
            @endcan
            @can('investors.index')
            <li><a class="nav-link" href="#">{{ __('All Investors') }}</a></li>
            @endcan
          </ul>
        </li> -->
       <!-- @can('title')
        <li class="{{ Request::is('admin/title*') ? 'show active' : '' }}">
          <a href=" " class="nav-link"><i class="fas fa-align-right"></i> <span>{{ __('Titles And Descriptions') }}</span></a>
        </li>
        @endcan -->
        <!-- @can('support.index')
        <li class="{{ Request::is('admin/support') ? 'active' : '' }}">
          <a href="#" class="nav-link"><i class="fas fa-headset"></i> <span>{{ __('Support') }}</span></a>
        </li>
        @endcan -->
        <!-- @can('theme.settings')
        <li class="{{ Request::is('admin/theme/settings') ? 'active' : '' }}">
          <a href="#" class="nav-link"><i class="fas fa-columns"></i> <span>{{ __('Theme Settings') }}</span></a>
        </li>
        @endcan -->
        <li class="menu-header">{{ __('Administrator') }}</li>

          <li class="nav-item dropdown {{ Request::is('admin/users*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i> <span>{{ __('Users') }}</span></a>
            <ul class="dropdown-menu">
              @can('user.create')
              <li><a class="nav-link" href="{{ route('admin.users.create') }}">{{ __('Add User') }}</a></li>
              @endcan
              @can('user.index')
              <li><a class="nav-link" href="{{ route('admin.users.index') }}">{{ __('All Users') }}</a></li>
              @endcan
              @can('user.verified')
              <li><a class="nav-link" href="#">{{ __('Verified Users') }}</a></li>
              @endcan
              <!-- @can('user.banned')
              <li><a class="nav-link" href="#">{{ __('Banded Users') }}</a></li>
              @endcan -->
              @can('user.unverified')
              <li><a class="nav-link" href="#">{{ __('Email Unverified') }}</a></li>
              <!-- <li><a class="nav-link" href="#">{{ __('Mobile Unverified') }}</a></li> -->
              @endcan
              
            </ul>
          </li>
          <li class="nav-item dropdown {{ Request::is('admin/role') ? 'show active' : ''}} {{ Request::is('admin/admin') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user-shield"></i> <span>{{ __('Roles') }}</span></a>
            <ul class="dropdown-menu">
              @can('role.list')
              <li><a class="nav-link" href="{{ route('admin.role.index') }}">{{ __('Roles') }}</a></li>
              <!-- ">{{ __('Roles') }}</a></li> -->
              @endcan
              <!-- @can('admin.list')
              <li><a class="nav-link" href="#">{{ __('Admins') }}</a></li>
              @endcan -->
            </ul>
          </li>
          <li class="nav-item dropdown {{ Request::is('admin/setting*') ? 'show active' : ''}} {{  Request::is('admin/menu') ? 'show active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cogs"></i> <span>{{ __('Settings') }}</span></a>
          <ul class="dropdown-menu">
            @can('system.settings')
            <li><a class="nav-link" href=" {{route('admin.settings') }}">{{ __('System Settings') }}</a></li>
            @endcan
            <!-- @can('seo.settings')
            <li><a class="nav-link" href="#">{{ __('SEO Settings') }}</a></li>
            @endcan
            @can('menu')
            <li><a class="nav-link" href="#">{{ __('Menu Settings') }}</a></li>
            @endcan -->
          </ul>
        </li>


        <!--reports-->
        <li class="menu-header">{{ __('Reports') }}</li>
        <li class="nav-item dropdown {{ Request::is('admin/reports*') ? 'show active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-chart-line"></i> <span>{{ __('Reports') }}</span></a>
          <ul class="dropdown-menu">
            @can('report.customer')
            <li><a class="nav-link" href="{{route('customers.report')}}">{{ __('Customer Report') }}</a></li>
            @endcan
            @can('report.transaction')
            <li><a class="nav-link" href="{{ route('transaction.report') }}">{{ __('Transaction Report') }}</a></li>
            @endcan
            <!---Disburse Report-->
            @can('report.disburse')
            <li><a class="nav-link" href="{{ route('disburse.report') }}">{{ __('Disburse Report') }}</a></li>
            @endcan

            <!-- Performance of RO -->
            @can('report.performance')
            <li><a class="nav-link" href="{{ route('performance.report') }}">{{ __('Performance Report') }}</a></li>
            @endcan
          </ul>
        </li> 
   

    </ul>
  </aside>
</div>