{!!Form::hidden('leaf_id_user', isset($user['leaf_id_user']) ? $user['leaf_id_user'] : '' ,['id'=>'leaf_id_user' , 'value'=>isset($user['leaf_id_user']) ? $user['leaf_id_user'] : '']) !!}
@include('_version_02.utility_charges.mobile_apps_light.layouts_home.partials._header')
		@yield('content')
@include('_version_02.utility_charges.mobile_apps_light.layouts_home.partials._footer')





 
