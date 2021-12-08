<?php

namespace Lines;
use Bramus\Ansi\Writers\StreamWriter;
use Illuminate\Support\Collection;
use Bramus\Ansi\Ansi;
class Grid
{

    protected Collection $lines;

    public function __construct(int $xMax, int $yMax) {
        $this->lines = new Collection();
        for($y=0;$y<=$yMax;$y++) {
            $temp = new Collection();
            for($x=0;$x<=$xMax;$x++) {
                $temp[$x] = 0;
            }
            $this->lines->push($temp);
        }
    }

    public function addLine(Line $line): void {
        /** @var Point $point */
        foreach($line as $point) {
           $this->lines[$point->Y()][$point->X()] += 1;
        }
    }

    public function countem(): int {
        $overlaps = 0;
        foreach($this->lines as $row) {
            foreach($row as $cell) {
                if($cell >1) {
                    $overlaps++;
                }
            }

        }

        return  $overlaps;
    }

    public function print(): void {
        $console = new Ansi(new StreamWriter());
        foreach($this->lines as $row) {
            foreach($row as $cell) {
                if($cell == 0) {
                    $console->text(".");
                } else {
                    $console->text($cell);
                }
            }
            $console->lf();
        }
    }


}