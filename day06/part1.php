<?php

$fr = fopen('input', 'r');

fscanf($fr, "Time: %[^\n]", $timesString);
fscanf($fr, "Distance: %[^\n]", $distanceString);

$times = [];

if (false !== preg_match_all("~(\d+)~", $timesString, $match)) {
    $times = array_map(fn ($item) => (int) $item, $match[0]);
}

$distances = [];

if (false !== preg_match_all("~(\d+)~", $distanceString, $match)) {
    $distances = array_map(fn ($item) => (int) $item, $match[0]);
}

$races = array_map(fn ($time, $distance) => ['time' => $time, 'distance' => $distance], $times, $distances);

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
