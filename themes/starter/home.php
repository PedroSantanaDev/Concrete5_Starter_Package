<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); ?>
<main>
    <?php
    $a = new Area('Banner');
    $a->enableGridContainer();
    $a->display($c);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" id="main">
                <?php
                $a = new Area('Main');
                $a -> display($c);
                ?>		
            </div>
        </div>
    </div>
</main>

<?php  $this->inc('elements/footer.php'); ?>