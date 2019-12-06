<?php
	session_start();
	
	if(!isset($_SESSION["user"])) header("Location: index.php");
  
	$dbLink = mysqli_connect("DB_HOST", "DB_NAME", "DB_PASSWORD", "DB_NAME");
		
	if (mysqli_error($dbLink)) die("Could not connect to the database");
	
	$qGet = "SELECT Thoughts FROM users WHERE Email='".$_SESSION["user"]."'";
	
	if($result = mysqli_query($dbLink, $qGet)) {
		if ($row = mysqli_fetch_array($result))
			$sThoughts = $row["Thoughts"];
		else die("Could not get data from database");
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
	function textChange(e) {
		e.preventDefault();
		$("#btnLogout").addClass("disabled");
		
		var sText = $(e.target).val();
		
		var oPost = $.post(
						"updatedb.php", 
						{ thoughts: sText }
					)
					.done(function (data) { 
						$("#btnLogout").removeClass("disabled");
						$("#divSaved").text("(...saved...)");
						$("#divSaved").removeClass("d-none");
						$("#divSaved").addClass("alert-success");
						$("#divSaved").removeClass("alert-danger");
					})
					.fail(function (data) {
						$("#modalError").modal();
						$("#divSaved").text("(...error saving...)");
						$("#divSaved").removeClass("alert-success");
						$("#divSaved").addClass("alert-danger");
					})
	}
	</script>
	
	<title>My Secret Diary</title>
	
</head>
<body class="text-light">
<div class="container vw-100 vh-100 position-relative">
	<header id="hdrWrite" class="row">
		<div class="col-10">Enter your most secret thoughts - and I will store them for you!</div>
		<div class="col-2 text-right pr-4">
			<form method="POST" action="index.php">
				<input type="hidden" name="action" value="LOGOUT">
				<button type="submit" class="btn btn-success" id="btnLogout">Logout</button>
			</form>
	</header>
	<textarea id="txtDiary" oninput="textChange(event)"><?=$sThoughts?></textarea>
	<div id="divSaved" class="alert alert-success small d-none"></div>
	<div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="modalError" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content text-dark">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Database Error</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					Your most secret thoughts couldn't be saved to the database.
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>