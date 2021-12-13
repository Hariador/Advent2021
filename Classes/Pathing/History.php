<?php

namespace Pathing;

use Illuminate\Support\Collection;

class History
{

    protected Collection $history;

    public function __construct()
    {
        $this->history=  new Collection();
    }

    public function push(Cave $cave) {
       $this->history->push($cave);

    }

    public function pop(): void {
        $this->history->pop();
    }

    public function visited(string $label): bool {
        foreach($this->history as $cave) {
            if($cave->getLabel() === $label) {
                return true;
            }
        }
        return false;
    }

    public function visitedTwice(string $label): bool {
        $count = 0;
        foreach($this->history as $cave) {
            if($cave->getLabel() === $label) {
                $count++;
                if($count >1 ) {
                    return true;
                }
            }
        }

        return false;
    }

    public function toArray(): array {
        $t = [];
        foreach($this->history as $cave) {
            $t[] = $cave->getLabel();
        }

        return $t;
    }

}