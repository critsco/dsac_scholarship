<?php
// Establish a connection to the database
include "db_conn.php";

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a match is found, log in the user
    if ($result->num_rows > 0) {
        // Set the session variable
        $_SESSION["username"] = $username;

        // Redirect to admin.php
        header("Location: admin2.php");
        exit(); // Make sure to stop the script after the redirection
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!doctype html>
<html lang="en">
  <head>
  	<title>FSUU Scholarships Portal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/style.css">

	<style>
		.logo-container a {
  		display: flex;
  		flex-direction: column;
  		align-items: center;
  		text-decoration: none;
		}

		.logo-container img {
  		max-width: 100px; /* Set the max width for the logo */
  		height: auto; /* Maintain aspect ratio */
		}
	</style>

	</head>
	<body>
	<section class="ftco-section" style="background-color: #1015A3;">
		<div class="container">
			<div class="row justify-content-center" style="margin-top: -50px;">
				<div class="col-md-7 col-lg-5">
					<div class="wrap">
						<div class="img" style="background-image: url(images/urios3.jpg);"></div>
						<div class="login-wrap p-4 p-md-4" style="background-color: #fffffe;">
			      	<div class="d-flex">
			      		<div class="w-100">
						  <h3 class="logo-container"><a href="index.php"><img src="assets/img/logo.png" alt="FSUU Logo"> <span>FSUU Scholarships Portal</span></a></h3>
			      			<h3 class="mb-4">Admin Login</h3>
			      		</div>
			      	</div>
							<form action="" method="post" class="signin-form">
			      		<div class="form-group mt-3">
			      			<input type="text" class="form-control" name="username" required>
			      			<label class="form-control-placeholder" for="username">Username</label>
			      		</div>
		            <div class="form-group">
		              <input id="password-field" type="password" class="form-control" name="password" required>
		              <label class="form-control-placeholder" for="password">Password</label>
		              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
		            </div>
		            <div class="form-group">
		            	<button type="submit" class="form-control btn btn-primary rounded submit px-3">LOGIN</button>
		            </div>
		          </form>
		        </div>
		      </div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

	</body>
</html>

