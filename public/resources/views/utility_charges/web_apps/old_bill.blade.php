@extends('utility_charges.layouts.web_apps')
@section('content')
<style type="text/css">
	.border-padding {
		border: 1px solid #000000; 
		padding: 5px 20px;
	}
	.td-border-top {
		border-top: 1px solid #000000!important;
	}
	.table-bordered,
	.table-bordered tbody tr td,
	.table-bordered thead tr th {
		border: 1px solid #000000;
	}
	.table-bordered thead {
		background-color: blue;
	}
	.table-bordered thead tr th {
		vertical-align: top;
	}
</style>
<div class="document_header">
	<div class="row">
		<div class="col-md-4">
			<p class="text-uppercase text-center bg-danger border-padding">Bil Elektrik Dan Invois Cukai</p>
			<div class="row">
				<div class="col-md-6">{{App\Language::trans('Account No.')}}</div>
				<div class="col-md-6">: 220108474407</div>
			</div>
			<div class="row">
				<div class="col-md-6">{{App\Language::trans('Contact No.')}}</div>
				<div class="col-md-6">: 273970</div>
			</div>
			<div class="row">
				<div class="col-md-6">{{App\Language::trans('Deposit.')}}</div>
				<div class="col-md-6">: RM336.20</div>
			</div>
			<div class="row">
				<div class="col-md-6">{{App\Language::trans('No. Invoice.')}}</div>
				<div class="col-md-6">: 1307541244</div>
			</div>
			<p class="text-uppercase">Anandan A/L Balakrisnan</p>
			<p>E-3A-3A, Subang Parkhomes<br>
				PSRN Kemajuan<br>
				47500 Subang Jaya<br>
				Selangor</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<p class="bg-warning text-center border-padding">Jumlah Perlu Dibayar RM 755.90</p>
		</div>
		<div class="col-md-4">
			<p class="bg-warning text-center border-padding">Tarikh Bil : 15.03.2018</p>
		</div>
	</div>
</div>
<div class="document_content">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th class="col-md-2"></th>
					<th class="col-md-2"></th>
					<th class="col-md-2"></th>
					<th class="col-md-6"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="3" class="text-right"><u>Amaun</u></td>
					<td colspan="3"><u>Bayar Sebelum</u></td>
				</tr>
				<tr>
					<td>Tunggakan</td>
					<td>RM</td>
					<td class="text-right">335.50</td>
					<td colspan="3">Segera</td>
				</tr>
				<tr>
					<td>Caj Semasa</td>
					<td>RM</td>
					<td class="text-right">420.41</td>
					<td colspan="3">14.04.2018</td>
				</tr>
				<tr>
					<td>Penggenapan</td>
					<td>RM</td>
					<td class="text-right">0.01-</td>
					<td colspan="3"></td>
				</tr>
				<tr>
					<td class="td-border-top">Jumlah Bil</td>
					<td class="td-border-top">RM</td>
					<td class="td-border-top text-right">755.90</td>
					<td colspan="3" class="td-border-top"></td>
				</tr>
				<tr>
					<td>Bil Terdahulu<br>(13.02.2018)</td>
					<td>RM</td>
					<td class="text-right">676.55</td>
					<td>Bayaran Akhir<br>(27.02.2018)</td>
					<td>RM</td>
					<td>341.05</td>
				</tr>
				<tr>
					<td class="td-border-top">Jenis Bacaan</td>
					<td class="td-border-top" colspan="2"> : <span class="border-padding bg-danger" style="width: 100%">Bacaan Sebenar</span></td>
					<td class="td-border-top" colspan="3"></td>
				</tr>
				<tr>
					<td class="td-border-top">Tempoh Bil</td>
					<td class="td-border-top" colspan="2"> : 14.02.1018 - 15.03.2018 (30 Hari)</td>
					<td class="td-border-top" colspan="3"></td>
				</tr>
				<tr>
					<td>Tarif</td>
					<td colspan="2"> : A: Kediaman</td>
					<td colspan="3"></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="table-responsive td-border-top">
		<table class="table">
			<tbody>
				<tr>
					<td class="col-md-6">Tarif (kWh/kW)</td>
					<td class="text-right col-md-2">Kegunaan (kWh/kW)</td>
					<td class="text-right col-md-2">Kadar(RM)</td>
					<td class="text-right col-md-2">Amaun(RM</td>
				</tr>
				<tr>
					<td class="col-md-6">200</td>
					<td class="text-right col-md-2">200.00</td>
					<td class="text-right col-md-2">0.2180</td>
					<td class="text-right col-md-2">43.60</td>
				</tr>
				<tr>
					<td class="col-md-6">100</td>
					<td class="text-right col-md-2">100.00</td>
					<td class="text-right col-md-2">0.3340</td>
					<td class="text-right col-md-2">33.40</td>
				</tr>
				<tr>
					<td class="col-md-6">300</td>
					<td class="text-right col-md-2">300.00</td>
					<td class="text-right col-md-2">0.5160</td>
					<td class="text-right col-md-2">154.80</td>
				</tr>
				<tr>
					<td class="col-md-6">300</td>
					<td class="text-right col-md-2">300.00</td>
					<td class="text-right col-md-2">0.5460</td>
					<td class="text-right col-md-2">163.80</td>
				</tr>
				<tr>
					<td class="col-md-6">>900</td>
					<td class="text-right col-md-2">20.00</td>
					<td class="text-right col-md-2">0.5710</td>
					<td class="text-right col-md-2">11.42</td>
				</tr>
				<tr>
					<td class="col-md-6">Jumlah</td>
					<td class="text-right col-md-2">920.00</td>
					<td class="text-right col-md-2"></td>
					<td class="text-right col-md-2">407.02</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="text-center">Keterangan</th>
					<th class="text-center">Tidak Kena GST</th>
					<th class="text-center">Kena GST</th>
					<th class="text-center">Jumlah</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Kegunaan kWh <span class="pull-right">kWh</span></td>
					<td class="text-right">300.00</td>
					<td class="text-right">620.00</td>
					<td class="text-right">920.00</td>
				</tr>
				<tr>
					<td>Kegunaan RM <span class="pull-right">RM</span></td>
					<td class="text-right">77.00</td>
					<td class="text-right">330.02</td>
					<td class="text-right">407.02</td>
				</tr>
				<tr>
					<td>ICPT (RM0.0152-) <span class="pull-right">RM</span></td>
					<td class="text-right">4.56-</td>
					<td class="text-right">9.42-</td>
					<td class="text-right">13.98-</td>
				</tr>
				<tr>
					<td>Kegunaan Bulan Semasa <span class="pull-right">RM</span></td>
					<td class="text-right">72.44</td>
					<td class="text-right">320.60-</td>
					<td class="text-right">393.04-</td>
				</tr>
				<tr>
					<td>6% GST (6% X RM320.60) <span class="pull-right">RM</span></td>
					<td class="text-right"></td>
					<td class="text-right"></td>
					<td class="text-right">19.24-</td>
				</tr>
				<tr>
					<td>KWTBB (1.6%) <span class="pull-right">RM</span></td>
					<td class="text-right"></td>
					<td class="text-right"></td>
					<td class="text-right">6.51-</td>
				</tr>
				<tr>
					<td>Surcaj Lewat Bayar <span class="pull-right">RM</span></td>
					<td class="text-right"></td>
					<td class="text-right"></td>
					<td class="text-right">1.62-</td>
				</tr>
				<tr>
					<td></td>
					<td class="text-right"></td>
					<td class="text-right"></td>
					<td class="text-right">-</td>
				</tr>
				<tr>
					<td>Caj Semasa <span class="pull-right">RM</span></td>
					<td class="text-right"></td>
					<td class="text-right"></td>
					<td class="text-right">420.41-</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="text-center" rowspan="2"></th>
					<th class="text-center" rowspan="2">No. Meter</th>
					<th class="text-center" rowspan="2">Faktor Meter</th>
					<th class="text-center" colspan="2">Bacaan Meter</th>
					<th class="text-center" rowspan="2">Kegunaan</th>
					<th class="text-center" rowspan="2">Unit</th>
				</tr>
				<tr>
					<th class="text-center">Dahulu</th>
					<th class="text-center">Semasa</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-center">M</td>
					<td class="text-center">2122681408</td>
					<td class="text-center">1.00000</td>
					<td class="text-center">20,374.00</td>
					<td class="text-center">21,294.00</td>
					<td class="text-center">920.00</td>
					<td class="text-center">kWh</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="document_footer">
	<div class="row">
		<div class="col-md-12 text-right">
			<a class="btn btn-primary" href="https://cloud.leaf.com.my/web/payment-prepare.php?type=api&inapp=0&paymentid=07435b0e0c4272baa53c992d8adb80a8"><i class="fa fa-credit-card fa-fw"></i> Pay Now</a>
		</div>
	</div>
</div>
@stop
@section('script')
@stop