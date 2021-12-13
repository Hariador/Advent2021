<?php
namespace Pathing;

use Illuminate\Support\Collection;

class Cave
{
    protected string $label;
    protected bool $large = false;
    protected bool $terminal = false;
    protected bool $start = false;
    protected bool $small= false;
    protected Collection $connections;

    public function __construct(string $label)
    {
        $this->connections = new Collection();
        $this->label = $label;
        if(preg_match('/^[A-Z]+$/', $this->label)){
            $this->large = true;
        } else if($label === 'start') {
            $this->start = true;
        } else if($label === 'end') {
            $this->terminal = true;
        } else {
            $this->small = true;
        }
    }
    public function getLabel(): string {
        return $this->label;
    }

    public function isLarge(): bool {
        return $this->large;
    }

    public function isSmall(): bool {
        return $this->small;
    }

    public function isStart(): bool {
        return $this->start;
    }

    public function isEnd(): bool {
        return $this->terminal;
    }

    public function getConnections(): array {
        return $this->connections->toArray();
    }

    public function addConnection(string $label): void {
        $this->connections->push($label);
    }

    public function __toString(): string {
        return $this->getLabel();
    }
}