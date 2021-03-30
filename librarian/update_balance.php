<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_librarian.php";
	require "header_librarian.php";
?>

<html>
	<head>
		<title>Zmień saldo</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css" />
		<link rel="stylesheet" href="css/update_balance_style.css">
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
			<legend>Podaj szczegóły</legend>
			
				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
				
				<div class="icon">
					<input class="m-user" type='text' name='m_user' id="m_user" placeholder="Nazwa użytkownika" required />
				</div>
				
				<div class="icon">
					<input class="m-balance" type="number" name="m_balance" placeholder="Kwota do dodania" required />
				</div>
				
				<input type="submit" name="m_add" value="Dodaj" />
		</form>
	</body>
	
	<?php
		if(isset($_POST['m_add']))
		{
			$query = $con->prepare("SELECT username FROM member WHERE username = ?;");
			$query->bind_param("s", $_POST['m_user']);
			$query->execute();
			if(mysqli_num_rows($query->get_result()) != 1)
				echo error_with_field("Niepoprawna nazwa użytkownika", "m_user");
			else
			{
				$query = $con->prepare("UPDATE member SET balance = balance + ? WHERE username = ?;");
				$query->bind_param("ds", $_POST['m_balance'], $_POST['m_user']);
				if(!$query->execute())
					die(error_without_field("Nie udało się zmienić salda"));
				echo success("Saldo zostało pomyślnie zaktualizowane");
			}
		}
	?>
</html>