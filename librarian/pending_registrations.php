<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_librarian.php";
	require "header_librarian.php";
?>

<html>
	<head>
		<title>Oczekujące rejestracje</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/custom_checkbox_style.css">
		<link rel="stylesheet" type="text/css" href="css/pending_registrations_style.css">
	</head>
	<body>
		<?php
			$query = $con->prepare("SELECT username, name, email, balance FROM pending_registrations");
			$query->execute();
			$result = $query->get_result();
			$rows = mysqli_num_rows($result);
			if($rows == 0)
				echo "<h2 align='center'>Brak oczekujących rejestracji</h2>";
			else
			{
				echo "<form class='cd-form' method='POST' action='#'>";
				echo "<legend>Oczekujące rejestracje</legend>";
				echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
				echo "<table width='100%' cellpadding=10 cellspacing=10>
						<tr>
							<th></th>
							<th>Nazwa użytkownika<hr></th>
							<th>Imię<hr></th>
							<th>E-mail<hr></th>
							<th>Saldo<hr></th>
						</tr>";
				for($i=0; $i<$rows; $i++)
				{
					$row = mysqli_fetch_array($result);
					echo "<tr>";
					echo "<td>
							<label class='control control--checkbox'>
								<input type='checkbox' name='cb_".$i."' value='".$row[0]."' />
								<div class='control__indicator'></div>
							</label>
						</td>";
					$j;
					for($j=0; $j<3; $j++)
						echo "<td>".$row[$j]."</td>";
					echo "<td>$".$row[$j]."</td>";
					echo "</tr>";
				}
				echo "</table><br /><br />";
				echo "<div style='float: right;'>";
				echo "<input type='submit' value='Usuń wybranego użytkownika' name='l_delete' />&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "<input type='submit' value='Dodaj użytkownika' name='l_confirm' />";
				echo "</div>";
				echo "</form>";
			}
			
			$header = 'From: <noreply@library.com>' . "\r\n";
			
			if(isset($_POST['l_confirm']))
			{
				$members = 0;
				for($i=0; $i<$rows; $i++)
				{
					if(isset($_POST['cb_'.$i]))
					{
						$username =  $_POST['cb_'.$i];
						$query = $con->prepare("SELECT * FROM pending_registrations WHERE username = ?;");
						$query->bind_param("s", $username);
						$query->execute();
						$row = mysqli_fetch_array($query->get_result());
						
						$query = $con->prepare("INSERT INTO member(username, password, name, email, balance) VALUES(?, ?, ?, ?, ?);");
						$query->bind_param("ssssd", $username, $row[1], $row[2], $row[3], $row[4]);
						if(!$query->execute())
							die(error_without_field("Wprowadzanie danych się nie powiodło."));
						$members++;
						
						$to = $row[3];
						$subject = "Zaakceptowano członkowstwo.";
						$message = "Twoje członkowstwo zostało zaakceptowane. Możesz teraz wypożyczać książki.";
						mail($to, $subject, $message, $header);
					}
				}
				if($members > 0)
					echo success("Pomyślnie dodano ".$members." użytkownika.");
				else
					echo error_without_field("Nie wybrano użytkownika");
			}
			
			if(isset($_POST['l_delete']))
			{
				$requests = 0;
				for($i=0; $i<$rows; $i++)
				{
					if(isset($_POST['cb_'.$i]))
					{
						$username =  $_POST['cb_'.$i];
						$query = $con->prepare("SELECT email FROM pending_registrations WHERE username = ?;");
						$query->bind_param("s", $username);
						$query->execute();
						$email = mysqli_fetch_array($query->get_result())[0];
						
						$query = $con->prepare("DELETE FROM pending_registrations WHERE username = ?;");
						$query->bind_param("s", $username);
						if(!$query->execute())
							die(error_without_field("Nie udało się usunąć użytkownika."));
						$requests++;
						
						$to = $email;
						$subject = "Odrzucono członkowstwo.";
						$message = "Twoje członkowstwo zostało odrzucone. Skontaktuj się z bibliotekarzem.";
						mail($to, $subject, $message, $header);
					}
				}
				if($requests > 0)
					echo success("Pomyślnie usunięto ".$requests." użytkownika.");
				else
					echo error_without_field("Nie wybrano użytkownika.");
			}
		?>
	</body>
</html>