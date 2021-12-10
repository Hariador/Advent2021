<?php

namespace Chunk;

class ChunkPiece
{
    protected string $chunkValue;
    protected bool $matched;

    public function __construct(string $value)
    {
        $this->chunkValue = $value;
        $this->matched = false;
    }

    public function getValue(): string {
        return $this->chunkValue;
    }

    public function markAsMatched(): void {
        $this->matched = true;
    }

    public function matched(): bool {
        return $this->matched;
    }

    public function __toString(): string
    {
        return $this->chunkValue;
    }
}