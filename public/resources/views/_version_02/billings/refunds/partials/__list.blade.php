<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Payments/Credit notes')}}</h3> 
	</div>
	<div class="box-body">
		<div>
		
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-bordered table-hover" style="min-width: 1000px;">
						<thead>
							<tr>
								<th>#</th>
								<th class="col-md-1">
									{{App\Language::trans('Date')}}
								</th>
								<th class="col-md-2">
									{{App\Language::trans('Document No.')}}
								</th>
								<th class="col-md-1 text-center">
									{{App\Language::trans('Currency')}}<br>					
								</th>
								<th class="col-md-1 text-center">							
									{{App\Language::trans('Rate')}}<br>
								</th>

								<th class="col-md-1 text-center">			
									{{App\Language::trans('Document Amount')}}
								</th>
								<th class="col-md-1 text-center">
									{{App\Language::trans('Unapplied Amount')}}
								</th>
			
								<th class="col-md-2 text-center">
									{{App\Language::trans('Amount')}} 
									(<span class="currency_label"></span>)
								</th>
					
							</tr>
						</thead>
						<tbody>
							@foreach($model->items as $row)
							@endforeach
							<tr>
								<td>#</td>
								<td class="col-md-1">
					
								</td>
								<td class="col-md-2">
		
								</td>
								<td class="col-md-1 text-center">
											
								</td>
								<td class="col-md-1 text-center">							
		
								</td>

								<td class="col-md-1 text-center">			
								
								</td>
								<td class="col-md-1 text-center">
							
								</td>
		
								<td class="col-md-2 text-center">
	
								(<span class="currency_label"></span>)
								</td>
				
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td class="text-right" colspan="7">{{App\Language::trans('Total Applied amount')}} :</td>
								<td class="text-right">{{$model->getDouble(0)}}</td>
							</tr>
							<tr>
								<td class="text-right" colspan="7">{{App\Language::trans('Total refunded amount')}} :</td>
								<td class="text-right">{{$model->getDouble(0)}}</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
			<div class="col-md-12">
		 		<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
		          	 Tick the check box next to the payments/credit notes to apply refund.
		          </p>
			</div>
	</div>
</div>
