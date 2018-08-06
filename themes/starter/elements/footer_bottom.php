<?php defined('C5_EXECUTE') or die("Access Denied.");?>


<?php

$live = false;

if ($live): ?>
<!-- Production version, optimized for size and speed -->
<script src="https://cdn.jsdelivr.net/npm/vue"></script>

<?php else: ?>
<!-- Development version, includes helpful console warnings -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

<?php endif;?>

<?php View::element('footer_required');?>

</body>
</html>
