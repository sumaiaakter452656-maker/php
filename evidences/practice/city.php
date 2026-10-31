<?php
$countries = array(
    "Bangladesh" => "Dhaka",
    "India"      => "New Delhi",
    "Japan"      => "Tokyo",
    "China"      => "Beijing",
    "Australia"  => "Canberra"
);

ksort($countries);

foreach ($countries as $country => $capital) {
    echo "Country: $country, Capital: $capital <br>";
}
?>