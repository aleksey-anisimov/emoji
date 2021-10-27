<?php
declare(strict_types=1);

namespace Anisimov\Emoji\Delimiter;

class SingleColonDelimiter implements DelimiterInterface
{
    public function getRegexp(): string
    {
        return '/[\:]{1}[^(\:|\s)]{1,}[\:]{1}/';
    }

    public function getDelimiter(): string
    {
        return ':';
    }
}