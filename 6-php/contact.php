<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/coliff/bootstrap-rfs/bootstrap-rfs.css">
    
    <!-- Imported scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
	<title>Contact Form</title>
	
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
	</script>
</head>
<body>	
	<div class="container">
	<?php if (sizeof($_POST)==0) { ?>
		<h1>Contact Form</h1>
		<form method="POST" action="contact.php" class="needs-validation" novalidate>
			<div class="form-group">
				<label for="txtName">Name</label>
				<input id="txtName" name="name" class="form-control" type="text" placeholder="Enter your name" required />
				<div class="valid-feedback">
					Looks good!
				</div>
				<div class="invalid-feedback">
					Please enter your name - I need to know it so I can reply
				</div>
			</div>
			<div class="form-group">
				<label for="txtEmail">Email</label>
				<input id="txtEmail" name="email" class="form-control" type="email" placeholder="Enter your email address" required />
				<div class="valid-feedback">
					Looks good!
				</div>
				<div class="invalid-feedback">
					Please enter your email address and make sure it's valid! I need to know it so I can reply
				</div>
			</div>
			<div class="form-group">
				<label for="taMessage">Enter your message</label>
				<textarea id="taMessage" name="message" class="form-control" placeholder="Enter a message" required></textarea>
				<div class="valid-feedback">
					Looks good!
				</div>
				<div class="invalid-feedback">
					Please enter a message - you must have something to say?
				</div>
			</div>
			<button type="submit" class="btn btn-primary">Send Message</button>
		</form>
	
	<?php } else {
		$emailTo = "andy.cork@gmail.com";
		$subject = "[Contact Form] Vespasianvs.co.uk from ".$_POST["name"];
		$message = $_POST["message"]." from ".$_POST["name"];
		$headers = "From: andy@vespasianvs.co.uk;Reply-To: ".$_POST["email"];
		
		if (mail($emailTo, $subject, $message, $headers)) echo '<div class="alert alert-success" role="alert">Email sent successfully!</div>';
		else '<div class="alert alert-danger" role="alert">Failed to send email!</div>';
	}
	?>
			
	</div>
</body>