<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "../header.php";
?>

<html>
	<head>
		<title>Rejestracja</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css">
		<link rel="stylesheet" href="css/register_style.css">
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
			<legend>Podaj szczegóły</legend>
			
				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
				
				<div class="icon">
					<input class="m-user" type="text" name="m_user" id="m_user" placeholder="Nazwa użytkownika" required />
				</div>
				
				<div class="icon">
					<input class="m-pass" type="password" name="m_pass" placeholder="Hasło" required />
				</div>
				
				<div class="icon">
					<input class="m-name" type="text" name="m_name" placeholder="Imię" required />
				</div>
				
				<div class="icon">
					<input class="m-email" type="email" name="m_email" id="m_email" placeholder="E-mail" required />
				</div>
				
				<div class="icon">
					<input class="m-balance" type="number" name="m_balance" id="m_balance" placeholder="Początkowe saldo" required />
				</div>
				
				<br />
				<input type="submit" name="m_register" value="Zarejestruj się" />
		</form>
	</body>
	
	<?php
		if(isset($_POST['m_register']))
		{
			if($_POST['m_balance'] < 500)
				echo error_with_field("Potrzebujesz minimum $500, aby się zarejestrować.", "m_balance");
			else
			{
				$query = $con->prepare("(SELECT username FROM member WHERE username = ?) UNION (SELECT username FROM pending_registrations WHERE username = ?);");
				$query->bind_param("ss", $_POST['m_user'], $_POST['m_user']);
				$query->execute();
				if(mysqli_num_rows($query->get_result()) != 0)
					echo error_with_field("Nazwa użytkownika jest już zajęta.", "m_user");
				else
				{
					$query = $con->prepare("(SELECT email FROM member WHERE email = ?) UNION (SELECT email FROM pending_registrations WHERE email = ?);");
					$query->bind_param("ss", $_POST['m_email'], $_POST['m_email']);
					$query->execute();
					if(mysqli_num_rows($query->get_result()) != 0)
						echo error_with_field("Ten adres e-mail jest już zajęty.", "m_email");
					else
					{
						$query = $con->prepare("INSERT INTO pending_registrations(username, password, name, email, balance) VALUES(?, ?, ?, ?, ?);");
						$query->bind_param("ssssd", $_POST['m_user'], sha1($_POST['m_pass']), $_POST['m_name'], $_POST['m_email'], $_POST['m_balance']);
						if($query->execute())
							echo success("Poprawnie zarejestrowano, zostaniesz poinformowany o potwierdzeniu rejestracji.");
						else
							echo error_without_field("Nie udało się zarejestrować. Spróbuj ponownie później.");
					}
				}
			}
		}
	?>
	
</html>