{!!Form::hidden('leaf_id_user', $user['leaf_id_user'] ,['id'=>'leaf_id_user' , 'value'=>$user['leaf_id_user']]) !!}
@include('_version_02.leaf_accountings.mobile_apps.layouts.partials._header')
@include('_version_02.leaf_accountings.mobile_apps.layouts.partials._left_sidebar')
		@yield('content')
@include('_version_02.leaf_accountings.mobile_apps.layouts.partials._footer')





 
