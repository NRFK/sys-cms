<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php if ($page) { ?>
		<title>Admin | <?php echo ucfirst($page); ?></title>
		<?php } else { ?>
		<title>Admin | Dashboard</title>
		<?php } ?>
		<!-- Bootswatch Darkly Theme -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.3.7/darkly/bootstrap.min.css" integrity="sha512-p5mHOC7N2BAy1SdoU/f2XD4t7EM05/5b1QowPXdyvqrSFsWQl3HVqY60hvvnOcMVBXBwr3ysopvGgxqbaovv/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="/platform/templates/backend/default/style.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	</head>
	<body>
		<div class="container mt-5">
			<div id="logoId" class="">
				Admin
			</div>