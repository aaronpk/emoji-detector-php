Emoji Detection
===============

This library will find all emoji in an input string and return information about each emoji character. It supports emoji with skin tone modifiers, as well as the composite emoji that are made up of multiple people.

Usage
-----

```php
$input = "Hello 👍🏼 World 👨‍👩‍👦‍👦";
$emoji = Emoji\detect_emoji($input);

print_r($emoji);
```

The function returns an array with details about each emoji found in the string.

```
Array
(
  [0] => Array
    (
      [emoji] => 👨‍👩‍👦‍👦
      [short_name] => man-woman-boy-boy
      [num_points] => 7
      [points_hex] => Array
        (
          [0] => 1F468
          [1] => 200D
          [2] => 1F469
          [3] => 200D
          [4] => 1F466
          [5] => 200D
          [6] => 1F466
        )
      [hex_str] => 1F468-200D-1F469-200D-1F466-200D-1F466
      [skin_tone] =>
    )
  [1] => Array
    (
      [emoji] => 👍🏼
      [short_name] => +1
      [num_points] => 2
      [points_hex] => Array
        (
          [0] => 1F44D
          [1] => 1F3FC
        )

      [hex_str] => 1F44D-1F3FC
      [skin_tone] => skin-tone-3
    )
)
```

* `emoji` - The emoji sequence found, as the original byte sequence. You can output this to show the original emoji.
* `short_name` - The short name of the emoji, as defined by [Slack's emoji data](https://github.com/iamcal/emoji-data).
* `num_points` - The number of unicode code points that this emoji is composed of.
* `points_hex` - An array of each unicode code point that makes up this emoji. These are returned as hex strings. This will also include "invisible" characters such as the ZJW character and skin tone modifiers.
* `hex_str` - A list of all unicode code points in their hex form separated by hyphens. This string is present in the [Slack emoji data](https://github.com/iamcal/emoji-data) array.
* `skin_tone` - If a skin tone modifier was used in the emoji, this field indicates which skin tone, since the `short_name` will not include the skin tone.


License
-------

Copyright 2017 by Aaron Parecki.

Available under the MIT license.

Emoji data sourced from [iamcal/emoji-data](https://github.com/iamcal/emoji-data) under the MIT license.

Emoji parsing regex sourced from [EmojiOne](https://github.com/Ranks/emojione) under the MIT license.

