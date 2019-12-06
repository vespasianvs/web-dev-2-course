<?php
	session_start();
	if (isset($_COOKIE["user"])) $_SESSION["user"] = $_COOKIE["user"];
	if (isset($_SESSION["user"])) header("Location: write_diary.php");
	
	$errorMsg = '';
	
	if (!empty($_POST) && isset($_POST["action"])) {
		$dbLink = mysqli_connect("DB_HOST", "DB_NAME", "DB_PASSWORD", "DB_NAME");
		
		if (mysqli_error($dbLink)) die("Could not connect to the database");
		
		
		if ($_POST["action"] == "SIGNUP") {
			$sEmail = mysqli_real_escape_string($dbLink, $_POST["newEmail"]);
			$sPassword = password_hash($_POST["newPassword"], PASSWORD_DEFAULT);
			
			$qCheck = "SELECT Email FROM users WHERE Email='".$sEmail."'";
			
			if($result = mysqli_query($dbLink, $qCheck)) {
				if (mysqli_num_rows($result)>0) $errorMsg = "That email address is already a member, were you trying to sign in?";
				else {
					$qInsert = "INSERT INTO users (`Email`, `Password`) VALUES ('".$sEmail."','".$sPassword."')";
					
					if (mysqli_query($dbLink, $qInsert)) {
						if ($_POST["keepLogged"]) setcookie("user", $sEmail, time()+60*60*24);
						$_SESSION["user"] = $sEmail;
						header("Location: write_diary.php");
					}
					else $errorMsg = "Could not add you to the database, please try again later";
				}
			}
			else $errorMsg = "Could not query the database";
		}
		elseif($_POST["action"] == "LOGIN") {
			$sEmail = mysqli_real_escape_string($dbLink, $_POST["email"]);
			
			$qCheck = "SELECT Email, Password FROM users WHERE Email='".$sEmail."'";
			
			if ($result = mysqli_query($dbLink, $qCheck)) {
				if ($row = mysqli_fetch_array($result)) {
					if (password_verify($_POST["password"], $row["Password"])) {
						if ($_POST["keepLogged"]) setcookie("user", $sEmail, time()+60*60*24);
						$_SESSION["user"] = $sEmail;
						header("Location: write_diary.php");
					}
					else $errorMsg = "Login error. Check your email address and password. Have you signed up?";
				}
				else {
					$errorMsg = "Login error. Check your email address and password. Have you signed up?";
				}
			}
			else $errorMsg = "Could not query the database";
		}
		elseif($_POST["action"] == "LOGOUT") {
			unset($_COOKIE["user"]);
			unset($_SESSION["user"]);
			setcookie("user", "", time()-60*60);
		}
		else $errorMsg = "Unknown Action sent by form";
	}

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/coliff/bootstrap-rfs/bootstrap-rfs.css"/>
	<link rel="stylesheet" href="css/styles.css"/>
    
    <!--Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script type="text/javascript">
	(function() {
	  'use strict';
	  window.addEventListener('load', function() {
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.getElementsByClassName('needs-validation');
		// Loop over them and prevent submission
		var validation = Array.prototype.filter.call(forms, function(form) {
		  form.addEventListener('submit', function(event) {
			if (form.checkValidity() === false) {
			  event.preventDefault();
			  event.stopPropagation();
			}
			form.classList.add('was-validated');
		  }, false);
		});
	  }, false);
	})();

	function toggleForm(btn) {
		if (btn.id == "btnTogSignUp") {
			$("#frmSignUp").removeClass("d-none");
			$("#frmLogin").addClass("d-none");
		}
		else {
			$("#frmSignUp").addClass("d-none");
			$("#frmLogin").removeClass("d-none");
		}	
	}
	</script>
	
	
	<title>My Secret Diary</title>
	
</head>
<body>
<div id="bgImage">
	<div class="container vh-100 mx-auto">
		<div id="divContent" class="row h-100 align-items-center">
			<div class="col-md-2"></div>
			<form id="frmLogin" method="POST" action="index.php" class="col-md-8 mx-auto needs-validation" novalidate>
				<h1>My Secret Diary</h1>
				<input type="hidden" name="action" value="LOGIN">
				<div class="form-group">
					<label for="txtEmail">Email</label>
					<input type="email" required id="txtEmail" name="email" placeholder="Enter your email address" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="txtPassword">Password</label>
					<input type="password" required id="txtPassword" name="password" class="form-control"/><br/>
				</div>
				<div class="form-group">
					<input type="checkbox" id="chkKeepLogged" name="keepLogged">
					<label for="chkKeepLogged">Keep me logged in. <small>(I accept the use of a cookie)</small></label>
				</div>
				<div class="form-row px-2">
					<button type="submit" id="btnSubmit" class="btn btn-primary col-2">Log In</button>
					<span class="text-right col-10">Don't have an account? <a href="#" id="btnTogSignUp" onclick="toggleForm(this)">Sign Up</a></span>
				</div>
				<?php 
				if (strLen($errorMsg) > 0) {
					echo "<div class='form-row p-2'><div class='alert alert-danger w-100'>".$errorMsg."</div></div>";
				}
			?>	
			</form>
			<form id="frmSignUp" method="POST" action="index.php" class="col-md-8 mx-auto d-none needs-validation" novalidate>
				<h1>My Secret Diary</h1>
				<input type="hidden" name="action" value="SIGNUP">
				<div class="form-group">
					<label for="txtNewEmail">Email</label>
					<input type="email" required id="txtNewEmail" name="newEmail" placeholder="Enter your email address" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="txtNewPassword">Password</label>
					<input type="password" required id="txtNewPassword" name="newPassword" class="form-control"/><br/>
				</div>
				<div class="form-group">
					<input type="checkbox" id="chkKeepLogged2" name="keepLogged">
					<label for="chkKeepLogged2">Keep me logged in. <small>(I accept the use of a cookie)</small></label>
				</div>
				<div class="form-row px-2">
					<button type="submit" id="btnSignUp" class="btn btn-primary col-2">Sign Up</button>
					<span class="text-right col-10">Have an account? <a href="#" id="btnTogLogin" onclick="toggleForm(this)">Sign In</a></span>
				</div>
			</form>
			<div class="col-md-2"></div>
		</div>
	</div>
</div>
<footer class="fixed-bottom text-light text-center">
	<div class="small">This website uses cookies to keep you logged in. That's it...nothing else.</div>
	<div class="small">Your secret thoughts are stored in plain text in a database. Be careful with what you disclose!</div>
</footer>
</body>
</html>