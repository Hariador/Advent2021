<?php
namespace Chunk;
use Chunk\ChunkPiece;
use Illuminate\Support\Collection;

class Chunk
{

    protected Collection $chunkData;
    protected bool $valid = false;
    protected int $pos=0;
    protected string $expected = "*";
    protected Collection $additions;

    public function __construct(string $input)
    {
        $this->chunkData = new Collection();
        $this->additions = new Collection();
        $inputs = str_split($input);

        $stop = false;
        foreach($inputs as $input) {
            switch($input) {
                case '(':
                case '[':
                case '{':
                case '<':$this->chunkData->push(new ChunkPiece($input));
                    break;
                case ')':
                case ']':
                case '}':
                case '>':{
                        $this->valid = $this->chunkData[$this->getNearestUnmatched()]->getValue() === $this->reverseChar($input);
                        if($this->valid) {
                            $this->chunkData[$this->getNearestUnmatched()]->markAsMatched();
                            $c = new ChunkPiece($input);
                            $c->markAsMatched();
                            $this->chunkData->push($c);
                        } else {
                            $stop = true;
                            $this->expected = $input;
                            $this->chunkData->push(new ChunkPiece($input));
                        }
                }
            }
            $this->pos++;
            if($stop) {
                break;
            }
        }
    }

    public function isValid(): bool {
        return $this->valid;
    }
    public function getCorruptChar(): string {
        if($this->valid) {
            throw new\ Exception("Chunk is not corrupt");
        }
        return $this->expected;
    }

    public function __toString(): string
    {
        $temp = "";
        foreach($this->chunkData as $chunk) {
            $temp .= $chunk->getValue();
        }
        return $temp;
    }

    public function score(): int {
        $score = 0;
        foreach($this->additions as $addition) {
            $score *= 5;
            switch($addition){
                case ')': $score += 1;
                    break;
                case ']': $score += 2;
                    break;
                case '}': $score += 3;
                    break;
                case '>': $score += 4;
            }
        }
        return $score;
    }

    public function fix(): void {
        $nearest = $this->getNearestUnmatched();
        while($nearest >= 0) {
            $char = $this->reverseChar($this->chunkData[$nearest]->getValue());
            $this->additions->push($char);
            $this->chunkData->push(new ChunkPiece($char));
            $this->chunkData[$nearest]->markAsMatched();
            $this->pos = $nearest;
            $nearest = $this->getNearestUnmatched();
        }

    }

    private function getNearestUnmatched(): int {
        for($i=$this->pos-1;$i>=0;$i--){
            if(!$this->chunkData[$i]->matched()) {
                return $i;
            }
        }
        return -1;
    }

    private function reverseChar(string $char): string {
        switch ($char) {
            case ')': return '(';
            case ']': return '[';
            case '}': return '{';
            case '>': return '<';
            case '(': return ')';
            case '[': return ']';
            case '{': return '}';
            case '<': return '>';
            default : return $char;
        }
    }

}