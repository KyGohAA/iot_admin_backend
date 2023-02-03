@extends('_version_02.iot.layouts.admin')
@section('content')
@include('_version_02.iot.layouts.partials._alert')
<div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        @include('_version_02.iot.layouts.partials._index_header')
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
				                            @foreach($cols as $col)
												@if($col != 'store_id')
													@if($col == 'id')
														<th>#</th>
													@elseif(str_contains($col, '_id'))
														<th">{{App\Language::trans(ucwords(str_replace('_id', '', $col)))}}</th>
													@else
														<th>{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
													@endif
												@endif
											@endforeach
											<th class="text-center">{{App\Language::trans('Action')}}</th>
                                        </tr>
                                    </thead>

                            
							
                                    <tbody>
  
					                        @foreach($model as $index => $row)
					                         	<tr id="{{$row['id']}}">
													<td class="text-center">{{$index+1}}</td>
													@foreach($row->toArray() as $key => $value)
														@php
															if(!in_array($key,$cols))
															{
																continue;
															}
														@endphp
														@if($key == 'photo')
															<!-- <td class="text-center"><img class="img-responsive" width="50" height="50" src="{{$row->profile_jpg()}}"></td> -->
														@elseif($key == 'status')
															<td class="text-center">{{$row->display_status_string($key)}}</td>
														@elseif($key != 'user_id' && $key != 'id')
															<td class="text-center">{{$value}}</td>
														@endif
													@endforeach
													<td class="text-center">
														<a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('UsersController@getEdit', [$row->id])}}">{{App\Language::trans('Edit')}}</a>
													</td>
												</tr>
					                        @endforeach
                                           
                                                                           
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
</div>


@endsection
@section('script')
@endsection