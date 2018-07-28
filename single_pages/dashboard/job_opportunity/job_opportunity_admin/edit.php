<?php
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
$session = new SymfonySession();
foreach ($session->getFlashBag()->get('success', array()) as $s) {
  $success .= $s;
}

foreach ($session->getFlashBag()->get('error', array()) as $e) {
  $error .= '<li>'.$e.'</li>';
}

$categories = Concrete\Package\JobOpportunity\Src\Entity\GrandErieJobOpportunityCategory::getCategoryArray();

?>

<div class="container job_container">
  <div class="row">
    <div class="col-md12 edit-wrapper">
      <form class="dashboard-form" method="post">
        <?=  $form->hidden('entityID', $job->id); ?>
        <div class="form-row">
          <?= $form->label('job_title', '<span class="required">*</span> Job Title:') ?>
          <?=  $form->text('job_title', $job->job_title, array('required'=>'required')); ?>
        </div>
        <div class="form-row">
          <?= $form->label('job_description', '<span class="required">*</span> Job Description:') ?>
          <div class="editor-wrapper">
            <?php
            $editor = Core::make('editor');
            echo $editor->outputStandardEditor('job_description', $job->job_description);
            ?>
          </div>
        </div>
        <div class="form-row">
          <?= $form->label('job_category', 'Category:') ?>
          <?= $form->select('job_category', $categories, $job->job_category); ?>
        </div>
        <div class="form-row">
          <?= $form->label('job_active', 'Status:') ?>
          <?= $form->select('job_active', array(1=>'Active', 0=>'Inactive'), $job->job_active); ?>
        </div>
        <div class="form-row">
          <?= $form->label('job_posted_date', 'Job Posted Date:') ?>
          <?= $form->text('job_posted_date', ($job->job_posted_date)?$job->job_posted_date->format('m/d/Y h:i A'):'',
          array('class'=>"datetime-picker")) ?> <small><i>(mm/dd/yyyy hh:mm AM/PM)</i></small>
        </div>
        <div class="form-row">
          <?= $form->label('job_expiry_date', 'Job Expiry Date:') ?>
          <?= $form->text('job_expiry_date', ($job->job_expiry_date)?$job->job_expiry_date->format('m/d/Y h:i A'):'',
          array('class'=>"datetime-picker")); ?> <small><i>(mm/dd/yyyy hh:mm AM/PM)</i></small>
        </div>
        <div class="form-row-submit">
          <?= $form->submit('Submit', "submit") ?>
        </div>
      </form>
      <a href="/dashboard/job_opportunity/job_opportunity_admin"><< Back</a>

    </div>
  </div>
</div>
