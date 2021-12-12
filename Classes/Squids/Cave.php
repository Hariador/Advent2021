<?php
namespace Squids;
use Bramus\Ansi\ControlSequences\EscapeSequences\Enums\SGR;
use Bramus\Ansi\Writers\StreamWriter;
use Illuminate\Support\Collection;
use Bramus\Ansi\Ansi;

class Cave
{

    protected Collection $map;
    protected int $maxY = 0;
    protected int $maxX = 0;

    public function __construct() {
        $this->map = new Collection();
        $this->squid = new Collection();
    }

    public function addRow(string $rowData): void {
        $this->maxY++;
        $temp = new Collection();
        $x = 0;
        foreach(str_split($rowData) as $energy) {
            $reading = new Squid((int)$energy);
            $temp->push($reading);
            $x++;
            if($x > $this->maxX) {
                $this->maxX = $x;
            }
        }
        $this->map->push($temp);
    }

    public function step(): void {
        for($y=0;$y<$this->maxY;$y++) {
            for ($x = 0; $x < $this->maxX; $x++) {
                if($this->map[$y][$x]->increment()) {
                    $this->scanAndIncrement($y,$x);
                }
            }

        }
    }

    public function flash(): int {
        $sum = 0;
        foreach($this->map as $row) {
            /** @var Squid $squid */
            foreach($row as $squid) {
                if($squid->willFlash()) {
                    $sum++;
                    $squid->flash();
                }
            }
        }
        return $sum;
    }

    public function print(): void {
        $console = new Ansi(new StreamWriter());
        $console->eraseDisplay();
        foreach($this->map as $row) {
            /** @var Squid $squid */
            foreach($row as $squid) {
                if($squid->willFlash()) {
                    $output = min($squid->getEnergy(),9);
                    $console->color(SGR::COLOR_FG_YELLOW)->text($output)->reset();
                } else {
                    if ($squid->getEnergy() == 0) {
                        $console->color(SGR::COLOR_FG_YELLOW_BRIGHT)->text($squid->getEnergy())->reset();
                    } else {
                        $console->color(SGR::COLOR_FG_WHITE)->text($squid->getEnergy())->reset();
                    }

                }
            }
            $console->lf();
        }
        $console->reset();
    }

    private function scanAndIncrement(int $y, int $x): void {

        //Up-left
        if(isset($this->map[$y-1][$x-1])) {
            if($this->map[$y-1][$x-1]->increment()) {
                $this->scanAndIncrement($y-1,$x-1);
            }
        }

        //Up
        if(isset($this->map[$y-1][$x])) {
            if($this->map[$y-1][$x]->increment()) {
                $this->scanAndIncrement($y-1,$x);
            }
        }

        //Up Right
        if(isset($this->map[$y-1][$x+1])) {
            if($this->map[$y-1][$x+1]->increment()) {
                $this->scanAndIncrement($y-1,$x+1);
            }
        }

        //Right
        if(isset($this->map[$y][$x+1])) {
            if($this->map[$y][$x+1]->increment()) {
                $this->scanAndIncrement($y,$x+1);
            }
        }

        //Down Right
        if(isset($this->map[$y+1][$x+1])) {
            if($this->map[$y+1][$x+1]->increment()) {
                $this->scanAndIncrement($y+1,$x+1);
            }
        }

        //Down
        if(isset($this->map[$y+1][$x])) {
            if($this->map[$y+1][$x]->increment()) {
                $this->scanAndIncrement($y+1,$x);
            }
        }

        //Down Left
        if(isset($this->map[$y+1][$x-1])) {
            if($this->map[$y+1][$x-1]->increment()) {
                $this->scanAndIncrement($y+1,$x-1);
            }
        }

        //Left
        if(isset($this->map[$y][$x-1])) {
            if($this->map[$y][$x-1]->increment()) {
                $this->scanAndIncrement($y,$x-1);
            }
        }
    }

}