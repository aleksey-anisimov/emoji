### It allows to detect emoji, remove emoji, encode emoji and decode emoji in string.

# Installation

```
composer require anisimov/emoji
```

# How to use

## Encode and decode Emoji

### Single colon delimiter

```php
$delimiter = new \Anisimov\Emoji\Delimiter\SingleColonDelimiter();
$emoji = new \Anisimov\Emoji\Emoji($delimiter);

$stringWithEmoji = 'some string 😉';

$result = $emoji->encode($stringWithEmoji); // some string :wink:

$stringWithEncodedEmoji = 'some string :wink:';

$result = $emoji->decode($stringWithEmoji); // some string 😉
```

### Double colon delimiter

```php
$delimiter = new \Anisimov\Emoji\Delimiter\DoubleColonDelimiter();
$emoji = new \Anisimov\Emoji\Emoji($delimiter);

$stringWithEmoji = 'some string 😉';

$result = $emoji->encode($stringWithEmoji); // some string ::wink::

$stringWithEncodedEmoji = 'some string ::wink::';

$result = $emoji->decode($stringWithEmoji); // some string 😉
```

### Triple colon delimiter

```php
$delimiter = new \Anisimov\Emoji\Delimiter\TripleColonDelimiter();
$emoji = new \Anisimov\Emoji\Emoji($delimiter);

$stringWithEmoji = 'some string 😉';

$result = $emoji->encode($stringWithEmoji); // some string :::wink:::

$stringWithEncodedEmoji = 'some string :::wink:::';

$result = $emoji->decode($stringWithEmoji); // some string 😉
```

## Detect emoji in string

```php
$delimiter = new \Anisimov\Emoji\Delimiter\SingleColonDelimiter();
$emoji = new \Anisimov\Emoji\Emoji($delimiter);

$stringWithEmoji = 'some string 😉';

$result = $emoji->hasEmoji($stringWithEmoji); // true

$stringWithoutEmoji = 'some string';

$result = $emoji->hasEmoji($stringWithoutEmoji); // false
```

---

## Remove all emoji in string

```php
$delimiter = new \Anisimov\Emoji\Delimiter\SingleColonDelimiter();
$emoji = new \Anisimov\Emoji\Emoji($delimiter);

$stringWithEmoji = '😉 some 😉 😉 😉 string 😉';

$result = $emoji->remove($stringWithEmoji); // 'some string'
```

---

## Find all emoji in string

```php
$delimiter = new \Anisimov\Emoji\Delimiter\SingleColonDelimiter();
$emoji = new \Anisimov\Emoji\Emoji($delimiter);

$stringWithEmoji = '😀 some 😃 😄 string 😁 😆';

$result = $emoji->find($stringWithEmoji); // ['😀', '😃', '😄', '😁', '😆']
```

---

# How to create custom delimiter

Create delimiter class that implements `\Anisimov\Emoji\Delimiter\DelimiterInterface`.

`\Anisimov\Emoji\Delimiter\DelimiterInterface::getRegexp` method should return regex to find delimiter wrapped emoji.

`\Anisimov\Emoji\Delimiter\DelimiterInterface::getDelimiter` method should return delimiter symbols

For example, `\Anisimov\Emoji\Delimiter\SingleColonDelimiter`

`\Anisimov\Emoji\Delimiter\SingleColonDelimiter::getRegexp` returns `/[\:]{1}[^(\:|\s)]{1,}[\:]{1}/`

`\Anisimov\Emoji\Delimiter\SingleColonDelimiter::getDelimiter` returns `:`