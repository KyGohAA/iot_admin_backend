<div class="modal fade" id="power_meter_house_room_detail_modal" tabindex="-1" role="dialog" aria-labelledby="power_meter_house_room_detail_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="power_meter_house_room_detail_modal_title" >{{App\Language::trans('Information')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id='meter_house_room_detail_div'>
        
            </div>
       
       


            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@section('script')
@endsection