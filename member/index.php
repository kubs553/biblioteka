<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "../verify_logged_out.php";
	require "../header.php";
?>

<html>
	<head>
		<title>Logowanie użytkownika</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css">
		<link rel="stylesheet" type="text/css" href="css/index_style.css">
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
		
			<legend>Logowanie użytkownika</legend>
			
			<div class="error-message" id="error-message">
				<p id="error"></p>
			</div>
			
			<div class="icon">
				<input class="m-user" type="text" name="m_user" placeholder="Nazwa użytkownika" required />
			</div>
			
			<div class="icon">
				<input class="m-pass" type="password" name="m_pass" placeholder="Hasło" required />
			</div>
			
			<input type="submit" value="Zaloguj się" name="m_login" />
			
			<br /><br /><br /><br />
			
			<p align="center">Nie posiadasz konta?&nbsp;<a href="register.php">Zarejestruj się</a>
		</form>
	</body>
	
	<?php
		if(isset($_POST['m_login']))
		{
			$query = $con->prepare("SELECT id, balance FROM member WHERE username = ? AND password = ?;");
			$query->bind_param("ss", $_POST['m_user'], sha1($_POST['m_pass']));
			$query->execute();
			$result = $query->get_result();
			
			if(mysqli_num_rows($result) != 1)
				echo error_without_field("Nieporawidłowe hasło lub login.");
			else 
			{
				$resultRow = mysqli_fetch_array($result);
				$balance = $resultRow[1];
				if($balance < 0)
					echo error_without_field("Twoje konto jest zawieszone, skontaktuj się z bibliotekarzem.");
				else
				{
					$_SESSION['type'] = "member";
					$_SESSION['id'] = $resultRow[0];
					$_SESSION['username'] = $_POST['m_user'];
					header('Location: home.php');
				}
			}
		}
	?>
	
</html>