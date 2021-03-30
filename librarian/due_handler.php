<?php
	require "../db_connect.php";
	require "verify_librarian.php";
	require "header_librarian.php";
?>

<html>
	<head>
		<title>Przypomnienia na dzisiaj</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
	</head>
	<body>
	
	<?php
		$query = "CALL generate_due_list();";
		$result = mysqli_query($con, $query);
		$rows = mysqli_num_rows($result);
		
		if($rows > 0)
		{
			$successfulEmails = 0;
			$idArray;
			$header = 'From: <noreply@library.com>' . "\r\n";
			$subject = "Prosimy o zwrócenie ksiąki dzisiaj";
			$query = "";
		
			for($i=0; $i<$rows; $i++)
			{
				$row = mysqli_fetch_array($result);
				$to = $row[1];
				$message = "Przypomnienie o zwrocie książki '".$row[3]."' o numerze ISBN ".$row[2]." do biblioteki.";
				if(mail($to, $subject, $message, $header) != FALSE)
				{
					$idArray[$i] = $row[0];
					$successfulEmails++;
				}
			}
			
			mysqli_next_result($con);
			
			for($i=0; $i<$rows; $i++)
			{
				$query = $con->prepare("UPDATE book_issue_log SET last_reminded = CURRENT_DATE WHERE issue_id = ?;");
				$query->bind_param("d", $idArray[$i]);
				$query->execute();
				$query->get_result();
			}
			
			if($successfulEmails > 0)
				echo "<h2 align='center'>Pomyślnie powiadomiono ".$successfulEmails." użytkownika.</h2>";
			else
				echo "Nie udało poinformować się użytkownika.";
		}
		else
			echo "<h2 align='center'>Brak przypomnień.</h2>";
	?>
	</body>
</html>