<?php

declare(strict_types=1);

namespace Anisimov\Emoji;

use Anisimov\Emoji\Delimiter\DelimiterInterface;
use Anisimov\Emoji\DTO\EmojiDTO;

class Emoji
{
    private EmojiEncoder $encoder;

    private EmojiDecoder $decoder;

    private EmojiDetector $detector;

    private EmojiKiller $killer;

    public function __construct(DelimiterInterface $delimiter)
    {
        $dataLoader = new DataLoader();
        $this->detector = new EmojiDetector($dataLoader);
        $this->decoder = new EmojiDecoder($dataLoader, $delimiter);
        $this->encoder = new EmojiEncoder($this->detector, $delimiter);
        $this->killer = new EmojiKiller($this->detector);
    }

    public function encode(string $string): string
    {
        return $this->encoder->encode($string);
    }

    public function decode(string $string): string
    {
        return $this->decoder->decode($string);
    }

    public function hasEmoji(string $string): bool
    {
        return count($this->detector->detect($string)) > 0;
    }

    public function remove(string $string): string
    {
        return $this->killer->remove($string);
    }

    public function find(string $string): array
    {
        return array_map(
            static function (EmojiDTO $emojiDTO): string {
                return $emojiDTO->emoji;
            },
            $this->detector->detect($string)
        );
    }
}