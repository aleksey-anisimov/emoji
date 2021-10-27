<?php

declare(strict_types=1);

namespace Anisimov\Emoji;

use Anisimov\Emoji\DTO\EmojiDTO;

class EmojiDetector
{
    private array $map;

    private string $regexp;

    public function __construct(DataLoader $dataLoader)
    {
        $this->map = $dataLoader->loadMap();
        $this->regexp = $dataLoader->loadRegexp();
    }

    public function getFirstEmoji(string $string): ?EmojiDTO
    {
        $emojis = $this->detect($string);

        return count($emojis) ? $emojis[0] : null;
    }

    public function detect(string $string): array
    {
        $prevEncoding = mb_internal_encoding();
        mb_internal_encoding('UTF-8');

        $data = [];

        if (preg_match_all($this->regexp, $string, $matches, PREG_OFFSET_CAPTURE)) {
            $emojisLength = 0;
            $lastMbOffset = 0;

            foreach ($matches[0] as $match) {
                $character = $match[0];
                $offset = $match[1] - $emojisLength;
                $mbOffset = mb_strpos($string, $character, $lastMbOffset);
                $mbLength = mb_strlen($character);
                $lastMbOffset = $offset + $mbLength;
                $emojisLength += (strlen($character) - 1);
                $points = [];

                for ($i = 0; $i < $mbLength; $i++) {
                    $points[] = strtoupper(dechex($this->uniord(mb_substr($character, $i, 1))));
                }

                $hexString = $this->getHexString($points);

                $emojiDTO = new EmojiDTO();
                $emojiDTO->emoji = $character;
                $emojiDTO->shortName = $this->map[$hexString] ?? null;
                $emojiDTO->points = mb_strlen($character);
                $emojiDTO->hexString = $hexString;
                $emojiDTO->skinTone = $this->getSkinTone($points);
                $emojiDTO->offset = $offset;
                $emojiDTO->mbOffset = $mbOffset;
                $emojiDTO->mbLength = $mbLength;

                $data[] = $emojiDTO;
            }
        }

        if ($prevEncoding) {
            mb_internal_encoding($prevEncoding);
        }

        return $data;
    }

    private function getSkinTone(array $points): ?string
    {
        $skinTone = null;

        $skinTones = [
            '1F3FB' => 'skin-tone-2',
            '1F3FC' => 'skin-tone-3',
            '1F3FD' => 'skin-tone-4',
            '1F3FE' => 'skin-tone-5',
            '1F3FF' => 'skin-tone-6',
        ];

        foreach ($points as $point) {
            if (array_key_exists($point, $skinTones)) {
                $skinTone = $skinTones[$point];
            }
        }

        return $skinTone;
    }

    private function getHexString(array $points): string
    {
        return implode('-', $points);
    }

    private function uniord(string $c): int
    {
        $ord0 = ord($c[0]);

        if ($ord0 >= 0 && $ord0 <= 127) {
            return $ord0;
        }

        $ord1 = ord($c[1]);

        if ($ord0 >= 192 && $ord0 <= 223) {
            return ($ord0 - 192) * 64 + ($ord1 - 128);
        }

        $ord2 = ord($c[2]);

        if ($ord0 >= 224 && $ord0 <= 239) {
            return ($ord0 - 224) * 4096 + ($ord1 - 128) * 64 + ($ord2 - 128);
        }

        $ord3 = ord($c[3]);

        if ($ord0 >= 240 && $ord0 <= 247) {
            return ($ord0 - 240) * 262144 + ($ord1 - 128) * 4096 + ($ord2 - 128) * 64 + ($ord3 - 128);
        }

        return 0;
    }
}