<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Invoice Listing')}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table id="invoice_table" class="table table-bordered table-hover" style="min-width: 1000px;">
						<thead>
							<tr>
								<th>#</th>
								<th class="col-md-1">
									{{App\Language::trans('Invoice No.')}}
								</th>
								<th class="col-md-2">
									{{App\Language::trans('Due Date')}}
								</th>
								<th class="col-md-1 text-center">
									{{App\Language::trans('Currency')}}<br>					
								</th>
								<th class="col-md-1 text-center">							
									{{App\Language::trans('Date')}}<br>
								</th>

								<th class="col-md-1 text-center">			
									{{App\Language::trans('Invoice Amount')}}
								</th>
								<th class="col-md-1 text-center">
									{{App\Language::trans('Outstanding Amount')}}
								</th>
								<th class="col-md-1 text-center">
									{{App\Language::trans('Assign Credit')}}
								</th>
								<th class="col-md-2 text-center">
									{{App\Language::trans('Amount')}} 
									(<span class="currency_label"></span>)
								</th>
					
							</tr>
						</thead>
						<tbody>
							@foreach($model->items as $row)
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
										<td class="col-md-1 text-center">
									
										</td>
										<td class="col-md-2 text-center">
			
										(<span class="currency_label"></span>)
										</td>
								</tr>
							@endforeach			
						</tbody>
						<tfoot>
							<tr>
								<td class="text-right" colspan="8">{{App\Language::trans('Total Applied amount')}} :</td>
								<td class="text-right">{{$model->getDouble(0)}}</td>
							</tr>
							<tr>
								<td class="text-right" colspan="8">{{App\Language::trans('Total refunded amount')}} :</td>
								<td class="text-right">{{$model->getDouble(0)}}</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
