<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Email Receipt</title>

		<!-- Bootstrap CSS -->
		{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"> --}}

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style type="text/css">
			html {
				height: 100%;
			}
			body {
				height: 100%;
				background-color: #cccccc;
			}
			.container {
				position: relative;
				background-color: #ffffff;
				max-width: 700px;
				height: 100%;
				padding: 20px;
				margin: 0px auto;
			}
			.header {
				position: relative;
				height: 100px;
				background-color: #da262e;
			}
			.header .receipt {
				position: absolute;
				right: 10px;
				bottom: 10px;
				font-size: 20px;
				font-weight: 200;
				color: #ffffff;
			}
			h1 {
				font-size: 70px;
				line-height: 50px;
				font-weight: 200;
				margin-top: 50px;
				margin-bottom: 50px;
			}
			h3 strong {
				font-size: 45px;
				line-height: 35px;
				font-weight: 500;
			}
			.img-responsive {
				max-width: 100%;
			}
			.text-uppercase {
				text-transform: uppercase;
			}
			.box {
				background-color: #ececec;
				padding: 20px;
				width: 90%;
				margin: 50px auto 0px;
			}
			hr {
				border-color: #d7d7d7;
			}
			.footer-info {
				position: absolute;
				bottom: 20px;
				left: 20px;
				right: 20px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<center>
				<div class="header">
					<span class="receipt text-uppercase">Receipt</span>
				</div>
				<h1 class="text-uppercase">Thank You</h1>
				@php $setting = new App\Setting(); @endphp
				<h3><strong>RM{{$setting->getDouble($user['amount'])}}</strong></h3>
				<h3><strong class="text-uppercase">Paid</strong></h3>
			</center>
			<div class="box">
				<p>Your bill payment via online payment is successful.</p>
				<hr>
				<p><strong>Receipt Number</strong> : {{$user['document_no']}}</p>
			</div>
			<small class="footer-info">IMPORTANT: Please be cautioned that SUNWAY MEDICAL CENTRE will not be responsible for any viruses or other interfering or damaging elements which may be contained in this e-mail (including any attachments hereto).</small>
		</div>

		<!-- jQuery -->
		<script src="//code.jquery.com/jquery.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
 		<script src="Hello World"></script>
	</body>
</html>