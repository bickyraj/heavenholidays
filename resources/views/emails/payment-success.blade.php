<!DOCTYPE html>
<html>
<head>
	<title>Nepal Trekking Planner</title>
</head>
<body>
	<h3>Payment</h3>

    The payment has been done successfully from.

    <ul>
        <li>Trip Name: {{ $body['trip_name'] }}</li>
        <li>Full Name: {{ $body['full_name'] }}</li>
        <li>Email: {{ $body['email'] }}</li>
    </ul>
</body>
</html>
