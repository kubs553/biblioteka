<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "../verify_logged_out.php";
	require "../header.php";
?>

<html>
	<head>
		<title>Login - bibliotekarz</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css">
		<link rel="stylesheet" type="text/css" href="css/index_style.css">
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
		
		<legend>Login - bibliotekarz</legend>
		
			<div class="error-message" id="error-message">
				<p id="error"></p>
			</div>
			
			<div class="icon">
				<input class="l-user" type="text" name="l_user" placeholder="Nazwa użytkownika" required />
			</div>
			
			<div class="icon">
				<input class="l-pass" type="password" name="l_pass" placeholder="Hasło" required />
			</div>
			
			<input type="submit" value="Zaloguj się" name="l_login"/>
			
		</form>
	</body>
	
	<?php
		if(isset($_POST['l_login']))
		{
			$query = $con->prepare("SELECT id FROM librarian WHERE username = ? AND password = ?;");
			$query->bind_param("ss", $_POST['l_user'], sha1($_POST['l_pass']));
			$query->execute();
			if(mysqli_num_rows($query->get_result()) != 1)
				echo error_without_field("Nieprawidłowa nazwa lub hasło");
			else
			{
				$_SESSION['type'] = "librarian";
				$_SESSION['id'] = mysqli_fetch_array($result)[0];
				$_SESSION['username'] = $_POST['l_user'];
				header('Location: home.php');
			}
		}
	?>
	
</html>