<?php
namespace Board;
use Bramus\Ansi\ControlSequences\EscapeSequences\Enums\SGR;
use Bramus\Ansi\Writers\StreamWriter;
use Illuminate\Support\Collection;
use Board\BoardNumber;
use Bramus\Ansi\Ansi;

class Board
{
    protected Collection $rows;
    protected int $size = 0;
    protected bool $winning = false;

    public function __construct(Collection $board) {
        $this->rows = new Collection();
        foreach($board as $rowData) {
            $this->size++;
            $row = new Collection();
            foreach($rowData as $number) {
                if($number==='') {
                    //skip
                } else {
                    $row->push(new BoardNumber((int)$number));
                }
            }
            $this->rows->push($row);
        }
    }

    public function print(): void {
        $console = new Ansi(new StreamWriter('php://stdout'));
        foreach($this->rows as $row) {
            /** @var BoardNumber $number */
            foreach($row as $number) {
                if($number->isStamped()) {
                    $console->bold()->color([SGR::COLOR_FG_RED])->text($number->getValue()." ")->reset();
                } else {
                    $console->text($number->getValue()." ");
                }

            }
            $console->lf();
        }
    }

    public function get(int $x, int $y): BoardNumber {
        return $this->rows[$y][$x];
    }

    public function isWinner(): bool {
        return $this->winning;
    }

    public function stamp(int $value): bool {
        foreach($this->rows as $row) {
            /** @var BoardNumber $number */
            foreach($row as $number) {
                if($number->match($value)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function checkForWin(): void  {
        if($this->checkRowsForWin() || $this->checkColsForWin()) {
            $this->winning = true;
        }
    }

    public function checkRowsForWin(): bool {
        for($y=0;$y<$this->size;$y++) {
            $count = 0;
            for($x=0;$x < $this->size;$x++) {
                if($this->get($x,$y)->isStamped()) {
                    $count++;
                }
            }
            if($count===$this->size) {
                return true;
            }
        }
        return false;
    }

    public function checkColsForWin(): bool {
        for($y=0;$y<$this->size;$y++) {
            $count = 0;
            for($x=0;$x < $this->size;$x++) {
                if($this->get($y,$x)->isStamped()) {
                    $count++;
                }
            }
            if($count===$this->size) {
                return true;
            }
        }
        return false;
    }

    public function noFoundSum(): int {
        $sum =0;
        for($y=0;$y<$this->size;$y++) {

            for($x=0;$x < $this->size;$x++) {
                if(!$this->get($x,$y)->isStamped()) {
                    $sum+=$this->get($x,$y)->getValue();
                }
            }

        }
        return $sum;

    }
}