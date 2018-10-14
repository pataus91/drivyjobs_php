<?php

include ("mainlevel2.php");

function calculateFee($price, $nDays) {
    $commission = $price * 0.3;
    $insuranceFee = $commission * 0.5;
    $assistanceFee = 100 * $nDays;
    $drivyFee = $commission - $insuranceFee - $assistanceFee;
    $arrayCommission = [
        "insurance_fee" => $insuranceFee,
        "assistance_fee" => $assistanceFee,
        "drivy_fee" => $drivyFee,
        ];
    return $arrayCommission;
}

foreach($rentals as $key => $rental) { 
    foreach($cars as $key => $car) {
        if($rental['car_id'] === $car['id']) {
            $nDays = calculateNDays($rental['start_date'], $rental['end_date']);
            $durationPrice = calculateDurationPrice($nDays, $car['price_per_day']);
            $price = calculatePrice($durationPrice, $rental['distance'], $car['price_per_km']);
            $fee = calculateFee($price, $nDays);
            $arrayResult[] = [
                "id" => $rental['id'],    
                "price" => $price,
                'commission' => $fee,
            ];              
       } 
   } 
   $arrayForJson = ['rentals' => $arrayResult];
} 

$solution = json_encode($arrayForJson, JSON_PRETTY_PRINT);
file_put_contents($solutionFile, $solution);
echo $solution;