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

?>
<script type="text/javascript" src="/packages/job_opportunity/js/job_opportunity.js"></script>

<div class="container job_container">
  <div class="row">
    <div class="well col-xs-12">
      <form method="get" id="job_search" class="form-inline column-sort">
        <!--<?php //$token; ?>-->
        <a href="<?= $this->action('edit');?>" class="btn btn-info pull-right">Create New Category</a>
        <?= $form->hidden('sort', $sort, array('id' => 'sort')); ?>
      </form>
    </div>
    <div class="col-md-12">
      <table class="table table-hover">
        <thead>
          <tr class="bg-default">
            <th><button class="col-sort button-link" value="job_title">Job Category</button></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if($lists) :?>
            <?php foreach($lists as $ll): ?>
              <tr>
                <td><?= $ll->category_desc; ?></td>
                <td>
                  <a href="<?= $this->action('edit', $ll->id);?>" class="btn btn-info btn-sm"><i class="icon-pencil"></i>Edit</a>
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
