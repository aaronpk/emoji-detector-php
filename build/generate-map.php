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
      $map[$emoji['unified'] . '-' . $key] = $emoji['short_name'];
    }
  }
}

file_put_contents(dirname(__FILE__).'/../src/map.json', json_encode($map));

$keys = array_keys($map);
usort($keys,function($a,$b){
    return strlen($b)-strlen($a);
});
$all = preg_replace('/\-?([0-9a-f]+)/i', '\x{$1}', implode('|', $keys));

file_put_contents(dirname(__FILE__).'/../src/regexp.json', json_encode($all));
