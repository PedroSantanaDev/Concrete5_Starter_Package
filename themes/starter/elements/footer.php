<?php defined('C5_EXECUTE') or die("Access Denied.");?>
</div>
<footer id="footer-theme">    
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <?php
                    $a = new GlobalArea('Footer Left');
                    $a->display();
                    ?>
                </div>
                <div class="col-sm-4">
                    <?php
                    $a = new GlobalArea('Footer Centre');
                    $a->display();
                    ?>
                </div>
                <div class="col-sm-4">
                    <?php
                    $a = new GlobalArea('Footer Right');
                    $a->display();
                    ?>
                </div>
            </div>
        </div>
    </section>
</footer>


<?php $this->inc('elements/footer_bottom.php');?>
