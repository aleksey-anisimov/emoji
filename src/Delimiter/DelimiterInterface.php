<?php

declare(strict_types=1);

namespace Anisimov\Emoji\Delimiter;

interface DelimiterInterface
{
    public function getRegexp(): string;

    public function getDelimiter(): string;
}