@extends('operates.layouts.main')
@section('content')

<!-- CONTENT -->
<div id="page-content">
  <div class="setting-page">
    <div class="container">
      <div class="row ">
        <div class="col s12 m12 l12 ">
          <div class="section-title">
            <span class="theme-secondary-color">Verify</span> ACCOUNT
          </div>
        </div>
      </div>
      <br> 
      <form>
        <div class="row">
          <div class="input-field col s12 m12 l12 ">
             {!! Form::text('fullname', null, ['class'=>'validate','required']) !!}
            <label for="user-firstname">Name</label>
          </div>
        </div>

        <div class="row">
          <div class="input-field col s12 m12 l12 ">
             {!! Form::text('ic_number', null, ['class'=>'validate','required']) !!}
            <label for="user-lastname">IC Number</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12 m12 l12 ">
             {!! Form::text('email', null, ['class'=>'validate','required']) !!}
            <label for="user-email">Email</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12 m12 l12 ">
             {!! Form::text('phone_number', null, ['class'=>'validate','required']) !!}
            <label for="user-phone">Phone</label>
          </div>
        </div>

        <div class="row">
          <div class="col s6 m12 l6 ">
            <label for="zip">Bank Detail</label>
              {!! Form::select('bank_id', App\MembershipModel\Bank::combobox(), null, ['class'=>'form-control']) !!}
          </div>
          <div class="input-field col s6 m12 l6 ">
            {!! Form::text('account_no', null, ['class'=>'validate','required']) !!}
            <label for="account_no">Account No.</label>
          </div>
        </div>


        <div class="row">
          <!-- <div class="input-field col s12 m12 l12 ">
            <textarea id="user-address" class="materialize-textarea"></textarea>
            <label for="user-address">Address</label>
          </div> -->
          <div class="row">
            <div class="input-field col s12 m6 l4 offset-m3 offset-l4 center">
              <input class="waves-effect waves-light btn" value="SUBMIT" type="submit"></div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- END CONTENT -->
<br><br><br>
@endsection
@section('script')
@endsection