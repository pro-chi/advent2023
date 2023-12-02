<?php

$gamesSum = 0;
$fr = fopen("input", 'r');

while (!feof($fr)) {
    [$game, $bag] = parseLine(fgets($fr));

    if (-1 === $game) {
        continue;
    }

    $few = getFew($bag);
    $power = array_reduce(
        $few,
        static function ($multiple, $item) {
            return $multiple *= $item;
        },
        1
    );

    printf("%d. game %s %d\n", $game, implode(', ', $few), $power);
    $gamesSum += $power;
}

fclose($fr);
echo "\nSum:" . $gamesSum;


/**
 * @param string $line
 *
 * @return array
 */
function parseLine(string $line): array
{
    if (empty($line)) {
        return [-1,[]];
    }

    [$gameString, $setsString] = explode(':', $line);

    sscanf($gameString, 'Game %d', $game);
    $bag = array_map(
        static function ($set) {
            $cubes = [];

            foreach (explode(', ', trim($set)) as $item) {
                sscanf($item, "%d %s", $times, $color);
                $cubes[$color] = $times;
            }

            return $cubes;
        },
        explode(';', trim($setsString))
    );

    return [$game, $bag];
}


/**
 * @param array $bag
 *
 * @return array|int[]
 */
function getFew(array $bag): array
{
    if ([] === $bag) {
        return [];
    }

    $colors = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];

    foreach ($bag as $set) {
        foreach ($set as $color => $count) {
            $colors[$color] = max($colors[$color], $count);
        }
    }

    return $colors;
}
