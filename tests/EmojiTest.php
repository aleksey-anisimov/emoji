<?php

declare(strict_types=1);

namespace Anisimov\Emoji\Test;

use Anisimov\Emoji\Delimiter\DoubleColonDelimiter;
use Anisimov\Emoji\Delimiter\SingleColonDelimiter;
use Anisimov\Emoji\Delimiter\TripleColonDelimiter;
use Anisimov\Emoji\Emoji;
use PHPUnit\Framework\TestCase;

class EmojiTest extends TestCase
{
    /**
     * @dataProvider getStringsForEncoding
     */
    public function testEncode(string $string, string $expectedResult, string $delimiterClass): void
    {
        $delimiter = new $delimiterClass();
        $emoji = new Emoji($delimiter);
        $result = $emoji->encode($string);

        self::assertSame($expectedResult, $result);
    }

    public function getStringsForEncoding(): array
    {
        return [
            'emoji in the end of string' => [
                'some string 😉😉😉😉😉',
                'some string :wink::wink::wink::wink::wink:',
                SingleColonDelimiter::class,
            ],
            'emoji in the start of string' => [
                '😉😉😉😉😉 some string',
                ':wink::wink::wink::wink::wink: some string',
                SingleColonDelimiter::class,
            ],
            'emoji in the middle of string' => [
                'some 😉😉😉😉😉 string',
                'some :wink::wink::wink::wink::wink: string',
                SingleColonDelimiter::class,
            ],
            'emoji in different parts of string' => [
                '😉😉 some 😉 😉 😉 string 😉',
                ':wink::wink: some :wink: :wink: :wink: string :wink:',
                SingleColonDelimiter::class,
            ],
            'emoji with single colon delimiter' => [
                'some string 😉😉😉😉😉',
                'some string :wink::wink::wink::wink::wink:',
                SingleColonDelimiter::class,
            ],
            'emoji with double colon delimiter' => [
                'some string 😉😉😉😉😉',
                'some string ::wink::::wink::::wink::::wink::::wink::',
                DoubleColonDelimiter::class,
            ],
            'emoji with triple colon delimiter' => [
                'some string 😉😉😉😉😉',
                'some string :::wink::::::wink::::::wink::::::wink::::::wink:::',
                TripleColonDelimiter::class,
            ],
            'empty string' => [
                '',
                '',
                TripleColonDelimiter::class,
            ],
        ];
    }

    /**
     * @dataProvider getStringsForDecoding
     */
    public function testDecode(string $string, string $expectedResult, string $delimiterClass): void
    {
        $delimiter = new $delimiterClass();
        $emoji = new Emoji($delimiter);
        $result = $emoji->decode($string);

        self::assertSame($expectedResult, $result);
    }

    public function getStringsForDecoding(): array
    {
        return [
            'emoji in the end of string' => [
                'some string :wink::wink::wink::wink::wink:',
                'some string 😉😉😉😉😉',
                SingleColonDelimiter::class,
            ],
            'emoji in the start of string' => [
                ':wink::wink::wink::wink::wink: some string',
                '😉😉😉😉😉 some string',
                SingleColonDelimiter::class,
            ],
            'emoji in the middle of string' => [
                'some :wink::wink::wink::wink::wink: string',
                'some 😉😉😉😉😉 string',
                SingleColonDelimiter::class,
            ],
            'emoji in different parts of string' => [
                ':wink::wink: some :wink: :wink: :wink: string :wink:',
                '😉😉 some 😉 😉 😉 string 😉',
                SingleColonDelimiter::class,
            ],
            'emoji with single colon delimiter' => [
                'some string :wink::wink::wink::wink::wink:',
                'some string 😉😉😉😉😉',
                SingleColonDelimiter::class,
            ],
            'emoji with double colon delimiter' => [
                'some string ::wink::::wink::::wink::::wink::::wink::',
                'some string 😉😉😉😉😉',
                DoubleColonDelimiter::class,
            ],
            'emoji with triple colon delimiter' => [
                'some string :::wink::::::wink::::::wink::::::wink::::::wink:::',
                'some string 😉😉😉😉😉',
                TripleColonDelimiter::class,
            ],
            'with whitespace in the start' => [
                'some string : wink:',
                'some string : wink:',
                SingleColonDelimiter::class,
            ],
            'with whitespaces in the middle' => [
                'some string : wink :',
                'some string : wink :',
                SingleColonDelimiter::class,
            ],
            'with whitespace in the end' => [
                'some string :wink :',
                'some string :wink :',
                SingleColonDelimiter::class,
            ],
            'empty string' => [
                '',
                '',
                TripleColonDelimiter::class,
            ],
        ];
    }

    /**
     * @dataProvider getStringsForCheckingThatItHasEmoji
     */
    public function testHasEmoji(string $string, bool $expectedResult, string $delimiterClass): void
    {
        $delimiter = new $delimiterClass();
        $emoji = new Emoji($delimiter);
        $result = $emoji->hasEmoji($string);

        self::assertSame($expectedResult, $result);
    }

    public function getStringsForCheckingThatItHasEmoji(): array
    {
        return [
            'emoji in the end of string' => [
                'some string 😉😉😉😉😉',
                true,
                SingleColonDelimiter::class,
            ],
            'emoji in the start of string' => [
                '😉😉😉😉😉 some string',
                true,
                SingleColonDelimiter::class,
            ],
            'emoji in the middle of string' => [
                'some 😉😉😉😉😉 string',
                true,
                SingleColonDelimiter::class,
            ],
            'emoji in different parts of string' => [
                '😉😉 some 😉 😉 😉 string 😉',
                true,
                SingleColonDelimiter::class,
            ],
            'string without emoji' => [
                'some string',
                false,
                SingleColonDelimiter::class,
            ],
            'only one emoji' => [
                '😉',
                true,
                DoubleColonDelimiter::class,
            ],
            'empty string' => [
                '',
                false,
                TripleColonDelimiter::class,
            ],
        ];
    }

    /**
     * @dataProvider getStringsForRemovingEmoji
     */
    public function testRemove(string $string, string $expectedResult, string $delimiterClass): void
    {
        $delimiter = new $delimiterClass();
        $emoji = new Emoji($delimiter);
        $result = $emoji->remove($string);

        self::assertSame($expectedResult, $result);
    }

    public function getStringsForRemovingEmoji(): array
    {
        return [
            'emoji in the end of string' => [
                'some string 😉😉😉😉😉',
                'some string',
                SingleColonDelimiter::class,
            ],
            'emoji in the start of string' => [
                '😉😉😉😉😉 some string',
                'some string',
                SingleColonDelimiter::class,
            ],
            'emoji in the middle of string' => [
                'some 😉😉😉😉😉 string',
                'some string',
                SingleColonDelimiter::class,
            ],
            'emoji in different parts of string' => [
                '😉😉 some 😉 😉 😉 string 😉',
                'some string',
                SingleColonDelimiter::class,
            ],
            'empty string' => [
                '',
                '',
                SingleColonDelimiter::class,
            ],
            'white-spaces string' => [
                '        ',
                '',
                SingleColonDelimiter::class,
            ],
        ];
    }

    /**
     * @dataProvider getStringsForFindingEmoji
     */
    public function testFindEmoji(string $string, array $expectedResult, string $delimiterClass): void
    {
        $delimiter = new $delimiterClass();
        $emoji = new Emoji($delimiter);
        $result = $emoji->find($string);

        self::assertCount(count($expectedResult), $result);

        foreach ($result as $index => $item) {
            self::assertSame($expectedResult[$index], $item);
        }
    }

    public function getStringsForFindingEmoji(): array
    {
        return [
            'string without emoji' => [
                'some string',
                [],
                SingleColonDelimiter::class,
            ],
            'string with emoji' => [
                'some string 😀 😃 😄 😁 😆',
                ['😀', '😃', '😄', '😁', '😆'],
                SingleColonDelimiter::class,
            ],
        ];
    }
}
