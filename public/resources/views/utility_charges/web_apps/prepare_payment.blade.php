@extends('utility_charges.layouts.web_apps')
@section('content')
<div class="row progress-tab">
	<div class="col-sm-4 active">
		<center>1. {{App\Language::trans('Details')}}</center>
	</div>
	<div class="col-sm-4">
		<center>2. {{App\Language::trans('Payment')}}</center>
	</div>
	<div class="col-sm-4">
		<center>3. {{App\Language::trans('Receipt')}}</center>
	</div>
</div>
{!!Form::open()!!}
<h3 class="title">{{App\Language::trans('Make a payment')}}</h3>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4>{{App\Language::trans('Account')}}</h4>
	</div>
	<div class="panel-body">
		<div class="table-row row">
			<div class="table-col-md-9 col-md-9 border-right">
				{!!Form::select('room_id', App\LeafAPI::get_self_houses(), null, ['class'=>'input-room'])!!}
				<p class="text-uppercase">{{Auth::user()->fullname}}</p>
				@if(session('status_level'))
					<div class="alert alert-{{session('status_level')}} alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						{{session('status_msg')}}
					</div>
				@endif
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>{{App\Language::trans('Due Date')}}</th>
								<th>{{App\Language::trans('Document Date')}}</th>
								<th>{{App\Language::trans('Document No.')}}</th>
								<th class="text-center">{{App\Language::trans('Status')}}</th>
								<th class="text-right">{{App\Language::trans('Amount')}}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($listing as $index => $row)
								<tr>
									<td>{{$index+1}}</td>
									<td>{{$setting->getDate($row->due_date)}}</td>
									<td>{{$setting->getDate($row->document_date)}}</td>
									<td>{{$row->document_no}}</td>
									<td class="text-center">{{strtotime($row->due_date) <= strtotime('now') ? App\Language::trans('Outstanding'):App\Language::trans('Unpaid')}}</td>
									<td class="text-right">{{$setting->getDouble($row->total_amount-$row->over_due_amount)}}</td>
									@php $total += $row->total_amount; @endphp
								</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td colspan="5">{{App\Language::trans('Outstanding')}}</td>
								<td class="text-right">RM{{$setting->getDouble($outstanding_balance)}}</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div class="table-col-md-3 col-md-3">
				<p>RM<span class="pull-right">{{App\Language::trans('Enter amount')}}</span></p>
				{!!Form::number('amount', $setting->getDouble($outstanding_balance), ['class'=>'input-amount','min'=>'0','step'=>'0.01','onchange'=>'init_double(this)'])!!}
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-md-6">
				<h2>{{App\Language::trans('Total')}}</h2>
			</div>
			<div class="col-md-6 text-right">
				<h2><small>RM</small> <span class="grand_total">{{$setting->getDouble($outstanding_balance)}}</span></h2>
			</div>
		</div>
	</div>
</div>
<div class="row margin-bottom-50">
	<div class="col-md-12 text-right">
		<a class="btn" href="#">{{App\Language::trans('Cancel')}}</a>
		<button name="submit" class="btn btn-success" type="submit">{{App\Language::trans('Proceed')}} <i class="fa fa-angle-right fa-fw"></i></button>
	</div>
</div>
{!!Form::close()!!}
<div class="row">
	<div class="col-md-12">
		<a target="_blank" href="https://www.sunway.com.my">
			<img class="img-responsive" src="{{asset('img/utility_charges/sunway_banner.jpg')}}">
		</a>
	</div>
</div>
@stop
@section('script')
$("input[name=amount]").on("change", function(){
	$(".grand_total").html(parseFloat($(this).val()).toFixed(2));
});
{{-- $("button[name=submit]").on("click", function(event){
	event.preventDefault();
	if($("input[name=amount]").val() <= 0) {
		alert("{{App\Language::trans('Amount cannot be zero.')}}");
	} else {
		$("form").submit();
	}
}) --}}
$("select[name=room_id]").on("change", function(){
    var url = window.location.href;
    var n = url.indexOf("?");
    url = url.substring(0, n != -1 ? n : url.length);
	if (url.indexOf("?") > -1){
	   url += "&room_id="+$(this).val();
	}else{
	   url += "?room_id="+$(this).val();
	}
	window.location.href = url;
})
@stop