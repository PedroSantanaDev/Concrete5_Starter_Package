<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<?php if ($jobs): ?>
	<br>
	<div class="panel-group" id="accordion">
		<?php
		$category_heading = '';
		?>
		<!--<h2 class="job-post-heading"><?= $category_heading ?></h2>-->
		<?php foreach ($jobs as $job): ?>
			<?php
			$expiry = '';
			if ($job['job_expiry_date']) {
				$expiry = date('M d, Y h:i a', strtotime($job['job_expiry_date']));
			}

			if ($job['category_desc'] != $category_heading)
			{
				echo '<br>';
				// add new headings
				$category_heading = $job['category_desc'];
				echo '<h2 class="job-post-heading">'.$category_heading.'</h2>';
			}
			?>
			<div class="panel panel-default job-posting-wrapper">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#<?= $job['id']?>">
							<?php  echo  '<span class="job-title">'.h($job['job_title']).'</span>';
							echo ' <span class="job-expiry-date"> Expiry: '.$expiry.' </span>'; ?>
							<span class="glyphicon glyphicon-plus pull-right"></span>
						</a>
					</h4>
				</div>
				<div id="<?= $job['id'] ?>" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="col-lg-10">
							<?= $job['job_description']; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
<?php else: ?>
	<p>There are currently no job opportunities</p>
<?php endif ?>
