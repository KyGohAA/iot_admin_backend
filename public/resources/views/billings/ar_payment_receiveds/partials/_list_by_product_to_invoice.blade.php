<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Product List Form')}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<a class="btn btn-default margin-bottom-15" onclick="add_row('product_table')" href="javascript:void(0)"><i class="fa fa-plus-square fa-fw"></i> {{App\Language::trans('Add Row')}}</a>
				<div class="table-responsive">
					<table id="product_table" class="table table-bordered table-hover" style="min-width: 1000px;">
						<thead>
							<tr>
								<th>#</th>
								<th class="col-md-2">
									{{App\Language::trans('Product Code')}}
								</th>
								<th class="col-md-5">
									{{App\Language::trans('Description')}}
								</th>
								<th class="col-md-1 text-center">
									{{App\Language::trans('Qty')}}<br>
									{{App\Language::trans('UOM')}}<br>
									{{App\Language::trans('Unit Price')}}
								</th>
								<th class="col-md-1 text-center">
									{{App\Language::trans('Tax')}}<br>
									{{App\Language::trans('Tax Amount')}}
								</th>
								<th class="col-md-2 text-center">
									{{App\Language::trans('Amount')}} 
									(<span class="currency_label">{{$model->currency_code}}</span>)
								</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach($model->items as $row)
								<tr>
									<td>{{$i}}</td>
									<td>
										{!!Form::select('products['.$i.'][product_id]', App\Product::combobox(), $row->product_id, ['class'=>'form-control','onchange'=>'init_product_info_by_leaf_product_id(this, "sales")'])!!}
									</td>
									<td>
										{!!Form::textarea('products['.$i.'][description]', $row->product_description, ['rows'=>6,'class'=>'form-control'])!!}
									</td>
									<td>
										{!!Form::text('products['.$i.'][quantity]', $row->quantity, ['class'=>'form-control margin-bottom-15','onchange'=>'init_calculate_row(this, false)'])!!}
										{!!Form::text('products['.$i.'][uom]', $row->uom, ['class'=>'form-control margin-bottom-15'])!!}
										{!!Form::text('products['.$i.'][unit_price]', $row->unit_price, ['class'=>'form-control','onchange'=>'init_calculate_row(this, true)'])!!}
									</td>
									<td>
										{!!Form::select('products['.$i.'][tax_id]', App\Tax::combobox(App\Tax::sale_tag), $row->tax_id, ['class'=>'form-control margin-bottom-15'])!!}
										{!!Form::hidden('products['.$i.'][tax_percent]', $row->tax_percent)!!}
										{!!Form::text('products['.$i.'][tax_txt]', $row->tax_txt, ['class'=>'form-control margin-top-15',])!!}
									</td>
									<td>
										{!!Form::text('products['.$i.'][amount]', $row->amount, ['class'=>'form-control margin-bottom-15 text-right','readonly'])!!}
									</td>
									<td class="text-center">
										<a class="btn btn-default" onclick="remove_row(this)" href="javascript:void(0)"><i class="fa fa-trash fa-fw"></i></a>
									</td>
								</tr>
								@php $i++; @endphp
							@endforeach
							<tr>
								<td>{{$i}}</td>
								<td>
									{!!Form::select('products['.$i.'][product_id]', App\Product::combobox(), null, ['class'=>'form-control','onchange'=>'init_product_info_by_leaf_product_id(this, "sales")'])!!}
								</td>
								<td>
									{!!Form::textarea('products['.$i.'][description]', null, ['rows'=>6,'class'=>'form-control'])!!}
								</td>
								<td>
									{!!Form::text('products['.$i.'][quantity]', null, ['class'=>'form-control margin-bottom-15','onchange'=>'init_calculate_row(this, false)'])!!}
									{!!Form::text('products['.$i.'][uom]', null, ['class'=>'form-control margin-bottom-15'])!!}
									{!!Form::text('products['.$i.'][unit_price]', null, ['class'=>'form-control','onchange'=>'init_calculate_row(this, true)'])!!}
								</td>
								<td>
									{!!Form::select('products['.$i.'][tax_id]', App\Tax::combobox(App\Tax::sale_tag), null, ['class'=>'form-control margin-bottom-15'])!!}
									{!!Form::hidden('products['.$i.'][tax_percent]', null)!!}
									{!!Form::text('products['.$i.'][tax_txt]', null, ['class'=>'form-control margin-top-15',])!!}
								</td>
								<td>
									{!!Form::text('products['.$i.'][amount]', null, ['class'=>'form-control margin-bottom-15 text-right','readonly'])!!}
								</td>
								<td class="text-center">
									<a class="btn btn-default" onclick="remove_row(this)" href="javascript:void(0)"><i class="fa fa-trash fa-fw"></i></a>
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td class="text-right" colspan="5">{{App\Language::trans('Sub Total')}} :</td>
								<td class="text-right sub_total">{{$model->getDouble($model->amount)}}</td>
								<td></td>
							</tr>
							<tr>
								<td class="text-right" colspan="5">{{App\Language::trans('SST @ 10.00%')}} :</td>
								<td class="text-right gst_total">{{$model->getDouble($model->gst_amount)}}</td>
								<td></td>
							</tr> 
							<tr>
								<td class="text-right" colspan="5">{{App\Language::trans('Grand Total')}} :</td>
								<td class="text-right grand_total">{{$model->getDouble($model->total_amount)}}</td>
								<td></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
