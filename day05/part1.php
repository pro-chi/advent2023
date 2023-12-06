<?php

[$seeds, $maps] = (new ReadInput())->readData('input');

$conversion = $seeds;
/** @var Map $map */
foreach ($maps as $map) {
    $conversion = $map->convert($conversion);
}

$lowestLocation = array_reduce($conversion, fn ($hold, $input) => min($hold, $input), reset($conversion));

echo $lowestLocation;



class ReadInput {

    /**
     * @param string $fileName
     *
     * @return void
     * @throws ErrorException
     */
    public function readData(string $fileName): array
    {
        if (!is_file($fileName)) {
            throw new \ErrorException('Could not locate file');
        }

        $file = fopen($fileName, 'r');

        $seeds = $this->readSeeds($file);

        for ($i = 0; $i < 7; $i++) {
            $maps[] = $this->readMap($file);
        }

        fclose($file);

        return [$seeds, $maps];
    }

    /**
     * @param $file
     *
     * @return array
     */
    private function readSeeds($file): array
    {
        [, $numbers] = explode(':', trim(fgets($file)));
        $numbers = array_map(
            fn ($item) => (int) $item,
            array_filter(explode(' ', trim($numbers)), fn ($number) => is_numeric($number))
        );
        fgets($file);

        return $numbers;
    }

    /**
     * @param $file
     *
     * @return Map
     */
    private function readMap($file): Map
    {
        $line = fgets($file);

        if (!$line) {
            return  [];
        }

        sscanf(trim($line), '%s map:', $mapName);
        $map = new Map($mapName);

        while (($line = fgets($file)) && $line !== PHP_EOL) {
            sscanf(trim($line), "%d %d %d", $destination, $source, $length);
            $map->addRanges(new Range($destination, $source, $length));
        }

        return $map;
    }
}


class Map
{
    private string $name;

    /** @var Range[]  */
    private array $ranges = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addRanges(Range $range): void
    {
        $this->ranges[] = $range;
    }

    public function convert(array $input)
    {
        $output = [];

        foreach ($input as $seed) {
            $convert = null;

            foreach ($this->ranges as $range) {
                $convert = $range->convert($seed);

                if (null !== $convert) {
                    $output[$seed] = $convert;

                    break;
                }
            }

            if (null === $convert) {
                $output[$seed] = $seed;
            }
        }

        return $output;
    }
}

class Range
{
    private int $destination;
    private int $source;
    private int $length;

    public function __construct(int $destination, int $source, int $length)
    {
        $this->destination = $destination;
        $this->source = $source;
        $this->length = $length;
    }

    public function convert(int $input): ?int
    {
        if ($input >= $this->source &&  $input <= $this->source + $this->length) {
            return $this->destination + $input - $this->source;
        }

        return null;
    }
}
