<!DOCTYPE html>
<html>
<head>
	<title>Namaste Nepal</title>
</head>
<body>
	<h3>Enquiry</h3>

	Name: {{ $body['name'] ?? '' }} <br>
	Email: {{ $body['email'] ?? '' }} <br>
	Country: {{ $body['country'] ?? '' }} <br>
	Phone No: {{ $body['phone'] ?? '' }} <br>
	Message: {{ $body['message'] ?? '' }} <br>

	<h4>Traveller Information</h4>
	IP Address: {{ $body['ip_address'] }}
</body>
</html>
