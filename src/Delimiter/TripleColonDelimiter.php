<?php
declare(strict_types=1);

namespace Anisimov\Emoji\Delimiter;

class TripleColonDelimiter implements DelimiterInterface
{
    public function getRegexp(): string
    {
        return '/[\:]{3}[^(\:|\s)]{1,}[\:]{3}/';
    }

    public function getDelimiter(): string
    {
        return ':::';
    }
}