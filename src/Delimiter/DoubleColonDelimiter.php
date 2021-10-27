<?php
declare(strict_types=1);

namespace Anisimov\Emoji\Delimiter;

class DoubleColonDelimiter implements DelimiterInterface
{
    public function getRegexp(): string
    {
        return '/[\:]{2}[^(\:|\s)]{1,}[\:]{2}/';
    }

    public function getDelimiter(): string
    {
        return '::';
    }
}