<?php
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
$session = new SymfonySession();
foreach ($session->getFlashBag()->get('success', array()) as $s) {
  $success .= $s;
}

foreach ($session->getFlashBag()->get('error', array()) as $e) {
  $error .= '<li>'.$e.'</li>';
}

?>

<div class="container job_container">
  <div class="row">
    <div class="col-md12 edit-wrapper">
      <form class="dashboard-form" method="post">
        <?=  $form->hidden('entityID', $category->id); ?>
        <div class="form-row">
          <?= $form->label('category_desc', '<span class="required">*</span> Category:') ?>
          <?=  $form->text('category_desc', $category->category_desc, array('required'=>'required')); ?>
        </div>
        <div class="form-row-submit">
          <?= $form->submit('Submit', "submit") ?>
        </div>
      </form>
      <a href="/dashboard/job_opportunity/job_opportunity_category"><< Back</a>

    </div>
  </div>
</div>
