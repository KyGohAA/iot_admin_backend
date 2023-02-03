<!--No blank space is allowed so the code is link at one line-->
<ol class="track-progress" data-steps="{{$work_flow_listing['total_step']}}">
   <?php $counter = 1?>
   @foreach($work_flow_listing['work_flow'] as $work_flow)<!----><li onclick='go_to_step_with_previous_show(this,"go_to");' id="step_{{$counter}}" class="{{$work_flow['status']}}"><span>{{App\Language::trans($work_flow['title'])}}</span><i></i></li><!----><?php $counter++;?><!---->@endforeach
   <input type="hidden" id="total_step" name="total_step" value="{{$work_flow_listing['total_step']}}">
   <input type="hidden" id="format_correct" name="format_correct" value='false'>
</ol>

            

