@extends('web_stores.layouts.app')
@section('content')
<div class="block-title">FAQ</div>
<div class="list accordion-list">
  <ul>
    @foreach($model as $row)
      <li class="accordion-item">
        <a href="#" class="item-content item-link">
          <div class="item-inner">
            <div class="item-title">{!!$row['faq_question']!!}</div>
          </div>
        </a>
        <div class="accordion-item-content">
          <div class="block">
            <p>{!!$row['faq_answer']!!}</p>
          </div>
        </div>
      </li>
    @endforeach
  </ul>
</div>
@endsection
@section('script')
@endsection