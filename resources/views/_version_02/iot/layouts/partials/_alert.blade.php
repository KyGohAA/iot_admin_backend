@if(session(App\Setting::session_alert_status))
	<div class="alert alert-{{session(App\Setting::session_alert_status)}} alert-dismissible mt-5">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i id="alert_msg" class="icon fa fa-{{session(App\Setting::session_alert_icon)}}"></i>
		{{session(App\Setting::session_alert_msg)}}
	</div>
@endif




<div class="hf-elementor-layout elementor-element elementor-element-d39bbae iq-box-shadow icon-position-left elementor-widget elementor-widget-xamin_counter" data-id="d39bbae" data-element_type="widget" data-widget_type="xamin_counter.default">
		<div class="elementor-widget-container">
			<div class="iq-counter text-left iq-counter-style-1">

			<div class="iq-counter-icon">
						<i aria-hidden="true" class=" flaticon-ip"></i>			
		    </div>

			<div class="counter-content">
					<p class="iq-counter-info">
						<label id='iot_temperature'>Loading ...</label>
					</p>
					<h6 class="counter-title-text">Temperature</h6>

			</div>

			</div>

		</div>
</div>
		

	<div class="iq-counter-icon">
		 <div class="elementor-widget-container">
			<div class="iq-counter text-left iq-counter-style-3">

				<div class="iq-counter-icon">
					<i aria-hidden="true" class="fas fa-temperature-high"></i>			
				</div>
				<div class="counter-content">
					<p class="iq-counter-info">
						<label id='iot_temperature'>Loading ...</label>
					</p>
					<h6 class="counter-title-text">Temperature</h6>
				</div>

			</div>

		</div>
	</div>


$('#target-div').load('http://13.251.20.181/leaf_webview_iot/public/index.php/admin/page/redirect/device/x24e124136c225107 #iot-main-content');