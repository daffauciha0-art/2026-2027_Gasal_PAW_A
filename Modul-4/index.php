<?php

if($_SERVER('REQUEST_METHOD') == "POST") {
	if(empty($_POST["input_username"])) {
		echo "<p style='color: red;'>Username tidak boleh kosong</p>"
	} else {
		echo $_POST["input_username"];
	}

	echo $_POST["input_name"];
	echo $_POST["input_email"];
	echo $_POST["input_password"];
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Form PHP</title>
</head>
<body>
	<h1>Form PHP</h1>
		<form action="" method="post">
			<label>Name</label>
			<input type="text" name="input_name">
			<br><br>
			<label>Email</label>
			<input type="text" name="input_email">
			<br><br>
			<label>Username</label>
			<input type="text" name="input_username">
			<br><br>
			<label>Password</label>
			<input type="password" name="input_password">
			<br><br>
			<button type="submit">Submit</button>
		</form>
</body>
</html>