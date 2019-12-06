<?php
	$allowed_host = 'vespasianvs.co.uk';
	$host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);

	if(substr($host, 0 - strlen($allowed_host)) != $allowed_host) {
		http_response_code(412);
	}

	$from = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
	$name = $_POST["name"];
	$body = $_POST["message"];
	
	$headers = 'From: Vespasianvs no-reply@vespasianvs.co.uk' . "\r\n" ;
    $headers .='Reply-To: '. $from . "\r\n" ;
    $headers .='X-Mailer: PHP/' . phpversion();
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	
	$response = array();

	if ($from) $response["success"] = mail("andy.cork@gmail.com", "Portfolio query from ".$name, $body, $headers);
	else $response["success"]=false;
	
	$data = JSON_encode($response); // json string

	if(array_key_exists('callback', $_POST)){

		header('Content-Type: text/javascript; charset=utf8');
		header('Access-Control-Allow-Origin: http://www.vespasianvs.co.uk/');
		header('Access-Control-Max-Age: 3628800');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

		$callback = $_POST['callback'];
		echo $callback.'('.$data.');';

	}
	else {
		// normal JSON string
		header('Content-Type: application/json; charset=utf8');

		echo $data;
	}
?>