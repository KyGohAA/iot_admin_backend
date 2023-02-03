<section class="hk-sec-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-bordered table-hover" id="meter_payment_received_table" name="meter_payment_received_table" style="min-width: 1000px;">
						<thead>
							<tr>
								<th >#</th>
								<th >
									{{App\Language::trans('Date')}}
								</th>
								<th >
									{{App\Language::trans('Document No.')}}
								</th>
								<!-- <th class="text-center">
									{{App\Language::trans('Currency')}}<br>					
								</th>
								<th class="text-center">							
									{{App\Language::trans('Rate')}}<br>
								</th> -->

								<th class="text-center">			
									{{App\Language::trans('Document Amount')}}
								</th>
								<th class="text-center">
									{{App\Language::trans('Unapplied Amount')}}
								</th>
			
								<th class="text-center">
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
								<td ></td>
								<td ></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center">(<span class="currency_label"></span>)</td>
				
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td class="text-right" colspan="5">{{App\Language::trans('Total Applied amount')}} :</td>
								<td class="text-right" name="total_applied_amount" id="total_applied_amount">{{$model->getDouble(0)}}</td>
							</tr>
							<tr>
								<td class="text-right" colspan="5">{{App\Language::trans('Total refunded amount')}} :</td>
								<td class="text-right" name="total_refunded_amount" id="total_refunded_amount">{{$model->getDouble(0)}}</td>
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
</section>
