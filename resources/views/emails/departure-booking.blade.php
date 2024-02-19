<!DOCTYPE html>
<html>
<head>
	<title>Namaste Nepal</title>
</head>
<body>
	<h3>Departure Booking</h3>

	Trip: {{ $body['trip_name'] }} <br>
	From: {{ $body['from_date'] }} <br>
	To: {{ $body['to_date'] }} <br>
	Status: {{ $body['statusInfo'] }} <br>
	Booked By: {{ $body['first_name'] . " " . $body['middle_name'] . " " . $body['last_name'] }} <br>
	Country: {{ $body['country'] }} <br>
	Email: {{ $body['email'] }} <br>
	Contact No: {{ $body['contact_no'] }} <br>
	Gender: {{ $body['gender'] }} <br>
	Date of Birth: {{ $body['dob'] }} <br>
	Mailing Address: {{ $body['mailing_address'] }} <br>

	<h3>Trip Details</h3>
	Passport No.: {{ $body['passport_no'] }} <br>
	Place of Issue: {{ $body['place_of_issue'] }} <br>
	Issue Date: {{ $body['issue_date'] }} <br>
	Expiry Date: {{ $body['expiry_date'] }} <br>
	No. of Travellers: {{ $body['no_of_travellers'] }} <br>
	Emergency Contact: {{ $body['emergency_contact'] }} <br>

	<h4>Traveller Information</h4>
	IP Address: {{ $body['ip_address'] }}
</body>
</html>
