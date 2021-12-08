<?php
namespace Board;

class BoardNumber
{
    protected int $value;
    protected bool $stamped;

    public function __construct(int $value) {
        $this->value = $value;
        $this->stamped = false;
    }

    public function stamp(): void {
        $this->stamped = true;
    }

    public function getValue(): int {
        return $this->value;
    }

    public function isStamped(): bool {
        return $this->stamped;
    }

    public function match(int $value): bool {
        if($this->value === $value) {
            $this->stamp();
            return true;
        }

        return false;
    }
}