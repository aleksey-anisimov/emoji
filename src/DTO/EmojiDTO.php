<?php

declare(strict_types=1);

namespace Anisimov\Emoji\DTO;

class EmojiDTO
{
    public string $emoji;

    public string $shortName;

    public int $points;

    public string $hexString;

    public ?string $skinTone = null;

    public int $offset;

    public int $mbOffset;

    public int $mbLength;
}