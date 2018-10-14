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

function calculateDurationPrice($nDays, $dailyPrice) {
	$fourDPrice =  $dailyPrice * 0.9;
	$tenDPrice = $dailyPrice * 0.7;
	$tenPlusDPrice = $dailyPrice * 0.5;
	
	if($nDays === 1) {
		$durationPrice = $dailyPrice;
	} elseif (1 < $nDays && $nDays < 5) {
		$durationPrice = ($nDays - 1) * $fourDPrice + $dailyPrice;
	} elseif (4 < $nDays && $nDays < 11) {
		$durationPrice = ($nDays - 4) * $tenDPrice + 3 * $fourDPrice + $dailyPrice;
	} elseif ($nDays > 10) {
		$durationPrice = ($nDays - 10) * $tenPlusDPrice + 6 * $tenDPrice + 3 * $fourDPrice + $dailyPrice;
	}
	return $durationPrice;
}

function calculatePrice($durationPrice, $distance, $kmPrice){
    $price = $durationPrice + $distance * $kmPrice;
    return $price;
}

foreach($rentals as $key => $rental) { 
    foreach($cars as $key => $car) {
        if($rental['car_id'] === $car['id']) {
            $nDays = calculateNDays($rental['start_date'], $rental['end_date']);
            $durationPrice = calculateDurationPrice($nDays, $car['price_per_day']);
            $price = calculatePrice($durationPrice, $rental['distance'], $car['price_per_km']);
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