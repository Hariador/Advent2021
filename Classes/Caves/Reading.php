<?php
namespace Caves;

class Reading
{

    protected int $height;
    protected bool $isLowPoint;
    protected int $basinIndex = -1;

    public function __construct(int $height) {
        $this->height = $height;
        $this->isLowPoint = false;
    }

    public function getHeight(): int{
        return $this->height;
    }

    public function isLowPoint(): bool {
        return $this->isLowPoint;
    }

    public function markAsLowPoint(int $basin): void {
        $this->isLowPoint = true;
        $this->basinIndex = $basin;
    }

    public function assignBasin(int $basin): bool {
        if($this->height < 9) {
            if($this->basinIndex == -1) {
                $this->basinIndex = $basin;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function inBasin(): bool {
        if ($this->basinIndex >= 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getBasin(): int {
        return $this->basinIndex;
    }

}