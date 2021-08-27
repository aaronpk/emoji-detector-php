<?php
require('vendor/autoload.php');

$input = "Hello 👍🏼 World 👨‍👩‍👦‍👦";
$emoji = Emoji\detect_emoji($input);

print_r($emoji);


$string = 'I like 🌮 and 🌯';
$emojis = Emoji\detect_emoji($string);
while (sizeof($emojis = Emoji\detect_emoji($string)) > 0) {
  $offset = $emojis[0]['mb_offset'];
  $length = $emojis[0]['mb_length'];
  $strlen = mb_strlen($string, 'UTF-8');
  $start = mb_substr($string, 0, $offset, 'UTF-8');
  $end = mb_substr($string, $offset + $length, $strlen - ($offset + $length), 'UTF-8');
  $string = $start.':'.$emojis[0]['short_name'].':'.$end;
}
echo $string."\n";

