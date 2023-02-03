{!!Form::hidden('leaf_id_user', isset($user['leaf_id_user']) ? $user['leaf_id_user'] : '' ,['id'=>'leaf_id_user' , 'value'=>isset($user['leaf_id_user']) ? $user['leaf_id_user'] : '']) !!}
@include('_version_02.utility_charges.mobile_apps.layouts.partials._header')
		@yield('content')
@include('_version_02.utility_charges.mobile_apps.layouts.partials._footer')





 
