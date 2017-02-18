<?php
// Build the mapping array of hex unicode code point lists to shortnames.
// From Slack's emoji.json
// https://raw.githubusercontent.com/iamcal/emoji-data/master/emoji_pretty.json

$emoji_data = json_decode(file_get_contents('https://raw.githubusercontent.com/iamcal/emoji-data/master/emoji_pretty.json'), true);

$map = [];

foreach($emoji_data as $emoji) {
  $map[$emoji['unified']] = $emoji['short_name'];
  foreach($emoji['variations'] as $var) {
    $map[$var] = $emoji['short_name'];
  }
  if(array_key_exists('skin_variations', $emoji)) {
    foreach($emoji['skin_variations'] as $key=>$var) {
      $map[$key] = $emoji['short_name'];
    }
  }
}

file_put_contents(dirname(__FILE__).'/../src/map.json', json_encode($map));
