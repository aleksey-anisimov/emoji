<?php

declare(strict_types=1);

namespace Anisimov\Emoji;

use Error;
use JsonException;

class DataLoader
{
    private const MAP_FILE_PATH = __DIR__ . '/../files/map.json';
    private const REGEXP_FILE_PATH = __DIR__ . '/../files/regexp.json';

    /**
     * @throws JsonException
     */
    public function loadMap(): array
    {
        $data = $this->load(self::MAP_FILE_PATH);

        return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    }

    public function loadRegexp(): string
    {
        return '/(?:' . json_decode($this->load(self::REGEXP_FILE_PATH)) . ')/u';
    }

    /**
     * @throws Error
     */
    private function load($filePath): string
    {
        if (!file_exists($filePath)) {
            throw new Error('File ' . $filePath . ' is not found');
        }

        return file_get_contents($filePath);
    }
}