<div class="modal fade"  tabindex="-1" role="dialog" id="utransaction_debug_modal" aria-labelledby="utransaction_debug_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="utransaction_debug_modal_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            {!! Form::model($model, ['class'=>'form-horizontal','id'=>'member-application-form','name'=>'member-application-form',"files"=>true]) !!}
                

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
                    {!! Form::label('name', App\Language::trans('Name'), ['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!!Form::select("check_list[]", App\Customer::combobox_from_leaf(), null, array("style"=>"width: 100%;", "multiple class"=>"chosen-select","class"=>"form-control select2","id"=>"check_list","multiple"=>true))!!}
                    </div>
                </div>
                 {!!Form::hidden('utransaction_debug_operation', null)!!} 
                 <h5 class="hk-sec-title">{{App\Language::trans('Result')}}</h5><hr>

                
            </div>
     
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="get_utransaction_result_by_operation();">Submit</button>
                {!!Form::hidden('id_house_member', null, ['id'=>'id_house_member']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>



@section('script')

@endsection