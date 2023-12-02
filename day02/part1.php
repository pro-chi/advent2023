<?php

$fr = fopen("input", 'r');


$gamesSum = 0;


while (!feof($fr)) {
    $line = fgets($fr);
    [$game, $bag] = parseLine($line);

    $possible = isPlayable($bag);
//    printf("%d is %s\t%s\n", $game, $possible ? 'possible' : 'impossible', $line);

    if ($possible) {
        printf("%d. game is possible\n", $game);
        $gamesSum += $game;
    }
}

fclose($fr);

echo "\nSum: ". $gamesSum;


/**
 * @param string $line
 *
 * @return mixed
 */
function parseLine(string $line): mixed
{
    if (empty($line)) {
        return [0,[]];
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
 * @return bool
 */
function isPlayable(array $bag): bool
{
    if ([] === $bag) {
        return false;
    }

    return [] === array_filter(
        $bag,
        static function($items) {
            return
                (isset($items['red']) && $items['red'] > 12)
                || (isset($items['green']) && $items['green'] > 13)
                || (isset($items['blue']) && $items['blue'] > 14)
            ;
        }
    );
}
