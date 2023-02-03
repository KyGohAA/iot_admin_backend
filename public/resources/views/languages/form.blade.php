@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
{!! Form::open() !!}
<div class="table-responsive" style="overflow-x:auto;">
	<table id="leaf_data_tablex" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30">
		<thead>
			<tr>
				<th>{{App\Language::trans('ID')}}</th>
				<th class="text-center">{{App\Language::trans('Source')}}</th>
				<th class="text-center">{{App\Language::trans('English')}}</th>
				<th class="text-center">{{App\Language::trans('Malay')}}</th>
				<th class="text-center">{{App\Language::trans('Chinese Simplified')}}</th>
				<th class="text-center">{{App\Language::trans('Chinese Traditional')}}</th>
			</tr>
		</thead>
		<tbody>
			@foreach($ori_lang as $row)
				<tr>
					<td>{{$n++}}</td>
					<td class="text-center">{{$row->word_str}}</td>
					@foreach(DB::table('translation_languages')->get() as $type)
						<?php $fdata = DB::table('translation_words')->where('language_id','=',$type->id)->where('translation_of_id_word','=',$row->id)->first(); ?>
      					<td>{!!Form::textarea("translation[$row->id][type][$type->id]", $fdata ? $fdata->word_str:'', array("rows"=>3,"class"=>"form-control"))!!}</td>
					@endforeach
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6" class="text-right">
					<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o fa-fw"></i> {{App\Language::trans('Save')}}</button>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection