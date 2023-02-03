  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{Auth::user()->profile_jpg()}}" class="img-circle">
        </div>
        <div class="pull-left info">
          <p>{{Auth::user()->fullname}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> {{App\Language::trans('Online')}}</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">{{App\Language::trans('MAIN NAVIGATION')}}</li>
        <li><a href="{{action('DashboardsController@getDashboard')}}"><i class="fa fa-users fa-fw"></i> <span>{{App\Language::trans('Dashboard')}}</span></a></li>
        <li><a href="{{action('DashboardsController@getUserProfile')}}"><i class="fa fa-dashboard fa-fw"></i> <span>{{App\Language::trans('Profile')}}</span></a></li>
        
        @if(App\LeafAPI::get_module_status([App\LeafAPI::label_power_meter]))
          <li class="treeview">
            <a href="#">
              <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Power Meter')}}</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{action('UMeterRegistersController@getStatus')}}"><i class="fa fa-tablet fa-fw"></i> <span>{{App\Language::trans('Current Power')}}</span></a></li>
              <li><a href="{{action('UMeterRegistersController@getStatusDetail')}}"><i class="fa fa-tablet fa-fw"></i> <span>{{App\Language::trans('Current Power (Summary)')}}</span></a></li>
              <li><a href="{{action('ReportsController@getRoomUsages')}}"><i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Room Usages')}}</span></a></li>
              <li><a href="{{action('ReportsController@getMonthlyUsages')}}"><i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Monthly Usages')}}</span></a></li>
            </ul>
          </li>  

          <li class="treeview">
            <a href="#">
              <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Billing')}}</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{action('UMeterInvoiceController@getIndex')}}"><i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Invoices')}}</span></a></li>
              <li><a href="{{action('ReportsController@getInvoices')}}"><i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Invoices Reports')}}</span></a></li>
              <li><a href="{{action('ReportsController@getMonthlySales')}}"><i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Monthly Sales')}}</span></a></li>
              <li><a href="{{action('UMeterSubsidiariesController@getIndex')}}"><i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Subsidy')}}</span></a></li>
           </ul>
          </li>          
        @endif
      
       
      
        @if(App\LeafAPI::get_module_status([App\LeafAPI::label_accounting]))
          <li class="treeview">
            <a href="#">
              <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Booking')}}</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{action('IframesController@getBookingFacility')}}"><i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Facilitiy')}}</span></a></li>
            </ul>
          </li>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Account Receivable')}}</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{action('ARInvoicesController@getIndex')}}"><i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('AR Invoices')}}</span></a></li>
              <li><a href="{{action('ARPaymentReceivedsController@getIndex')}}"><i class="fa fa-dashboard fa-fw"></i> <span>{{App\Language::trans('Payment Received')}}</span></a></li>
              <li><a href="{{action('ARRefundsController@getIndex')}}"><i class="fa fa-dashboard fa-fw"></i> <span>{{App\Language::trans('Refund')}}</span></a></li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Reports')}}</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="{{action('ReportsController@getSalesReport')}}"><i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Sales Report')}}</span></a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Maintenances')}}</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="{{action('CustomerGroupsController@getIndex')}}"><i class="fa fa-users fa-fw"></i> <span>{{App\Language::trans('Customer Groups')}}</span></a></li>
                  <li><a href="{{action('CustomersController@getIndex')}}"><i class="fa fa-user fa-fw"></i> <span>{{App\Language::trans('Customers')}}</span></a></li>
                  <li><a href="{{action('LeafPaymentItemToNCLAccountMappersController@getIndex')}}"><i class="fa fa-dashboard fa-fw"></i> <span>{{App\Language::trans('Account Mapping')}}</span></a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li><a href="{{action('TicketsController@getIndex')}}"><i class="fa fa-tags fa-fw"></i> <span>{{App\Language::trans('Ticket Complaint')}}</span></a></li>
        @endif
        <li class="treeview">
          <a href="#">
            <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Setup Setting')}}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(App\LeafAPI::get_module_status([App\LeafAPI::label_power_meter]))
              <li><a href="{{action('UMeterRegistersController@getIndex')}}"><i class="fa fa-tachometer fa-fw"></i> <span>{{App\Language::trans('Devices Setting')}}</span></a></li>
            @endif
            @if(App\LeafAPI::get_module_status([App\LeafAPI::label_accounting]))
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Product Settings')}}</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="{{action('UomsController@getIndex')}}"><i class="fa fa-cube fa-fw"></i> <span>{{App\Language::trans('Unit Of Measurements')}}</span></a></li>
                  <li><a href="{{action('LocationsController@getIndex')}}"><i class="fa fa-thumb-tack fa-fw"></i> <span>{{App\Language::trans('Locations')}}</span></a></li>
                  <li><a href="{{action('ProductCategoriesController@getIndex')}}"><i class="fa fa-cubes fa-fw"></i> <span>{{App\Language::trans('Product Categories')}}</span></a></li>
                  <li><a href="{{action('ProductsController@getIndex')}}"><i class="fa fa-cube fa-fw"></i> <span>{{App\Language::trans('Products')}}</span></a></li>
                </ul>
              </li>
            @endif
            <li class="treeview">
              <a href="#">
                <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Billing Settings')}}</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                @if(App\LeafAPI::get_module_status([App\LeafAPI::label_power_meter]))
                  <li><a href="{{action('UtilityChargesController@getIndex')}}"><i class="fa fa-list-ul fa-fw"></i> <span>{{App\Language::trans('Price Lists')}}</span></a></li>
                  <li><a href="{{action('UMeterClassController@getIndex')}}"><i class="fa fa-tachometer fa-fw"></i> <span>{{App\Language::trans('Account Classes')}}</span></a></li>
                @endif
                @if(App\LeafAPI::get_module_status([App\LeafAPI::label_accounting]))
                  <li><a href="{{action('PaymentTermsController@getIndex')}}"><i class="fa fa-credit-card-alt fa-fw"></i> <span>{{App\Language::trans('Payment Terms')}}</span></a></li>
                  <li><a href="{{action('CurrenciesController@getIndex')}}"><i class="fa fa-money fa-fw"></i> <span>{{App\Language::trans('Currencies')}}</span></a></li>
                  <li><a href="{{action('TaxesController@getIndex')}}"><i class="fa fa-money fa-fw"></i> <span>{{App\Language::trans('Taxes')}}</span></a></li>
                @endif
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-map fa-fw"></i> <span>{{App\Language::trans('Location Settings')}}</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{action('CountriesController@getIndex')}}"><i class="fa fa-globe fa-fw"></i> <span>{{App\Language::trans('Countries')}}</span></a></li>
                <li><a href="{{action('StatesController@getIndex')}}"><i class="fa fa-location-arrow fa-fw"></i> <span>{{App\Language::trans('States')}}</span></a></li>
                <li><a href="{{action('CitiesController@getIndex')}}"><i class="fa fa-map-marker fa-fw"></i> <span>{{App\Language::trans('Cities')}}</span></a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-users fa-fw"></i> <span>{{App\Language::trans('Users Settings')}}</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{action('UsersController@getIndex')}}"><i class="fa fa-user fa-fw"></i> <span>{{App\Language::trans('Users')}}</span></a></li>
                <li><a href="{{action('UserGroupsController@getIndex')}}"><i class="fa fa-users fa-fw"></i> <span>{{App\Language::trans('Groups')}}</span></a></li>
              </ul>
            </li>
            <li><a href="{{action('MobileSettingsController@getIndex')}}"><i class="fa fa-book fa-fw"></i> <span>{{App\Language::trans('Mobile Settings')}}</span></a></li> 
            <li><a href="{{action('SettingsController@getIndex')}}"><i class="fa fa-cogs fa-fw"></i> <span>{{App\Language::trans('General Setting')}}</span></a></li>
          </ul>
        </li>  

         <li class="treeview">
          <a href="#">
            <i class="fa fa-sort-alpha-asc fa-fw"></i> <span>{{App\Language::trans('Opencart Settings')}}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{action('OpencartLanguageTranslatorsController@getIndex')}}"><i class="fa fa-paste fa-fw"></i> <span>{{App\Language::trans('Backend')}}</span></a></li>
            <li><a href="{{action('OpencartLanguageTranslatorsController@getIndex')}}"><i class="fa fa-paste fa-fw"></i> <span>{{App\Language::trans('Fontend')}}</span></a></li>
            <li><a href="{{action('OpencartLanguageTranslatorsController@getTranslationStatus')}}"><i class="fa fa-paste fa-fw"></i> <span>{{App\Language::trans('Log')}}</span></a></li>
          </ul>
        </li>
 
        {{-- <li><a href="https://adminlte.io/docs"><i class="fa fa-book fa-fw"></i> <span>{{App\Language::trans('Documentation')}}</span></a></li> --}}
        <li class="header">{{App\Language::trans('LABELS')}}</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>{{App\Language::trans('Important')}}</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>{{App\Language::trans('Warning')}}</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>{{App\Language::trans('Information')}}</span></a></li>
      </ul>
     
    </section>
    <!-- /.sidebar -->
  </aside>
