<?php
namespace Caves;
use Bramus\Ansi\ControlSequences\EscapeSequences\Enums\SGR;
use Bramus\Ansi\Writers\StreamWriter;
use Illuminate\Support\Collection;
use Bramus\Ansi\Ansi;
class Cave
{

    protected Collection $map;
    protected int $maxY = 0;
    protected int $maxX = 0;
    protected int $basinIndex = 0;
    protected Collection $basinSizes;

    public function __construct() {
        $this->map = new Collection();
        $this->basinSizes = new Collection();
    }

    public function addRow(string $rowData): void {
        $this->maxY++;
        $temp = new Collection();
        $x = 0;
        foreach(str_split($rowData) as $height) {
            $reading = new Reading((int)$height);
            $temp->push($reading);
            $x++;
            if($x > $this->maxX) {
                $this->maxX = $x;
            }
        }
        $this->map->push($temp);
    }

    public function scanLows(): void {
        for($y=0;$y<$this->maxY;$y++) {
            for($x=0;$x<$this->maxX;$x++) {
                /** @var Reading $reading */
                $reading = $this->map[$y][$x];

                $above = isset($this->map[$y - 1]) ? $this->map[$y - 1][$x]->getHeight() : PHP_INT_MAX;
                $below = isset($this->map[$y + 1]) ? $this->map[$y+1][$x]->getHeight() : PHP_INT_MAX;
                $left = isset($this->map[$y][$x - 1]) ? $this->map[$y][$x - 1]->getHeight() : PHP_INT_MAX;
                $right = isset($this->map[$y][$x + 1]) ? $this->map[$y][$x + 1]->getHeight() : PHP_INT_MAX;
                if ($reading->getHeight() < $above &&
                    $reading->getHeight() < $below &&
                    $reading->getHeight() < $left &&
                    $reading->getHeight() < $right) {
                        $reading->markAsLowPoint($this->basinIndex);
                        $this->basinSizes[$this->basinIndex] = 1;
                        $this->basinIndex++;
                }
            }
        }
    }

    public function getSizes(): Collection {
        return $this->basinSizes;
    }

    public function fillBasins(): bool {
        $changed = false;
        for($y=0;$y<$this->maxY;$y++) {
            for ($x = 0; $x < $this->maxX; $x++) {
                /** @var Reading $reading */
                $reading = $this->map[$y][$x];
                $above = isset($this->map[$y - 1]) && $this->map[$y - 1][$x]->inBasin();
                $below = isset($this->map[$y + 1]) && $this->map[$y + 1][$x]->inBasin();
                $left = isset($this->map[$y][$x - 1]) && $this->map[$y][$x - 1]->inBasin();
                $right = isset($this->map[$y][$x + 1]) && $this->map[$y][$x + 1]->inBasin();
                if($above) {
                   if($reading->assignBasin($this->map[$y - 1][$x]->getBasin())){
                       $this->basinSizes[$reading->getBasin()] += 1;
                       $changed = true;
                       break;
                   }

                }
                if($below) {
                    if($reading->assignBasin($this->map[$y+1][$x]->getBasin())) {
                        $this->basinSizes[$reading->getBasin()] += 1;
                        $changed = true;
                        break;
                    }

                }
                if($left) {
                    if($reading->assignBasin($this->map[$y][$x - 1]->getBasin())){
                        $this->basinSizes[$reading->getBasin()] += 1;
                        $changed = true;
                        break;
                    }
                }
                if($right) {
                    if($reading->assignBasin($this->map[$y][$x + 1]->getBasin())) {
                        $this->basinSizes[$reading->getBasin()] += 1;
                        $changed = true;
                    }
                }

            }
        }
        return $changed;
    }


    public function sumOfRisk(): int {
        $sum = 0;
        foreach($this->map as $row) {
            /** @var Reading $reading */
            foreach($row as $reading) {
                if($reading->isLowPoint()) {
                    $sum += $reading->getHeight() + 1;
                }
            }
        }

        return $sum;
    }

    public function print(): void {
        $console = new Ansi(new StreamWriter());
        foreach($this->map as $row) {
            /** @var Reading $reading */
            foreach($row as $reading) {
                if($reading->inBasin()) {
                    $console->color(31 + ($reading->getBasin() %6))->text($reading->getHeight())->reset();
                } else {
                    $console->text(" ")->reset();
                }
            }
            $console->lf();
        }
        $console->reset();
    }

}