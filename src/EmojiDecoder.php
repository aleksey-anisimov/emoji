<?php

declare(strict_types=1);

namespace Anisimov\Emoji;

use Anisimov\Emoji\Delimiter\DelimiterInterface;

class EmojiDecoder
{
    private const EMOJI_PARTS_DELIMITER = '-';
    private const EMOJI_PREFIX = '&#x';
    private const EMOJI_SUFFIX = ';';

    private array $map;

    private DelimiterInterface $delimiter;

    public function __construct(DataLoader $dataLoader, DelimiterInterface $delimiter)
    {
        $this->map = $dataLoader->loadMap();
        $this->delimiter = $delimiter;
    }

    public function decode(string $string): string
    {
        foreach ($this->findEncodedEmoji($string) as $encodedEmoji) {
            $emoji = array_search($encodedEmoji, $this->map, true) ?: '';
            $preparedEmoji = $this->prepareEmoji($emoji);

            $string = str_replace(
                $this->delimiter->getDelimiter() . $encodedEmoji . $this->delimiter->getDelimiter(),
                $preparedEmoji,
                $string
            );
        }

        return html_entity_decode($string);
    }

    private function prepareEmoji(string $string): string
    {
        $parts = explode(self::EMOJI_PARTS_DELIMITER, $string);
        $result = '';

        foreach ($parts as $part) {
            $result .= self::EMOJI_PREFIX . $part . self::EMOJI_SUFFIX;
        }

        return $result;
    }

    private function findEncodedEmoji(string $string): array
    {
        preg_match_all($this->delimiter->getRegexp(), $string, $matches);

        $result = $matches[0] ?: [];

        return $this->removePrefixAndSuffix($result);
    }

    private function removePrefixAndSuffix(array $result): array
    {
        $delimiter = $this->delimiter->getDelimiter();

        return array_map(
            static function ($item) use ($delimiter): string {
                return str_replace([$delimiter], '', $item);
            },
            $result
        );
    }
}