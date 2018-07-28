<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); ?>
	
<div id="subpage" class="container">
	<div class="row">
        <div id="main" class="col-md-12">
            <?php
            $a = new Area('Main');
            $a -> display($c);
            ?>		
        </div>
     </div>
</div>

<?php  $this->inc('elements/footer.php'); ?>