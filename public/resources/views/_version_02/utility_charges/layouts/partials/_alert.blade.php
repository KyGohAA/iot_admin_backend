@if(session(App\Setting::session_alert_status))
	<div class="alert alert-{{session(App\Setting::session_alert_status)}} alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i class="icon fa fa-{{session(App\Setting::session_alert_icon)}}"></i>
		{{session(App\Setting::session_alert_msg)}}
	</div>
@endif