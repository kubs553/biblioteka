<?php
	require "../db_connect.php";
	require "verify_librarian.php";
	require "header_librarian.php";
?>

<html>
	<head>
		<title>Strona główna</title>
		<link rel="stylesheet" type="text/css" href="css/home_style.css" />
	</head>
	<body>
		<div id="allTheThings">
			<a href="pending_registrations.php">
				<input type="button" value="Oczekujące rejestracje" />
			</a><br />
			<a href="pending_book_requests.php">
				<input type="button" value="Oczekujące książki" />
			</a><br />
			<a href="insert_book.php">
				<input type="button" value="Dodaj książkę" />
			</a><br />
			<a href="update_copies.php">
				<input type="button" value="Zmień stan książek" />
			</a><br />
			<a href="update_balance.php">
				<input type="button" value="Zmień saldo użytkownika" />
			</a><br />
			<a href="due_handler.php">
				<input type="button" value="Przypomnienia na dzisiaj" />
			</a><br /><br />
		</div>
	</body>
</html>