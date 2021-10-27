<?php

declare(strict_types=1);

$emojiData = json_decode(
    file_get_contents('https://raw.githubusercontent.com/iamcal/emoji-data/master/emoji_pretty.json'),
    true,
    512,
    JSON_THROW_ON_ERROR
);

$map = [];

foreach ($emojiData as $emoji) {
    $shortName = $emoji['short_name'];

    if (isset($emoji['short_names']) && in_array('flag-' . $shortName, $emoji['short_names'], true)) {
        $shortName = 'flag-' . $shortName;
    }

    $map[$emoji['unified']] = $shortName;

    if (isset($emoji['variations'])) {
        foreach ($emoji['variations'] as $var) {
            $map[$var] = $shortName;
        }
    }

    if (isset($emoji['skin_variations'])) {
        foreach ($emoji['skin_variations'] as $key => $var) {
            $map[$var['unified']] = $shortName;
        }
    }
}

file_put_contents(__DIR__ . '/../files/map.json', json_encode($map, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));

$keys = array_keys($map);

usort($keys, static function (string $a, string $b) {
    return strlen($b) - strlen($a);
});

$all = preg_replace('/\-?([0-9a-f]+)/i', '\x{$1}', implode('|', $keys));

file_put_contents(__DIR__ . '/../files/regexp.json', json_encode($all, JSON_THROW_ON_ERROR));
