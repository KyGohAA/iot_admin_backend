@extends('commons.layouts_version_02.admin')
@section('content')
@include('commons.layouts_version_02.partials._alert')
@include('opencarts.products_version_02.partials.save_by_url')
@include('opencarts.products_version_02.partials.search_bar')

<div id="alert_msg_div" class="alert alert-success alert-dismissible hide">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i id="alert_msg" class="icon fa fa-check"></i>
</div>


<!-- Default box -->
@if(!$is_index)
      <section class="hk-sec-wrapper">
                            <h5 class="hk-sec-title">Mode Switch Table</h5>
                            <p class="mb-40">The Stack Table stacks the table headers to a two column layout with headers on the left when the viewport width is less than 40em (640px). Swipe Mode, ModeSwitch, Minimap, Sortable, SortableSwitch modes are available.</p>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="table-wrap">
                                        <table class="table tablesaw table-bordered table-hover  mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
                                            <thead>
                                                <tr>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Movie</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Rank</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Year</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1"><abbr title="Rotten Tomato Rating">Rating</abbr></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Gross</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="title"><a href="javascript:void(0)">Avatar</a></td>
                                                    <td>1</td>
                                                    <td>2009</td>
                                                    <td>83%</td>
                                                    <td>$2.7B</td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><a href="javascript:void(0)">Titanic</a></td>
                                                    <td>2</td>
                                                    <td>1997</td>
                                                    <td>88%</td>
                                                    <td>$2.1B</td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><a href="javascript:void(0)">The Avengers</a></td>
                                                    <td>3</td>
                                                    <td>2012</td>
                                                    <td>92%</td>
                                                    <td>$1.5B</td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><a href="javascript:void(0)">Harry Potter and the Deathly Hallows—Part 2</a></td>
                                                    <td>4</td>
                                                    <td>2011</td>
                                                    <td>96%</td>
                                                    <td>$1.3B</td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><a href="javascript:void(0)">Frozen</a></td>
                                                    <td>5</td>
                                                    <td>2013</td>
                                                    <td>89%</td>
                                                    <td>$1.2B</td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><a href="javascript:void(0)">Iron Man 3</a></td>
                                                    <td>6</td>
                                                    <td>2013</td>
                                                    <td>78%</td>
                                                    <td>$1.2B</td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><a href="javascript:void(0)">Transformers: Dark of the Moon</a></td>
                                                    <td>7</td>
                                                    <td>2011</td>
                                                    <td>36%</td>
                                                    <td>$1.1B</td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><a href="javascript:void(0)">The Lord of the Rings: The Return of the King</a></td>
                                                    <td>8</td>
                                                    <td>2003</td>
                                                    <td>95%</td>
                                                    <td>$1.1B</td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><a href="javascript:void(0)">Skyfall</a></td>
                                                    <td>9</td>
                                                    <td>2012</td>
                                                    <td>92%</td>
                                                    <td>$1.1B</td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><a href="javascript:void(0)">Transformers: Age of Extinction</a></td>
                                                    <td>10</td>
                                                    <td>2014</td>
                                                    <td>18%</td>
                                                    <td>$1.0B</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
@endif

@endsection
@section('script')
@endsection