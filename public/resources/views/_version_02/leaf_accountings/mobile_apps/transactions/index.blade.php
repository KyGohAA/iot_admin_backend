@extends('_version_02.leaf_accountings.mobile_apps.layouts.main')
@section('content')

<!-- CONTENT -->
<div id="page-content">
  <div class="section gallery section section_team">
    <div class="container">


  <ul class="nav nav-light nav-tabs active" role="tablist">
       <li role="presentation" class="nav-item"  style="width: 33%;">
        <a href="#new_listing" aria-controls="new_listing" class="d-flex h-60p align-items-center nav-link-2" role="tab" data-toggle="tab"><h5>{{App\Language::trans('New')}}</h5></a>
      </li>

      <li role="presentation" class="nav-item" style="width: 33%;">
        <a href="#income_listing" aria-controls="income_listing" class="d-flex h-60p align-items-center nav-link-2 active" role="tab" data-toggle="tab"><h5>{{App\Language::trans('Income')}}</h5></a>
      </li>

      <li role="presentation" class="nav-item"  style="width: 33%;">
        <a href="#expenses_listing" aria-controls="expenses_listing" class="d-flex h-60p align-items-center nav-link-2" role="tab" data-toggle="tab"><h5>{{App\Language::trans('Expenses')}}</h5></a>
      </li>
  </ul>

    <div class="tab-content"> 



        <div role="tabpanel" class="tab-pane active" id="new_listing">

                  @foreach($transaction_listing['new_listing'] as $row)
                 
                         @php 
                          $product_model = App\Product::find($row['product_id']);                      
                          $photo_path = $row['product_id']  == 9999 ? 'operates/img/icon/egg.png' : (isset($product_model['id']) ? $product_model['cover_photo_path'] : '');
                        @endphp
                     
                        <div class="row row-team gallery-img-box gallery-market gallery-all">
                          <div class="col s12">
                            <div class="wrap-team">
                             
                              <div>     
                                <table>
                                  <tr style="padding: 0; margin: 0;"> 
                                      <td style="padding: 0; margin: 0;"></td>
                                      <td colspan="2" width="95%;" style="padding: 0; margin: 0;">
                                        <img style="height:20px ;width:20px; margin-left: 5px;" src="{{asset($photo_path)}}" alt="transaction">  {{$row['description']}} <small><font style="margin-left: 2px;" size="1px">[Doc No: : {{$row['document_no']}}]</font></small> <hr>
                                      </td>
                                        <td width="5%"></td>
                                  </tr>
                                  <tr style="padding: 0; margin: 0;">
                                    <td style="padding: 0; margin: 0;"></td>
                                    <td width="70%;" style="padding: 0; margin: 0;">
                                        <small style=" margin-left: 5px;"><font size="1px"> {{App\Language::trans('Price')}} : {{$row['amount']}}</font> </small><br>          
                                         <hr>
                                        <small><i class="fa fa-calendar" style="margin-right:5px;"></i>{{App\Language::trans('Document Date')}} &nbsp;&nbsp;&nbsp;  : &nbsp; &nbsp;  {{$row['document_date']}}</small> <br>
                                        <small><i class="fa fa-check" style="margin-right:5px;"></i>{{App\Language::trans('Approval Date')}} &nbsp;: &nbsp; &nbsp; {{$row['approved_date']}} </small> <br>

                                    </td>
                                    <td  width="5%;" style="padding: 0; margin: 0;"></td>
                                    <td  width="20%;" style="padding: 0; margin: 0;">
                                        <label class="readmore-btn" style="margin-bottom: 5px; margin-right: 5px;  display:auto; width: 95%;">{{$row['label']}}</label>
                                        <a href="" style="margin-bottom: 5px; margin-right: 5px;  display:auto; width: 95%;" class="readmore-btn nav-link">{{App\Language::trans('Detail')}} </a>
                                    </td>
                                  </tr>
                                </table>
                              </div>
                              <div class="clear"></div>
                            </div>
                          </div>
                        </div>   
           
              @endforeach
        </div>


          <div role="tabpanel" class="tab-pane" id="income_listing">
                      @foreach($transaction_listing['income_listing'] as $row)
                         @php 
                          $product_model = App\Product::find($row['product_id']);                      
                          $photo_path = $row['product_id']  == 9999 ? 'operates/img/icon/egg.png' : (isset($product_model['id']) ? $product_model['cover_photo_path'] : '');
                        @endphp
                     
                        <div class="row row-team gallery-img-box gallery-market gallery-all">
                          <div class="col s12">
                            <div class="wrap-team">
                             
                              <div>     
                                <table>
                                  <tr style="padding: 0; margin: 0;"> 
                                      <td style="padding: 0; margin: 0;"></td>
                                      <td colspan="2" width="95%;" style="padding: 0; margin: 0;">
                                        <img style="height:20px ;width:20px; margin-left: 5px;" src="{{asset($photo_path)}}" alt="transaction">  {{$row['description']}} <small><font style="margin-left: 2px;" size="1px">[Doc No: : {{$row['document_no']}}]</font></small> <hr>
                                      </td>
                                        <td width="5%"></td>
                                  </tr>
                                  <tr style="padding: 0; margin: 0;">
                                    <td style="padding: 0; margin: 0;"></td>
                                    <td width="70%;" style="padding: 0; margin: 0;">
                                        <small style=" margin-left: 5px;"><font size="1px"> {{App\Language::trans('Price')}} : {{$row['amount']}}</font> </small><br>          
                                         <hr>
                                        <small><i class="fa fa-calendar" style="margin-right:5px;"></i>{{App\Language::trans('Document Date')}} &nbsp;&nbsp;&nbsp;  : &nbsp; &nbsp;  {{$row['document_date']}}</small> <br>
                                        <small><i class="fa fa-check" style="margin-right:5px;"></i>{{App\Language::trans('Approval Date')}} &nbsp;: &nbsp; &nbsp; {{$row['approved_date']}} </small> <br>

                                    </td>
                                    <td  width="5%;" style="padding: 0; margin: 0;"></td>
                                    <td  width="20%;" style="padding: 0; margin: 0;">
                                        <label class="readmore-btn" style="margin-bottom: 5px; margin-right: 5px;  display:auto; width: 95%;">{{$row['label']}}</label>
                                        <a href="" style="margin-bottom: 5px; margin-right: 5px;  display:auto; width: 95%;" class="readmore-btn nav-link">{{App\Language::trans('Detail')}} </a>
                                    </td>
                                </table>
                              </div>
                              <div class="clear"></div>
                            </div>
                          </div>
                        </div>  
                  @endforeach
          </div>

            <div role="tabpanel" class="tab-pane" id="expenses_listing">
              
                   @foreach($transaction_listing['expenses_listing'] as $row)

                     @php 
                      $product_model = App\Product::find($row['product_id']);                      
                      $photo_path = $row['product_id']  == 9999 ? 'operates/img/icon/egg.png' : (isset($product_model['id']) ? $product_model['cover_photo_path'] : '');
                    @endphp
                 
                    <div class="row row-team gallery-img-box gallery-market gallery-all">
                      <div class="col s12">
                        <div class="wrap-team">
                         
                          <div>     
                            <table>
                              <tr style="padding: 0; margin: 0;"> 
                                  <td style="padding: 0; margin: 0;"></td>
                                  <td colspan="2" width="95%;" style="padding: 0; margin: 0;">
                                    <img style="height:20px ;width:20px; margin-left: 5px;" src="{{asset($photo_path)}}" alt="transaction">  {{$row['description']}} <small><font style="margin-left: 2px;" size="1px">[Doc No: : {{$row['document_no']}}]</font></small> <hr>
                                  </td>
                                    <td width="5%"></td>
                              </tr>
                              <tr style="padding: 0; margin: 0;">
                                <td style="padding: 0; margin: 0;"></td>
                                <td width="70%;" style="padding: 0; margin: 0;">
                                    <small style=" margin-left: 5px;"><font size="1px"> {{App\Language::trans('Price')}} : {{$row['amount']}}</font> </small><br>          
                                     <hr>
                                    <small><i class="fa fa-calendar" style="margin-right:5px;"></i>{{App\Language::trans('Document Date')}} &nbsp;&nbsp;&nbsp;  : &nbsp; &nbsp;  {{$row['document_date']}}</small> <br>
                                    <small><i class="fa fa-check" style="margin-right:5px;"></i>{{App\Language::trans('Approval Date')}} &nbsp;: &nbsp; &nbsp; {{$row['approved_date']}} </small> <br>

                                </td>
                                <td  width="5%;" style="padding: 0; margin: 0;"></td>
                                <td  width="20%;" style="padding: 0; margin: 0;">
                                        <label class="readmore-btn" style="margin-bottom: 5px; margin-right: 5px;  display:auto; width: 95%;">{{$row['label']}}</label>
                                        <a href="" style="margin-bottom: 5px; margin-right: 5px;  display:auto; width: 95%;" class="readmore-btn nav-link">{{App\Language::trans('Detail')}} </a>
                                </td>
                              </tr>
                            </table>
                          </div>
                          <div class="clear"></div>
                        </div>
                      </div>
                    </div>   

              @endforeach
            </div>

    </div>       
    </div>
  </div>
</div>
<!-- END CONTENT -->
<br><br><br>
 
@endsection
@section('script')
@endsection