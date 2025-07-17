<?php if ($_SESSION['success']) { ?>
<div class="alert alert-success"><?php echo $_SESSION['success']; ?></div>
<?php } ?>
<?php if($_SESSION['error']) { ?>
<div class="alert alert-danger"><b>Error:</b> <?php echo $_SESSION['error']; ?></div>
<?php } ?>

<div class="card text-white bg-dark mb-3" style="padding:20px;">
	<div class="card-body">
		<p class="card-text">Welcome to the website admin panel.</p>
	</div>
</div>