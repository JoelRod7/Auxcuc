<?php
$ch = curl_init();
$headers = array(
    "Authorization: JWT eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjo0LCJ1c2VybmFtZSI6InBydWViYTIwMjJAY3VjLmVkdS5jbyIsImV4cCI6MTY0OTQ1MzA1NCwiY29ycmVvIjoicHJ1ZWJhMjAyMkBjdWMuZWR1LmNvIn0.MAoFJE2SBgHvp9BS9fyBmb2gZzD0BHGPiyKoAo_uYAQ",
    "Content-Type: application/json",
);

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

curl_setopt($ch, CURLOPT_URL, "http://consultas.cuc.edu.co/api/v1.0/profesores");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);

echo $res;
curl_close($ch);
?>