<?php

$fr = fopen('input', 'r');

fscanf($fr, "Time: %[^\n]", $timesString);
fscanf($fr, "Distance: %[^\n]", $distanceString);

$races[] = [
    'time' => (int) str_replace(' ', '', $timesString),
    'distance' => (int) str_replace(' ', '', $distanceString),
];

$result = 1;

foreach ($races as $race) {
    $distances = [];

    for ($speed=0; $speed <= $race['time']; $speed++) {
        $distances[$speed] = $speed * ($race['time'] - $speed);
    }

    $distance = $race['distance'];

    $beatWin = count(
        array_filter(
            $distances,
            function ($item) use ($distance) {
                return $item > $distance;
            }
        )
    );

    $result *= $beatWin;
}

echo $result;
