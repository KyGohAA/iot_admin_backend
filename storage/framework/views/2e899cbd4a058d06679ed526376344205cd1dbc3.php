<td class="text-center">
  <a onclick="return confirm(confirmMsg)" class="loading-label" href="<?php echo e(action($page_variables['edit_link'], [$row->id])); ?>"><?php echo e(App\Language::trans('Edit')); ?></a> | 
  <!-- <a href="<?php echo e(action($page_variables['view_link'], [$row->id])); ?>"><?php echo e(App\Language::trans('View')); ?></a> |  -->
  <a onclick="return confirm(confirmMsg)" class="loading-label" href="<?php echo e(action($page_variables['delete_link'], [$row->id])); ?>"><?php echo e(App\Language::trans('Del')); ?></a>
</td>