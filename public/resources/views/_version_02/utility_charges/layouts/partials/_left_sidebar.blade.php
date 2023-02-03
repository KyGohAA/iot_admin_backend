  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{Auth::user()->profile_jpg()}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{Auth::user()->fullname}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> {{App\Language::trans('Online')}}</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">{{App\Language::trans('MAIN NAVIGATION')}}</li>
        <li><a href="{{action('DashboardsController@getUtilityChargeIndex')}}"><i class="fa fa-dashboard fa-fw"></i> <span>{{App\Language::trans('Dashboard')}}</span></a></li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Power Meter')}}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{action('UMeterRegistersController@getStatus')}}"><i class="fa fa-tablet fa-fw"></i> <span>{{App\Language::trans('Current Power')}}</span></a></li>
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
          </ul>
        </li>    
		
		<li class="treeview">
          <a href="#">
            <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Payment')}}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{action('ARPaymentReceivedsController@getIndex')}}"><i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Payment')}}</span></a></li>
            <li><a href="{{action('ARRefund@getIndex')}}"><i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Refund')}}</span></a></li>
          </ul>
        </li>        
		
        <li class="treeview">
          <a href="#">
            <i class="fa fa-file-o fa-fw"></i> <span>{{App\Language::trans('Setup Setting')}}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{action('UMeterRegistersController@getIndex')}}"><i class="fa fa-tachometer fa-fw"></i> <span>{{App\Language::trans('Devices Setting')}}</span></a></li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-map fa-fw"></i> <span>{{App\Language::trans('Billing Setup')}}</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{action('UtilityChargesController@getIndex')}}"><i class="fa fa-list-ul fa-fw"></i> <span>{{App\Language::trans('Price Lists')}}</span></a></li>
                <li><a href="{{action('UMeterClassController@getIndex')}}"><i class="fa fa-tachometer fa-fw"></i> <span>{{App\Language::trans('Account Classes')}}</span></a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-map fa-fw"></i> <span>{{App\Language::trans('Locations')}}</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{action('UCountriesController@getIndex')}}"><i class="fa fa-globe fa-fw"></i> <span>{{App\Language::trans('Countries')}}</span></a></li>
                <li><a href="{{action('UStatesController@getIndex')}}"><i class="fa fa-location-arrow fa-fw"></i> <span>{{App\Language::trans('States')}}</span></a></li>
                <li><a href="{{action('UCitiesController@getIndex')}}"><i class="fa fa-map-marker fa-fw"></i> <span>{{App\Language::trans('Cities')}}</span></a></li>
              </ul>
            </li>
            <li><a href="{{action('SettingsController@getUtilityChargeIndex')}}"><i class="fa fa-cogs fa-fw"></i> <span>{{App\Language::trans('General Setting')}}</span></a></li>
            <li><a href="{{action('HelpsController@getIndex')}}"><i class="fa fa-book fa-fw"></i> <span>{{App\Language::trans('Help Setting')}}</span></a></li>
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

  <!-- =============================================== -->
