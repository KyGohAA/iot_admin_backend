@extends('_version_02.utility_charges.mobile_apps.layouts.main')
@section('content')
<!-- NEWS -->
<div class="section list-news" style="position: absolute; top: 15px;">
  <div class="container">
    <div class="row row-title">
      <div class="col s12">
        <div class="section-title">
          <span class="theme-secondary-color"></span> Check In history
        </div>
      </div>
    </div>
    <div class="row row-list-news">
      <div class="col s12">
         @foreach($user_stay_history_listing as $history)
		       	 <!--  item-->
		        <div class="news-item">
		          <div class="news-tem-image">
		            <img src="{{asset(App\Setting::MOBILE_ICON_PATH.$history['house_room_type'].'_room.png')}}" alt="room_type">
		          </div>
		          <div class="news-item-info">
		            <div class="list-news-title">
		              {{$history['house_subgroup']}} : {{$history['house_unit']}} <label class="readmore" style="max-height:3% ;margin-left:7px; margin-bottom:4px;background-image: linear-gradient(to bottom left, red, white);">{{$history['house_room_type'] == 'twin' ? "Twin room" : "Single room"}}</label>
		              <hr  style='margin-bottom:3px;margin-top:3px; '>
		            </div>

		            <div>
		            	<table>
		            		<tr>
		            			<td style="padding: 0; margin: 0;"><font size='0.2em'>{{App\Language::trans('Check In Date')}} : </font></td>
		            			<td style="padding: 0; margin: 0;"><font size='0.1em'>{{$history['house_room_member_start_date']}}</font></td>
		            		</tr>
		            		<tr>
		            			<td style="padding: 0; margin: 0;"><font size='0.2em'>{{App\Language::trans('Check Out Date')}} :</font></td>
		            			<td style="padding: 0; margin: 0;"><font size='0.05em'>{{$history['house_room_member_end_date'] != '0000-00-00 00:00:00' ?  $history['house_room_member_end_date'] : '-'}}</font></td>
		            		</tr>
		            	</table>
		     
		            </div>
		            
		            
		          </div>
		        </div>
		        <!-- End  item-->					
		  @endforeach  
      </div>
    </div>
  </div>
</div>

<br><br><br>
 
@endsection
@section('script')
@endsection