<div class="row">
    <div class="col-md-12" style="padding-bottom:50px;">
        <!-- DIRECT CHAT PRIMARY -->
        <div class="box box-info box-solid" style="background-color:#b3d9fc;">
            <div class="box-header with-border" style="background-color:#59abf7">
                <h3 class="box-title">{{App\Language::trans('Help')}}</h3>

                <!-- <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div> -->
            </div>
			
            <!-- /.box-body -->
            <div class="box-footer">
                <b>{{App\Language::trans('How do I pay online?')}}</b><br>
                    <b>{{App\Language::trans('If you are')}}</b><a> <font color="blue">{{App\Language::trans('applying online')}}</font></a>, {{App\Language::trans('you will be asked to pay at the end of your application.')}}<br>
                    <b>{{App\Language::trans('If you are')}}</b><a> <font color="blue">{{App\Language::trans('paying online')}}</font></a> {{App\Language::trans('for a ')}}<b>{{App\Language::trans('paper application:')}}</b><br>
                    {{App\Language::trans('Select your fee category.')}}<br>
                    {{App\Language::trans('When you get to the fee table ,select the fees you want to pay by putting a number in the "Quantity" column.')}}<br>

                    {{App\Language::trans('When you reach the "Summary of Fees" page , select "Login and Pay".')}} {{App\Language::trans('You will be asked to log in or register for a new payment account.')}}
                    {{App\Language::trans('After you log in, you will go to an external Web page to enter your payment details.')}} {{App\Language::trans('Once your payment is completed , a receipt will be emailed to you.')}}<br>

                    {{App\Language::trans('After you pay online, submit proof of payment with your application:')}}<br>
                    {{App\Language::trans('Print a copy of your receipt.')}}<br>
                    {{App\Language::trans('Write your application number or your Client ID Number on the back of your receipt.')}}
                    {{App\Language::trans('If you do not know them or a number has not been assigned to you yet,write your full name and address instead.')}}
                    {{App\Language::trans('Include a copy of the receipt with your application.')}}
					
					
            </div>
            <!-- /.box-footer-->
        </div>
        <!--/.direct-chat -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->