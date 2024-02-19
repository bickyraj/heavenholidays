<!DOCTYPE html>
<html>

<head>
    <title>Namaste Nepal</title>
</head>

<body>
    <h3>Booking</h3>

    Trip: {{ $body['trip_name'] }} <br>
    Booked By: {{ $body['first_name'] . ' ' . $body['middle_name'] . ' ' . $body['last_name'] }} {{ $body['fullname'] }} <br>
    @if ($body['amount'])
        Amount: {{ $body['amount'] }} <br>
    @endif
    @if ($body['price'])
        Price: {{ $body['price'] }} <br>
    @endif
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
    Preferred Departure Date: {{ $body['preferred_departure_date'] }} <br>
    Emergency Contact: {{ $body['emergency_contact'] }} <br>

    <h4>Traveller Information</h4>
    IP Address: {{ $body['ip_address'] }}
</body>

</html>
