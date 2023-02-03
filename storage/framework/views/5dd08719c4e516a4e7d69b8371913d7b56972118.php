<?php echo Form::hidden('customer_id', null, ['id'=>'customer_id']); ?>

<?php echo Form::hidden('is_by_item_sale', $is_by_item_sale, ['id'=>'is_by_item_sale' , 'value'=>'is_by_item_sale']); ?>

<?php echo Form::hidden('is_tax_inclusive', null); ?> 
<?php echo Form::hidden('membership_product_id', $membership_product_id, ['id'=>'membership_product_id' , 'value'=>'membership_product_id']); ?>

<?php echo Form::hidden('package_pax_number', null); ?> 
<?php echo Form::hidden('package_min_age', null); ?> 
<?php echo Form::hidden('package_max_age', null); ?> 
