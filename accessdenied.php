<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="style/loginfailed.css">
	<title>Access Denied</title>
</head>

<body>
	<div class="container">
		<header>
			<i class="fa-solid fa-circle-exclamation" style="color: #ff0000; font-size: 50px;"></i>

		</header>
		<br>
		<section>
			<?php
			echo '<div class="main-message">Untuk mengakses aplikasi</div>';
			echo '<div class="sub-message">Anda harus login</div>';
			?>
		</section>
		<footer>
			<?php
			echo '<a href="login.php" class="retry-link"><b>ULANGI LAGI</b></a>';
			?>
		</footer>
	</div>
</body>

</html>