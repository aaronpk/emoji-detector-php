<?php
// Build the mapping array of hex unicode code point lists to shortnames.
// From Slack's emoji.json
// https://raw.githubusercontent.com/iamcal/emoji-data/master/emoji_pretty.json

$emoji_data = json_decode(file_get_contents('https://raw.githubusercontent.com/iamcal/emoji-data/master/emoji_pretty.json'), true);

$map = [];

foreach($emoji_data as $emoji) {
  $short_name = $emoji['short_name'];

  // Slack changed flag-de shortname to de, but we still want to keep flag-de
  // Most of the flag emojis are still flag-*, they only changed some of them
  if(isset($emoji['short_names']) && in_array('flag-'.$short_name, $emoji['short_names'])) {
    $short_name = 'flag-' . $short_name;
  }

  $map[$emoji['unified']] = $short_name;

  if(isset($emoji['variations'])) {
    foreach($emoji['variations'] as $var) {
      $map[$var] = $short_name;
    }
  }

  if(isset($emoji['skin_variations'])) {
    foreach($emoji['skin_variations'] as $key=>$var) {
      $map[$var['unified']] = $short_name;
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
