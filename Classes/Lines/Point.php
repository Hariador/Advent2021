<?php
namespace Lines;

class Point
{

    protected int $x;
    protected int $y;

    public function __construct(string $rawPoint) {
        $data = explode(',',trim($rawPoint));
        $this->x = $data[0];
        $this->y = $data[1];
    }

    public function __toString(): string
    {
        return "(".$this->x.",".$this->y.")";
    }

    public function X(): int {
        return $this->x;
    }

    public function Y(): int {
        return $this->y;
    }

    public function move(int $hStep, int $vStep): void {
        $this->x += $hStep;
        $this->y += $vStep;
    }

}