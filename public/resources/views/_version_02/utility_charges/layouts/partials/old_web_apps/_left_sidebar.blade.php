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
        <li><a href="{{action('WebUtilityChargesController@getBill')}}"><i class="fa fa-map-marker fa-fw"></i> <span>{{App\Language::trans('Current Bill')}}</span></a></li>
        <li class="header">{{App\Language::trans('LABELS')}}</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>{{App\Language::trans('Important')}}</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>{{App\Language::trans('Warning')}}</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>{{App\Language::trans('Information')}}</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->
