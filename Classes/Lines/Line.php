<?php

namespace Lines;

class Line implements \Iterator
{
    protected Point $start ;
    protected Point $end;
    protected bool $sloped;
    protected int $horizontal;
    protected int $vertical;
    protected ?Point $position;
    protected bool $done;
    protected bool $finished;

    public function __construct(string $lineData) {
        $line = explode('->', trim($lineData));
        $this->start = new Point($line[0]);
        $this->end = new Point($line[1]);
        if($this->start == $this->end) {
            throw new \Exception("Line has no length... aka it's a point");
        }
        $this->position = $this->start;
        $this->done = false;
        $this->finished = false;
        $this->setDirection();
    }

    public function getStart(): Point {
        return $this->start;
    }

    public function getEnd(): Point {
        return $this->end;
    }

    public function getMinX(): int {
        return min($this->start->X(),$this->end->X());
    }

    public function getMaxX(): int {
        return max($this->start->X(),$this->end->X());
    }

    public function getMinY(): int {
        return min($this->start->Y(), $this->end->Y());
    }

    public function getMaxY(): int {
        return max($this->start->Y(),$this->end->Y());
    }

    private function setDirection(): void {

        $this->horizontal = 0;
        $this->vertical = 0;


            if($this->end->X() > $this->start->X()) {
                $this->horizontal = 1 ;
            }

            if($this->end->Y() > $this->start->Y()) {
                $this->vertical = 1;
            }

            if ($this->start->X() > $this->end->X()) {
                $this->horizontal = -1;
            }

            if($this->start->Y() > $this->end->Y()) {
                $this->vertical = -1;
            }

    }

    public function __toString(): string {
        return $this->start->__toString() ." -> " . $this->end->__toString();
    }

    public function valid()
    {
        return !$this->finished;
    }

    public function next()
    {
        $this->position->move($this->horizontal,$this->vertical);

        if($this->done) {
            $this->finished = true;
        }
        if($this->position == $this->end) {
            $this->done = true;
        }
    }

    public function current()
    {
        return $this->position;
    }

    public function rewind()
    {

            $this->position = $this->start;
            $this->done = false;
            $this->finished = false;


    }

    public function key() {

    }

    public function printDiag(): void {
        printLine("Hor: {$this->horizontal} Ver: {$this->vertical}");
        printLine("Start: {$this->start} End: {$this->end}");
    }
}