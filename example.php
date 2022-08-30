<?php
require('vendor/autoload.php');

$input = "Hello 👍🏼 World 👨‍👩‍👦‍👦";
$emoji = Emoji\detect_emoji($input);

print_r($emoji);


$emoji = Emoji\is_single_emoji('👨‍👩‍👦‍👦');
print_r($emoji);



echo Emoji\replace_emoji('I like 🌮 and 🌯')."\n";


echo "\n";


$string = "Trešnja 🍒";
$emoji = Emoji\detect_emoji($string);
echo '.'.grapheme_substr($string, 0, $emoji[0]['grapheme_offset']).".\n";
echo '.'.substr($string, 0, $emoji[0]['byte_offset']).".\n";

