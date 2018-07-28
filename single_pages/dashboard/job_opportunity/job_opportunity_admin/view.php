<?php
/**
* [$token concrete5 token object]
* @var object
*/
$token = \Core::make("token");
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
$session = new SymfonySession();

foreach ($session->getFlashBag()->get('error', array()) as $e) {
  $error .= '<li>'.$e.'</li>';
}

foreach ($session->getFlashBag()->get('success', array()) as $s) {
  $success .= $s;
}

$categories = Concrete\Package\JobOpportunity\Src\Entity\GrandErieJobOpportunityCategory::getCategoryArray();
?>

<div class="container job_container">
  <div class="row">
    <div class="well col-xs-12">
      <form method="get" id="search-filter" class="form-inline column-sort">
        <!--<?php //$token; ?>-->
        <?= $form->label('qtitle', 'Title: ') ?>
        <?= $form->text('qtitle', $qtitle); ?>
        <span class='field-spacer'></span>
        <?= $form->label('qcategory', ' Category: ') ?>
        <?= $form->select('qcategory', $categories, $qcategory); ?>
        <span class='field-spacer'></span>
        <?= $form->submit('Search', 'Search'); ?>
        <a href="/dashboard/job_opportunity/job_opportunity_admin" class="btn btn-default">Clear Search</a>
        <a href="<?= $this->action('edit');?>" class="btn btn-info pull-right">Create New Job</a>
        <?= $form->hidden('sort', $sort, array('id' => 'sort')); ?>
      </form>
    </div>
    <div class="col-md-12">
      <table class="table table-hover">
        <thead>
          <tr class="bg-default">
            <th><button class="col-sort button-link" value="job_title">Job Title</button></th>
            <th><button class="col-sort button-link" value="job_category">Job Category</button></th>
            <th><button class="col-sort button-link" value="job_posted_date">Posted On</button></th>
            <th><button class="col-sort button-link" value="job_expiry_date">Expires On</button></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if($lists) :?>
            <?php foreach($lists as $ll): ?>
              <tr>
                <td><?= $ll->job_title; ?></td>
                <td><?= $ll->getCategory() ?></td>
                <td><?php if($ll->job_posted_date) echo $ll->job_posted_date->format('M j, Y g:i A');?></td>
                <td><?php if($ll->job_expiry_date) echo $ll->job_expiry_date->format('M j, Y g:i A');?></td>
                <td>
                  <a href="<?= $this->action('edit', $ll->id);?>" class="btn btn-info btn-sm"><i class="icon-pencil"></i>Edit</a>
                  <a href="<?= $this->action('delete', $ll->id);?>" class="btn btn-danger btn-sm action-delete">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      <?php if($pagination) : ?>
        <div class="ccm-search-results-pagination">
          <?php
          if ($pagination->haveToPaginate()) {
            echo $pagination->renderDefaultView();
          }
          ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
