<?php
require_once 'connection.php';
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// CHECK IF USERNAME IS EMPTY
	if (empty(trim($_POST["username"]))) {
		$username_err = "Username can not blank";
	}
	else{
		$sql = "SELECT id FROM login WHERE username = ?";
		$stmt = mysqli_prepare($conn, $sql);
		if ($stmt) {
			mysqli_stmt_bind_param($stmt, "s", $param_username);

			// set the value of param username
			$param_username = trim($_POST['username']);

			// try to execute this statement
			if (mysqli_stmt_execute($stmt)) {
				mysqli_stmt_store_result($stmt);
				if (mysqli_stmt_num_rows($stmt) ==	1) {
					$username_err = "This username is already taken";
				}
				else{
					$username = trim($_POST['username']);
				}
			}
			else{
				echo "something went wrong";
			}
		}
	}
	mysqli_stmt_close($stmt);


	// check for password
	if (isset($_POST["password"])) {
		echo "there is something happened";
	}
	if(empty(trim($_POST["password"]))) {
		$password_err = "Password can not be blank";
	}
	elseif(strlen(trim($_POST["password"]))<5) {
		$password_err = "Password can not be less than 5";
	}
	else{
		$password = trim($_POST["password"]);
	}

	// check for confirm password
	if (trim($_POST["password"]) != trim($_POST["confirm_password"])) {
		$password_err = "Password should match";
	}

	// if there was no error go ahead and insert into the database
	if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
		$sql = "INSERT INTO login (username, password) VALUE (?,?)";
		$stmt = mysqli_prepare($conn, $sql);
		if ($stmt) {
			mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

			// set those parameters
			$param_username = $username;
			$param_password = password_hash($password, PASSWORD_DEFAULT);

			// Try to execute the cubrid_query
			if (mysqli_stmt_execute($stmt)) {
				header("location: index.php");
			}
			else{
				echo "something went wrong";
			}

		}
		mysqli_stmt_close($stmt);
	}
}
mysqli_close($conn);


?>





<!DOCTYPE html>
<html>
<head>
	<title>blood bank management system</title>

	<style>
		body{
			margin: 0;
		}
		.container{
			background: #5435;
			height: 100vh;
		}
		.row h1,p{
			background: red;
			padding: 10px;
			margin: 0;
			text-align: center;
			font-family: cursive;
			color: white;
		}
		.row p{


		}
		.col .row{
			text-align: center;
			height:80vh;
			background: pink;
		}
		.center{
			position: relative;
			top: 50%;
			transform: translateY(-50%);
		}
		.center label,input{
			font-size: 25px;
		}
		span{
			font-size: 25px;
		}

	</style>
</head>
<body>

	<div class="container">
		<div class="row">
			<h1>Blood Bank Management System</h1>
		</div>
		<div class="col">
			<div class="row">
				<form class="center" action="" method="post">
					<label>Username : -</label>
					<input type="text" name="username" placeholder="Enter Username" required><br><br>
					<label>Password : -</label>
					<input type="password" name="password" placeholder="Enter Password" required><br><br>
					<label>Confirm Password : -</label>
					<input type="password" name="confirm_password" placeholder="Enter Password" required><br><br>
					<input type="submit" value="Register" name="submit"><br><br>
					<span>Already Regitered?<a href="index.php">Register here</a></span>
				</form>
			</div>
		</div>
		<div class="row">
			<p>Copywrite MyProject</p>
		</div>
	</div>

</body>
</html>