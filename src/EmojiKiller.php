<?php

declare(strict_types=1);

namespace Anisimov\Emoji;

class EmojiKiller
{
    private const CHARSET = 'UTF-8';

    private EmojiDetector $detector;

    public function __construct(EmojiDetector $detector)
    {
        $this->detector = $detector;
    }

    public function remove(string $string): string
    {
        while ($emojiDTO = $this->detector->getFirstEmoji($string)) {
            $offset = $emojiDTO->mbOffset;
            $length = $emojiDTO->mbLength;
            $stringLength = mb_strlen($string, self::CHARSET);
            $start = mb_substr($string, 0, $offset, self::CHARSET);
            $end = mb_substr($string, $offset + $length, $stringLength - ($offset + $length), self::CHARSET);
            $string = $start . $end;
        }

        $string = mb_ereg_replace('\s{2,}', ' ', $string);

        return trim($string);
    }
}