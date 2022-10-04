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
            <a class="nav-link" href=""><i class="fas fa-tachometer-alt"></i> <span>{{ __('Dashboard') }}</span></a>
          </li>
          @endcan
          @can('transaction')
          <!--- Transaction Modules --->
          <li class="menu-header">{{ __('Withdraw & Transactions') }}</li>
          
          <li class="nav-item dropdown {{ Request::is('admin/bank_withdraw*') ? 'show active' : '' }} ">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-university"></i> <span>{{ __('Bank Withdraw') }}</span></a>
            <ul class="dropdown-menu">
             
              <li><a class="nav-link" href="{{ route('admin.bank_transaction_approved') }}">{{ __('Approved') }}</a></li>
             
             
              <li><a class="nav-link" href="{{ route('admin.bank_transaction_rejected') }}">{{ __('Rejected') }}</a></li>
              
              <li><a class="nav-link" href="{{ route('admin.bank_transaction_request') }}">{{ __('Requests') }}
              </a></li>
            </ul>
          </li>
          
          <li class="nav-item dropdown {{ Request::is('admin/ownbank') ? 'show active' : '' }} ">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-university"></i> <span>{{ __('Own Bank') }}</span></a>
            <ul class="dropdown-menu">
              <li><a class="nav-link" href="{{ route('admin.ownbank.transactions') }}">{{ __('Own Bank Transaction') }}</a></li>
            </ul>
          </li>

          <!--- ALl Transaction Modules --->
          <li class="nav-item dropdown {{ Request::is('admin/all/transaction') ? 'show active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-exchange-alt"></i> <span> {{ __('All Transactions') }}</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="{{ route('admin.all.transaction') }}">{{ __('Transactions List') }}</a></li>
                </a>
              </li>
            </ul>
          </li>
          @endcan
          <li class="nav-item dropdown {{ Request::is('admin/e-currency*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-coins"></i> <span>{{ __('E-currency') }}</span></a>
            <ul class="dropdown-menu">
               @can('withdraw.request.approved')
              <li><a class="nav-link" href="{{ route('admin.approved_withdraw') }}">{{ __('Approved Withdraw') }}</a></li>
              @endcan
              @can('withdraw.request.rejected')
              <li><a class="nav-link" href="{{ route('admin.rejected_withdraw') }}">{{ __('Rejected Withdraw') }}</a></li>
              @endcan
              @can('withdraw.request.index')
              <li><a class="nav-link" href="{{ route('admin.withdraw_request') }}">{{ __('Withdraw Request') }}</a></li>
              @endcan
            </ul>
          </li>
          <!--- Loan Management Modules --->
          
          <li class="nav-item dropdown {{ Request::is('admin/loan*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-hand-holding-usd"></i> <span>{{ __('Loan Management') }}</span></a>
            <ul class="dropdown-menu">
              @can('loan.management.create')
              <li><a class="nav-link" href="{{ route('admin.loan.create') }}">{{ __('Add New Package') }}</a></li>
              @endcan

              @can('loan.management.index')
              <li><a class="nav-link" href="{{ route('admin.loan.index') }}">{{ __('Loan Package List') }}</a></li>
              @endcan
              @can('loan.request.list')
              <li><a class="nav-link" href="{{ route('admin.loan.request') }}">{{ __('Loan Request') }}
              </a></li>
              @endcan
              @can('loan.approved.index')
              <li><a class="nav-link" href="{{ route('admin.loan.approved.index') }}">{{ __('Loan Request Approved') }}
              </a></li>
              @endcan
              @can('loan.management.returnlist')
              <li><a class="nav-link" href="{{ route('admin.loan.return.index') }}">{{ __('Loan Return List') }}
              </a></li>
              @endcan
               @can('loan.rejected.index')
              <li><a class="nav-link" href="{{ route('admin.loan.rejected.index') }}">{{ __('Loan Request Rejected') }}
              </a></li>
              @endcan
            </ul>
          </li>
          @can('transaction')
          <li class="{{ Request::is('admin/all/bills') ? 'active' : '' }}">
            <a href="{{ route('admin.bills') }}" class="nav-link"><i class="fas fa-wallet"></i> <span>{{ __('All Bills') }}</span></a>
          </li>
          @endcan

          <li class="menu-header">{{ __('Deposits') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/deposit*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-money-bill-alt"></i> <span>{{ __('Deposits') }}</span></a>
            <ul class="dropdown-menu">
              @can('deposit.index')
              <li><a class="nav-link" href="{{ route('admin.deposit.index') }}">{{ __('All Deposits') }}</a></li>
              @endcan
              @can('deposit.complete')
              <li><a class="nav-link" href="{{ route('admin.deposit.complete') }}">{{ __('Complete Deposit') }}</a></li>
              @endcan
            </ul>
          </li>
          <li class="nav-item dropdown {{ Request::is('admin/fixed-deposit*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-money-bill-alt"></i> <span> {{ __('Fixed Deposit') }}</span></a>
            <ul class="dropdown-menu">
              @can('fixeddeposit.index')
              <li><a class="nav-link" href="{{ route('admin.fixed-deposit.index') }}">{{ __('All Plans') }}</a></li>
              @endcan
              @can('fixeddeposit.request')
              <li><a class="nav-link" href="{{ route('admin.fixed.deposit.request') }}">{{ __('In Queue') }}
              </a></li>
              @endcan
              @can('fixeddeposit.complete.index')
              <li><a class="nav-link" href="{{ route('admin.fixed.deposit.complete') }}">{{ __('Complete Deposit') }}
              </a></li>
              @endcan
               @can('fixeddeposit.failed.index')
              <li><a class="nav-link" href="{{ route('admin.fixed.deposit.failed') }}">{{ __('Rejected Deposit') }}
              </a></li>
              @endcan
               @can('fixeddeposit.history.index')
              <li><a class="nav-link" href="{{ route('admin.fixed.deposit.history') }}">{{ __('History') }}
              </a></li>
              @endcan
            </ul>
          </li>
          <!--- Bank Deposit Modules --->
          <li class="nav-item dropdown {{ Request::is('admin/bank-deposit*') ? 'show active' : '' }} {{ Request::is('admin/bank_deposit')  ?  'show active' : '' }} {{ Request::is('admin/manual_gateway')  ?  'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-university"></i> <span>{{ __('Bank Deposit') }}</span></a>
            <ul class="dropdown-menu">
              @can('deposit.manual.gateway.index')
              <li><a class="nav-link" href="{{ route('admin.manual_gateway') }}">{{ __('Manual Gateway') }}</a></li>
              @endcan
              @can('bank.deposit.approve')
              <li><a class="nav-link" href="{{ route('admin.approve_manual_deposit') }}">{{ __('Approve Manual Deposit') }}
              </a></li>
              @endcan

               @can('bank.deposit.index')
              <li><a class="nav-link" href="{{ route('admin.bank_deposit.index') }}">{{ __('All Bank Deposits') }}
              </a></li>
              @endcan
               @can('deposit.manual.gateway.index')
              <li><a class="nav-link" href="{{ route('admin.reject_manual_deposit') }}">{{ __('Reject Manual Deposit') }}
              </a></li>
              @endcan
               @can('deposit.manual.gateway.index')
              <li><a class="nav-link" href="{{ route('admin.pending_manual_deposit') }}">{{ __('Pending Manual Deposit') }}
              </a></li>
              @endcan
            </ul>
          </li>
          <!--- Branch Modules --->
          <li class="menu-header">{{ __('Bank') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/branch*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-code-branch"></i> <span>{{ __('Branch') }}</span></a>
            <ul class="dropdown-menu">
              @can('branch.create')
              <li><a class="nav-link" href="{{ route('admin.branch.create') }}">{{ __('Branch Create') }}</a></li>
              @endcan
              @can('branch.index')
              <li><a class="nav-link" href="{{ route('admin.branch.index') }}">{{ __('Manage Branchs') }}</a></li>
              @endcan
            </ul>
          </li>
          @can('otherbank.index')
          <li class="{{ Request::is('admin/others-bank') ? 'show active' : '' }}">
            <a class="nav-link" href="{{ route('admin.others-bank.index') }}"><i class="fas fa-university"></i> <span>{{ __('Others Bank') }}</span></a>
          </li>
          @endcan
          @can('currency.index')
          <li class="{{ Request::is('admin/currency') ? 'show active' : '' }}">
            <a class="nav-link" href="{{ route('admin.currency.index') }}"><i class="fas fa-dollar-sign"></i> <span>{{ __('Currency List') }}</span></a>
          </li>
          @endcan
          @can('country.index')
          <li class="{{ Request::is('admin/country') ? 'show active' : '' }}">
            <a class="nav-link" href="{{ route('admin.country.index') }}"><i class="fas fa-globe-americas"></i> <span>{{ __('Country List') }}</span></a>
          </li>
          @endcan
            <li class="nav-item dropdown {{ Request::is('admin/withdraw*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-money-check-alt"></i> <span>{{ __('Withdraw Method') }}</span></a>
            <ul class="dropdown-menu">
              @can('withdraw.create')
              <li><a class="nav-link" href="{{ route('admin.withdraw.create') }}">{{ __('Withdraw Method Create') }}</a></li>
              @endcan
              @can('withdraw.index')
              <li><a class="nav-link" href="{{ route('admin.withdraw.index') }}">{{ __('Withdraw Method List') }}</a></li>
              @endcan
            </ul>
          </li>
         
        
          @can('option')
           <li class="menu-header">{{ __('Options') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/option/ownbank*') ? 'show active' : '' }} {{ Request::is('admin/option/billcharge*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-funnel-dollar"></i> <span>{{ __('Charges') }}</span></a>
            <ul class="dropdown-menu">
              <li><a class="nav-link" href="{{ route('admin.option.ownbank.edit') }}">{{ __('Own Bank Charge') }}</a></li>
              <li><a class="nav-link" href="{{ route('admin.option.billcharge.edit') }}">{{ __('Bill Charge') }}</a></li>
            </ul>
          </li>
          <li class="{{ Request::is('admin/option/verification-settings') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.option.verification.edit') }}">
              <i class="fas fa-certificate"></i>
              <span>{{ __('Verification Settings') }}</span>
            </a>
          </li>
          <li class="{{ Request::is('admin/gateway/automatic-gateway/index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.deposit.automatic.gateway') }}"><i class="fas fa-wallet"></i> <span>{{ __('Payment Gateway') }}</span></a>
          </li>
          <li class="{{ Request::is('admin/option/twilio') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.option.twilio.edit') }}"><i class="fas fa-sms"></i> <span>{{ __('SMS Settings') }}</span></a>
          </li>
          @endcan
          <!--- User management Modules --->
          <li class="menu-header">{{ __('Customer Management') }}</li>
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
              <li><a class="nav-link" href="{{ route('admin.verified_users') }}">{{ __('Verified Users') }}</a></li>
              @endcan
              @can('user.banned')
              <li><a class="nav-link" href="{{ route('admin.banded_users') }}">{{ __('Banded Users') }}</a></li>
              @endcan
              @can('user.unverified')
              <li><a class="nav-link" href="{{ route('admin.email_unverified') }}">{{ __('Email Unverified') }}</a></li>
              <li><a class="nav-link" href="{{ route('admin.mobile_unverified') }}">{{ __('Mobile Unverified') }}</a></li>
              @endcan
              
            </ul>
          </li>
          <!--- Website management Modules --->
          <li class="menu-header">{{ __('Website Management') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/website*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-globe"></i> <span>{{ __('Website') }}</span></a>
            <ul class="dropdown-menu">
              @can('howitworks.index')
              <li><a class="nav-link" href="{{ route('admin.howitworks.index') }}">{{ __('How it works') }}</a></li>
              @endcan
              @can('service.index')
              <li><a class="nav-link" href="{{ route('admin.services.index') }}">{{ __('Manage Service') }}</a></li>
              @endcan
              @can('service.index')
              <li><a class="nav-link" href="{{ route('admin.feedbacks.index') }}">{{ __('Manage Feedback') }}</a></li>
              @endcan
             @can('counter')
              <li><a class="nav-link" href="{{ route('admin.counter.index') }}">{{ __('Manage Counter') }}</a></li>
              @endcan
             
            </ul>
          </li>
          <li class="nav-item dropdown {{ Request::is('admin/latest_news*') ? 'show active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fab fa-blogger-b"></i> <span>{{ __('Latest News') }}</span></a>
            <ul class="dropdown-menu">
              @can('news.create')
              <li><a class="nav-link" href="{{ route('admin.latest_news.create') }}">{{ __('Add New') }}</a></li>
              @endcan
              @can('news.index')
              <li><a class="nav-link" href="{{ route('admin.latest_news.index') }}">{{ __('All News') }}
              </a></li>
              @endcan
            </ul>
          </li>
          <li class="nav-item dropdown {{ Request::is('admin/pages*') ? 'show active' : '' }}">
           <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-copy"></i> <span>{{ __('Pages') }}</span></a>
           <ul class="dropdown-menu">
             @can('news.create')
             <li><a class="nav-link" href="{{ route('admin.pages.create') }}">{{ __('Add New Page') }}</a></li>
             @endcan
             @can('page.index')
             <li><a class="nav-link" href="{{ route('admin.pages.index') }}">{{ __('All Pages') }}</a></li>
             @endcan
           </ul>
         </li>
         <li class="nav-item dropdown {{ Request::is('admin/investor*') ? 'show active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-industry"></i> <span>{{ __('Investors') }}</span></a>
          <ul class="dropdown-menu">
            @can('investors.create')
            <li><a class="nav-link" href="{{ route('admin.investor.create') }}">{{ __('Add New') }}</a></li>
            @endcan
            @can('investors.index')
            <li><a class="nav-link" href="{{ route('admin.investor.index') }}">{{ __('All Investors') }}</a></li>
            @endcan
          </ul>
        </li>
       @can('title')
        <li class="{{ Request::is('admin/title*') ? 'show active' : '' }}">
          <a href="{{ route('admin.titles') }}" class="nav-link"><i class="fas fa-align-right"></i> <span>{{ __('Titles And Descriptions') }}</span></a>
        </li>
        @endcan
        @can('support.index')
        <li class="{{ Request::is('admin/support') ? 'active' : '' }}">
          <a href="{{ route('admin.support.index') }}" class="nav-link"><i class="fas fa-headset"></i> <span>{{ __('Support') }}</span></a>
        </li>
        @endcan
        @can('theme.settings')
        <li class="{{ Request::is('admin/theme/settings') ? 'active' : '' }}">
          <a href="{{ route('admin.theme.settings') }}" class="nav-link"><i class="fas fa-columns"></i> <span>{{ __('Theme Settings') }}</span></a>
        </li>
        @endcan
        <li class="menu-header">{{ __('Administrator') }}</li>
          <li class="nav-item dropdown {{ Request::is('admin/role') ? 'show active' : ''}} {{ Request::is('admin/admin') ? 'show active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user-shield"></i> <span>{{ __('Admins & Roles') }}</span></a>
          <ul class="dropdown-menu">
            @can('role.list')
            <li><a class="nav-link" href="{{ route('admin.role.index') }}">{{ __('Roles') }}</a></li>
            @endcan
            @can('admin.list')
            <li><a class="nav-link" href="{{ route('admin.admin.index') }}">{{ __('Admins') }}</a></li>
            @endcan
          </ul>
          <li class="nav-item dropdown {{ Request::is('admin/setting*') ? 'show active' : ''}} {{  Request::is('admin/menu') ? 'show active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cogs"></i> <span>{{ __('Settings') }}</span></a>
          <ul class="dropdown-menu">
            @can('system.settings')
            <li><a class="nav-link" href="{{ url('/admin/setting/env') }}">{{ __('System Environment') }}</a></li>
            @endcan
            @can('seo.settings')
            <li><a class="nav-link" href="{{ url('/admin/setting/seo') }}">{{ __('SEO Settings') }}</a></li>
            @endcan
            @can('menu')
            <li><a class="nav-link" href="{{ route('admin.menu.index') }}">{{ __('Menu Settings') }}</a></li>
            @endcan
          </ul>
        </li>
        @can('language.index')
      <li class="{{ Request::is('admin/language') ? 'active' : '' }}">
        <a href="{{ route('admin.language.index') }}" class="nav-link"><i class="fas fa-language"></i> <span>{{ __('Languages') }}</span></a>
      </li>
      @endcan
       @can('phone.settings')
      <li class="{{ Request::is('admin/phone-settings*') ? 'active' : '' }}">
        <a href="{{ route('admin.phone-settings.index') }}" class="nav-link"><i class="fas fa-columns"></i> <span>{{ __('Phone Settings') }}</span></a>
      </li>
      @endcan
    </ul>
  </aside>
</div>