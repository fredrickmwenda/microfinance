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
            <a class="nav-link" href="{{ route('admin.dashboard')}}"><i class="fas fa-tachometer-alt"></i> <span>{{ __('Dashboard') }}</span></a>         
          </li>
          @endcan
          @can('agent.dashboard')      
          <li class="{{ Request::is('agent/dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('agent.dashboard')}}"><i class="fas fa-tachometer-alt"></i> <span>{{ __('Dashboard') }}</span></a>
          </li>
          @endcan
         
          @can('transaction.list')
          <!--- Transaction Modules --->
          <li class="menu-header">{{ __('Transactions') }}</li>
          
          @can('disburse.list')
          <!--- Disburse Modules --->
          <li class="nav-item dropdown {{ Request::is('admin/bank_withdraw*') ? 'show active' : '' }} ">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class=" fas fa-money-check-alt"></i> <span>{{ __('Disbursements') }}</span></a>
            <ul class="dropdown-menu">
              @can('disburse.index')
              <li><a class="nav-link" href="{{ route('admin.disburse.index')}}">{{ __('Disburse List') }}</a></li>
              @endcan
              @can('disburse.create')        
              <li><a class="nav-link" href="{{ route('admin.disburse.create')}}">{{ __('Disburse Create') }}</a></li>
              @endcan
            </ul>
          </li>
          @endcan
          
          @can('transaction.show')
          <!--- ALl Transaction Modules --->
          <li class="nav-item dropdown {{ Request::is('admin/all/transaction') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-exchange-alt"></i> <span> {{ __('All Transactions') }}</span></a>
            <ul class="dropdown-menu">
              @can('transaction.index')
              <li>
                <a class="nav-link" href="{{route('transaction.index')}}">{{ __('All Transactions') }}</a>
              </li>
              @endcan
              @can('transaction.create')
              <li>
                <a class="nav-link" href="{{route('transaction.create')}}">{{ __('Transactions Create') }}</a>
              </li>
              @endcan
            </ul>
          </li>   
          @endcan
          

          @can('loan.management.list')
          <li class="nav-item dropdown {{ Request::is('admin/loan*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-hand-holding-usd"></i> <span>{{ __('Loan Management') }}</span></a>
            <ul class="dropdown-menu">


              @can('loan.management.index') 
              <li><a class="nav-link" href="{{ route('loan.index') }}">{{ __('Loan List') }}</a></li>
              @endcan
              @can('loan.request.index')
              <li><a class="nav-link" href="{{ route('loan.create')}}">{{ __('Loan Request') }} </a></li>
              @endcan

              @can('loan.pending.index')
              <li><a class="nav-link" href="{{ route('loans.pending')}}">{{ __('Loans Pending') }} </a></li>
              @endcan

              @can('loan.approved.index')
              <li><a class="nav-link" href="{{ route('loans.approved')}}">{{ __('Loans Approved') }} </a></li>           
              @endcan

              @can('loan.rejected.index')
              <li><a class="nav-link" href= "{{ route('loans.rejected')}}">{{ __('Loans Rejected') }} </a></li>
              @endcan

              @can('loan.overdue.index')            
              <li><a class="nav-link" href="{{ route('loans.overdue')}}">{{ __('Loans Overdue') }} </a></li>
              @endcan
             
              @can('loan.active.index')             
              <li><a class="nav-link" href="{{ route('loans.active')}}">{{ __('Loans Active') }} </a></li>
              @endcan
             
              @can('loan.closed.index')             
              <li><a class="nav-link" href="{{ route('loans.closed')}}">{{ __('Loans Closed') }} </a></li>
              @endcan
            </ul>
          </li>
          @endcan
          @endcan

          <!--- Customer Modules --->
          @can('customer.list')
          <li class="menu-header">{{ __('Customer Management') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/users*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i> <span>{{ __('Customers') }}</span></a>
            <ul class="dropdown-menu">
              @can('customer.index')
              <li><a class="nav-link" href="{{ route('customer.index') }}">{{ __('All Customers') }}</a></li>
              @endcan
              @can('customer.create')
              <li><a class="nav-link" href="{{ route('customer.create') }}">{{ __('Add Customer') }}</a></li>
              @endcan

              @can('customer.active')
              <li><a class="nav-link" href="{{ route('customers.active') }}">{{ __('Active Customers') }}</a></li>
              @endcan
           
              @can('customer.inactive ')             
              <li><a class="nav-link" href="{{ route('customers.inactive') }}">{{ __('Inactive Customers') }}</a></li>
              @endcan
   
            </ul>
          </li>
          @endcan

          <!--- Branch Modules --->
          @can('branch.list')
          <li class="menu-header">{{ __('Branches') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/branch*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-code-branch"></i> <span>{{ __('Branch') }}</span></a>
            <ul class="dropdown-menu">
              @can('branch.index')
                <li><a class="nav-link" href="{{ route('admin.branch.index') }}">{{ __('Branches List') }}</a></li>
                @endcan
              @can('branch.create')
              <li><a class="nav-link" href="{{ route('admin.branch.create') }}">{{ __('Add New Branch') }}</a></li>
              @endcan

            </ul>
          </li>
          @endcan


 


          
          <!--- Payment Gateway Modules --->
          @can('payment.gateway.list')
          <li class="menu-header">{{ __('Payment Methods & Settings') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/withdraw*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-wallet"></i> <span>{{ __('Payment Gateway') }}</span></a>
            <ul class="dropdown-menu">
              @can('payment.gateway.index')
              <li><a class="nav-link" href="{{ route('admin.payment-gateway.index') }}">{{ __('Payment Gateway List') }}</a></li>          
              @endcan
              @can('payment.gateway.create')
              <li><a class="nav-link" href="{{ route('admin.payment-gateway.create') }}">{{ __('Payment Gateway Create') }}</a></li>
              @endcan
            </ul>
          </li>
          @endcan


          <!--reports-->
          @can('report.list')
          <li class="menu-header">{{ __('Reports') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/reports*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-chart-line"></i> <span>{{ __('Reports') }}</span></a>
            <ul class="dropdown-menu">
              @can('report.customer')
              <li><a class="nav-link" href="{{route('customers.report')}}">{{ __('Customer Report') }}</a></li>
              @endcan
              @can('report.transaction')
              <li><a class="nav-link" href="{{ route('transactios.report') }}">{{ __('Transaction Report') }}</a></li>
              @endcan
              <!---Disburse Report-->
              @can('report.disburse')
              <li><a class="nav-link" href="{{ route('disburse.report') }}">{{ __('Disburse Report') }}</a></li>
              @endcan
    

              @can('report.performance')
              <li><a class="nav-link" href="{{ route('performance.report') }}">{{ __('Performance Report') }}</a></li>
              @endcan
            </ul>
          </li> 
          @endcan
         
        
          <!--- Admin Modules --->
          @can('administrator.module')
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
              @can('user.unverified')
              <li><a class="nav-link" href="#">{{ __('Email Unverified') }}</a></li>
              @endcan            
            </ul>
          </li>
          @can('role.list')
          <li class="nav-item dropdown {{ Request::is('admin/role') ? 'show active' : ''}} {{ Request::is('admin/admin') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user-shield"></i> <span>{{ __('Roles') }}</span></a>
            <ul class="dropdown-menu">
                         
              <li><a class="nav-link" href="{{ route('admin.role.index') }}">{{ __('Roles') }}</a></li>
            </ul>
          </li>
          @endcan

          @can('system.settings')
          <li class="nav-item dropdown {{ Request::is('admin/setting*') ? 'show active' : ''}} {{  Request::is('admin/menu') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cogs"></i> <span>{{ __('Settings') }}</span></a>
            <ul class="dropdown-menu">              
              <li><a class="nav-link" href=" {{route('admin.settings') }}">{{ __('System Settings') }}</a></li>             
            </ul>
          </li>
          @endcan
          @endcan



   

    </ul>
  </aside>
</div>