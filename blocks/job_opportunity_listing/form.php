<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
  <?php  echo $form->label('displayCategory', t("Category to Display")); ?>
  <?php  echo isset($btFieldsRequired) && in_array('displayCategory', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
  <?php  echo $form->select($view->field('displayCategory'), $displayCategory_options, $displayCategory, array()); ?>
</div>
