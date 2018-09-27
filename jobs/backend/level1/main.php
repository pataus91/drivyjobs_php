<?php

$json = file_get_contents('./data/input.json');
$data = json_decode($json, true);
$cars = $data['cars'];
$rentals = $data['rentals'];
$solutionFile = './data/output.json';

function calculateNDays ($startDate, $endDate) {
    $startD = new DateTime($startDate);
    $endD = new DateTime($endDate);
    $nDays = $startD->diff($endD)->d + 1; 
    return $nDays;
}

function calculatePrice($nDays, $dailyPrice, $distance, $kmPrice){
    $price = $nDays * $dailyPrice + $distance * $kmPrice;
    return $price;
}

foreach($rentals as $key => $rental) { 
    foreach($cars as $key => $car) {
        if($rental['car_id'] === $car['id']) {
            $nDays = calculateNDays($rental['start_date'], $rental['end_date']);
            $price = calculatePrice($nDays, $car['price_per_day'], $rental['distance'], $car['price_per_km']);
            $arrayResult[] = [
                "id" => $rental['id'],    
                "price" => $price
            ];  
       } 
   } 
   $arrayForJson = ['rentals' => $arrayResult];
} 

$solution = json_encode($arrayForJson, JSON_PRETTY_PRINT);
file_put_contents($solutionFile, $solution);
echo $solution;