<div class="row" style="padding-bottom:50px;">
   <div class="col-md-12">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget box-solid box-info widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black" style="background: url('http://dsc.propwall.com/photos/1901079/36b_thumb.jpg') center center;">
             
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="{{$membership_detail['photo']}}" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
         
               <div class="col-sm-12 border-right">
                  <div class="description-block">
                    <h5 class="description-header">{{$membership_detail['member_detail']['house_member_name']}}</h5>
                    <span ><h6>{{App\Language::trans('Valid from')}} {{$membership_detail['membership_start_date']}} {{App\Language::trans('till')}} {{$membership_detail['membership_end_date']}}</h6></span>
                  </div>
                  <!-- /.description-block -->
                </div>
              </div>
                  <div class="row">
                   <div class="col-sm-12 border-right">
                    <ul class="nav nav-stacked">
                      <li><a>{{$membership_detail['membership_type']}}<span class="pull-right badge bg-green"><i class="fa fa-credit-card" aria-hidden="true"></i></span></a></li>
                      <li><a>{{$membership_detail['member_detail']['house_member_phonenumber']}}<span class="pull-right badge bg-green"><i class="fa fa-phone" aria-hidden="true"></i></span></a></li>
                      <li><a>{{$membership_detail['member_detail']['house_member_email']}}<span class="pull-right badge bg-green"><i class="fa fa-inbox" aria-hidden="true"></i></span></a></li>
                    </ul>
                    </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
       <!-- /.widget-user -->

</div>
<!-- /.row -->






