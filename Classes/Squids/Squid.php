<?php

namespace Squids;

class Squid
{
    protected int $energy;
    protected bool $fired = false;

    public function __construct(int $enegry)
    {
        $this->energy = $enegry;
    }

    public function getEnergy(): int {
        return $this->energy;
    }

    public function willFlash(): bool {
        return $this->energy > 9;
    }

    public function flash(): void {
        $this->energy = 0;
    }

    public function increment(): bool {
        $this->energy++;
        if($this->energy == 10) {
            return true;
        } else {
            return false;
        }
    }
}