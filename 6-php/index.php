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
	
	<title>Weather Forecast</title>
	
	<style type="text/css">
		.stroked-text {
            text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;
        }
	</style>
	
	<?php
	$responseText = '';
	$weatherImage = 'default';
	$textColor = 'text-dark';
	
	if (sizeof($_POST)>0) {
		$weatherKeyWords = array("freeze", "freezing", "dry", "snow", "rain", "foggy");
		$city = str_replace(" ", "-", $_POST["city"]);
		$dom = new DOMDocument();
		$success = $dom->loadHTMLFile('https://www.weather-forecast.com/locations/'.$city.'/forecasts/latest');
		
		if (!$success) $responseText = "<div class='alert alert-danger' role='alert'>Invalid city entered</div>";
		else {
			$xpath = new DOMXPath($dom);
			$result = $xpath->query('//table[@class="b-forecast__table js-forecast-table"]/thead/tr/td[1]');
			
			if (!$success) $responseText = "<div class='alert alert-danger' role='alert'>Failed to find weather</div>";
			else {
				$forecast = $result->item(0)->childNodes->item(1)->nodeValue;
				
				foreach($weatherKeyWords as $key => $value) {
					if (stripos($forecast, $value)>0) {
						$weatherImage = $value;
						break;
					}
				}
					
				$responseText = "<div class='alert alert-success' role='alert'>";
				$responseText = $responseText."<h4>".$result->item(0)->childNodes->item(0)->nodeValue."</h4>";
				$responseText = $responseText."<p>".$forecast."</p>";
				$responseText = $responseText."</div>";
			}
		}
	}
	?>
	
</head>
<body style="background-image:url('images/<?=$weatherImage?>.jpg'); background-size:cover;">	
	<div class="container vh-100 mx-auto">
		<div class="row align-items-center h-100">
			<div class="col-10 mx-auto">
				<div class="row justify-content-center text-light stroked-text">
					<h1>What's The Weather?</h1>
				</div>
				<div class="row justify-content-center text-light stroked-text">
					<form method="POST" action="index.php" class="container-fluid">
						<div class="row justify-content-center">
							<label for="txtCity">Enter the name of a city</label>
						</div>
						<div class="row">
							<input type="text" id="txtCity" name="city" class="form-control" placeholder="Enter a city name" required />
						</div>
						<div class="row justify-content-center pt-3 pb-5">
							<button class="btn btn-primary" type="submit">Check the weather!</button>
						</div>
					</form>
				</div>
				<div class="row justify-content-center">
					<?= $responseText ?>
				</div>
			</div>
		</div>
	</div>
</body>