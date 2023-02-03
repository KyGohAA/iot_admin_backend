<td class="text-center">
  <a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action($page_variables['edit_link'], [$row->id])}}">{{App\Language::trans('Edit')}}</a> | 
  <!-- <a href="{{action($page_variables['view_link'], [$row->id])}}">{{App\Language::trans('View')}}</a> |  -->
  <a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action($page_variables['delete_link'], [$row->id])}}">{{App\Language::trans('Del')}}</a>
</td>