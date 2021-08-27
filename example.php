<?php
require('vendor/autoload.php');

$input = "Hello 👍🏼 World 👨‍👩‍👦‍👦";
$emoji = Emoji\detect_emoji($input);

print_r($emoji);


echo Emoji\replace_emoji('I like 🌮 and 🌯')."\n";

