<?php

declare(strict_types=1);

namespace Anisimov\Emoji;

use Anisimov\Emoji\Delimiter\DelimiterInterface;

class EmojiEncoder
{
    private const CHARSET = 'UTF-8';

    private EmojiDetector $detector;

    private DelimiterInterface $delimiter;

    public function __construct(EmojiDetector $detector, DelimiterInterface $delimiter)
    {
        $this->detector = $detector;
        $this->delimiter = $delimiter;
    }

    public function encode(string $string): string
    {
        $delimiter = $this->delimiter->getDelimiter();

        while ($emojiDTO = $this->detector->getFirstEmoji($string)) {
            $offset = $emojiDTO->mbOffset;
            $length = $emojiDTO->mbLength;
            $stringLength = mb_strlen($string, self::CHARSET);
            $start = mb_substr($string, 0, $offset, self::CHARSET);
            $end = mb_substr($string, $offset + $length, $stringLength - ($offset + $length), self::CHARSET);
            $string = $start . $delimiter . $emojiDTO->shortName . $delimiter . $end;
        }

        return $string;
    }
}