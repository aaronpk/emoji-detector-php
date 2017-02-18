<?php
namespace Emoji;

function detect_emoji($string) {
  // Find all the emoji in the input string

  $data = [];

  static $map;
  if(!isset($map))
    $map = _load_map();

  // From Emojione\Client
  // https://github.com/Ranks/emojione/blob/fd4bb7a26a39e93d0cb4ed90934e9e3f73ebca4b/lib/php/src/Client.php
  $regexp = '([*#0-9](?>\\xEF\\xB8\\x8F)?\\xE2\\x83\\xA3|\\xC2[\\xA9\\xAE]|\\xE2..(\\xF0\\x9F\\x8F[\\xBB-\\xBF])?(?>\\xEF\\xB8\\x8F)?|\\xE3(?>\\x80[\\xB0\\xBD]|\\x8A[\\x97\\x99])(?>\\xEF\\xB8\\x8F)?|\\xF0\\x9F(?>[\\x80-\\x86].(?>\\xEF\\xB8\\x8F)?|\\x87.\\xF0\\x9F\\x87.|..((\\xE2\\x80\\x8D\\xF0\\x9F\\x97\\xA8)|(\\xF0\\x9F\\x8F[\\xBB-\\xBF])|(\\xE2\\x80\\x8D\\xF0\\x9F\\x91[\\xA6-\\xA9]){2,3}|(\\xE2\\x80\\x8D\\xE2\\x9D\\xA4\\xEF\\xB8\\x8F\\xE2\\x80\\x8D\\xF0\\x9F..(\\xE2\\x80\\x8D\\xF0\\x9F\\x91[\\xA6-\\xA9])?))?))';

  if(preg_match_all($regexp, $string, $matches)) {
    foreach($matches[0] as $ch) {
      $points = [];
      for($i=0; $i<mb_strlen($ch); $i++) {
        $points[] = strtoupper(dechex(uniord(mb_substr($ch, $i, 1))));
      }
      $hexstr = implode('-', $points);

      if(array_key_exists($hexstr, $map)) {
        $short_name = $map[$hexstr];
      } else {
        $short_name = null;
      }

      $skin_tone = null;
      $skin_tones = [
        '1F3FB' => 'skin-tone-2',
        '1F3FC' => 'skin-tone-3',
        '1F3FD' => 'skin-tone-4',
        '1F3FE' => 'skin-tone-5',
        '1F3FF' => 'skin-tone-6',
      ];
      foreach($points as $pt) {
        if(array_key_exists($pt, $skin_tones))
          $skin_tone = $skin_tones[$pt];
      }

      $data[] = [
        'emoji' => $ch,
        'short_name' => $short_name,
        'num_points' => mb_strlen($ch),
        'points_hex' => $points,
        'hex_str' => $hexstr,
        'skin_tone' => $skin_tone,
      ];
    }
  }

  return $data;
}

function is_single_emoji($string) {
  $all_emoji = detect_emoji($string);

  // If there are more than one or none, return false immediately
  if(count($all_emoji) != 1)
    return false;

  $emoji = $all_emoji[0];

  // Check if there are any other characters in the string

  // Remove the emoji found
  $string = str_replace($emoji['emoji'], '', $string);
  // If there are any characters left, then the string is not a single emoji
  if(strlen($string) > 0)
    return false;

  return $emoji;
}

function _load_map() {
  return json_decode(file_get_contents(dirname(__FILE__).'/map.json'), true);
}

function uniord($c) {
  $ord0 = ord($c{0}); if ($ord0>=0   && $ord0<=127) return $ord0;
  $ord1 = ord($c{1}); if ($ord0>=192 && $ord0<=223) return ($ord0-192)*64 + ($ord1-128);
  $ord2 = ord($c{2}); if ($ord0>=224 && $ord0<=239) return ($ord0-224)*4096 + ($ord1-128)*64 + ($ord2-128);
  $ord3 = ord($c{3}); if ($ord0>=240 && $ord0<=247) return ($ord0-240)*262144 + ($ord1-128)*4096 + ($ord2-128)*64 + ($ord3-128);
  return false;
}
