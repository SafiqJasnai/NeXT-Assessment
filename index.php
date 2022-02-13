<?php include('header.php'); ?>

<title>ISS Locator</title>

<body background="https://3dwarehouse.sketchup.com/warehouse/v1.0/publiccontent/bbbf2a17-987c-4fe6-b190-ac8fe0e235e9">

	<div class="wrapper">

		<h1><a href="index.php">International Space Station</a></h1>
		<br>

		<?php include ('iss-locator.php') ?>

		<?php include ('iss-live.php'); ?>
		<div class="row">
			<div style="width:45%">
				<?php include ('iss-inside.php'); ?>
			</div>
			<div style="text-align:left;width:45%">
				<?php include ('iss-twitter.php'); ?>
			</div>
		</div>
	</div>



</body>
