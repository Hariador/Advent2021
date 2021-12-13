<?php

namespace Pathing;
use Bramus\Ansi\Ansi;
use Bramus\Ansi\Writers\StreamWriter;

class Paths
{
    protected array $paths;

    public function __construct()
    {
        $this->paths = [];
    }

    public function contains(string $label): bool {
        return array_key_exists($label,$this->paths);
    }


    public function add(Cave $cave): void {
        $this->paths[$cave->getLabel()] = $cave;
    }



    public function remove(Cave $cave): void {
        unset($this->paths[$cave->getLabel()]);
    }

    public function get(string $label): Cave {
        return $this->paths[$label];
    }

    public function print(): void {
        $console = new Ansi(new StreamWriter());
        foreach($this->paths as $label=>$path) {
            $console->text($label.'::')->tab();
            foreach($path->getConnections() as $connection) {
                $console->text($connection."-");
            }
            $console->lf();
        }
    }


}