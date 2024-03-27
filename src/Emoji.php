<?php
namespace Emoji;

define('LONGEST_EMOJI', 10);

function detect_emoji($string) {
  // Find all the emoji in the input string

  $prev_encoding = mb_internal_encoding();
  mb_internal_encoding('UTF-8');

  $data = array();

  static $map;
  if(!isset($map))
    $map = _load_map();

  static $baseCodepoints;
  if(!isset($baseCodepoints))
    $baseCodepoints = _load_basecodepoints();

  $codepoints = mb_str_split($string);
  $emojiChars = [];

  $currentEmoji = null;
  $includeNext = false;
  foreach($codepoints as $cp) {
    if($currentEmoji == null) {
      if(in_array($cp, $baseCodepoints)) {
        $currentEmoji = $cp;
      } elseif(_is_country_flag($cp)) {
        $currentEmoji = $cp;
        $includeNext = true; // Flags are always 2 chars so grab the next one
      }
    } else {
      if($includeNext) {
        $currentEmoji .= $cp;
        $includeNext = false;
      }
      elseif(_is_modifier($cp)) {
        // If this codepoint is a modifier, add it now
        $currentEmoji .= $cp;
        $includeNext = false;
      } elseif(_is_zwj($cp)) {
        // If this codepoint is a ZWJ, include the next codepoint in the emoji as well
        $currentEmoji .= $cp;
        $includeNext = true;
      } else {
        $emojiChars[] = $currentEmoji;
        $currentEmoji = null;

        if(in_array($cp, $baseCodepoints)) {
          $currentEmoji = $cp;
        } elseif(_is_country_flag($cp)) {
          $currentEmoji = $cp;
          $includeNext = true; // Flags are always 2 chars so grab the next one
        }
      }
    }
  }
  if($currentEmoji) {
    $emojiChars[] = $currentEmoji;
  }

  // Now we have a list of individual completed emoji chars in the order they are in the string.

  $lastGOffset = 0;
  $lastOffset = 0;

  foreach($emojiChars as $emoji) {
    $mbLength = mb_strlen($emoji); // the length of the emoji, mb chars are counted as 1

    $offset = strpos($string, $emoji, $lastOffset);
    $lastOffset = $offset + strlen($emoji);

    $gOffset = grapheme_strpos($string, $emoji, $lastGOffset);
    $lastGOffset = $gOffset + 1;

    $points = array();
    for($i=0; $i<$mbLength; $i++) {
      $points[] = strtoupper(dechex(uniord(mb_substr($emoji, $i, 1))));
    }
    $hexstr = implode('-', $points);

    if(array_key_exists($hexstr, $map)) {
      $short_name = $map[$hexstr];
    } else {
      $short_name = null;
    }

    $skin_tone = null;
    $skin_tones = array(
      '1F3FB' => 'skin-tone-2',
      '1F3FC' => 'skin-tone-3',
      '1F3FD' => 'skin-tone-4',
      '1F3FE' => 'skin-tone-5',
      '1F3FF' => 'skin-tone-6',
    );
    foreach($points as $pt) {
      if(array_key_exists($pt, $skin_tones))
        $skin_tone = $skin_tones[$pt];
    }

    $data[] = array(
      'emoji' => $emoji,
      'short_name' => $short_name,
      'num_points' => mb_strlen($emoji),
      'points_hex' => $points,
      'hex_str' => $hexstr,
      'skin_tone' => $skin_tone,
      'byte_offset' => $offset,       // The position of the emoji in the string, counting each byte
      'grapheme_offset' => $gOffset,  // The grapheme-based position of the emoji in the string
    );
  }

  if($prev_encoding)
    mb_internal_encoding($prev_encoding);

  return $data;
}

function get_first_emoji($string) {
  $emojis = detect_emoji($string);
  if(count($emojis))
    return $emojis[0];
  else
    return null;
}

function is_single_emoji($string) {
  $prev_encoding = mb_internal_encoding();
  mb_internal_encoding('UTF-8');

  // If the string is longer than the longest emoji, it's not a single emoji
  if(mb_strlen($string) >= LONGEST_EMOJI) return false;

  $all_emoji = detect_emoji($string);

  $emoji = false;

  // If there are more than one or none, return false immediately
  if(count($all_emoji) == 1) {
    $emoji = $all_emoji[0];

    // Check if there are any other characters in the string

    // Remove the emoji found
    $string = str_replace($emoji['emoji'], '', $string);

    // If there are any characters left, then the string is not a single emoji
    if(strlen($string) > 0)
      $emoji = false;
  }

  if($prev_encoding)
    mb_internal_encoding($prev_encoding);

  return $emoji;
}


/**
 * Replace Emoji
 *
 * @param string $string
 * @param string $prefix
 * @param string $suffix
 * @param string $index
 * @return string
 */
function replace_emoji($string, $prefix='', $suffix='', $index='short_name') {
  while ($emoji = get_first_emoji($string)) {
    $offset = $emoji['byte_offset'];
    $length = strlen($emoji['emoji']);
    $strlen = strlen($string);
    $start = substr($string, 0, $offset);
    $end = substr($string, $offset + $length, $strlen - ($offset + $length));

    // Check index
    if (empty($index)) {
      $index = 'short_name';
    }

    $string = $start.$prefix.$emoji[$index].$suffix.$end;
  }
  return $string;
}

function remove_emoji($string, $opts=[]) {
  $emojis = \Emoji\detect_emoji($string);

  foreach (array_reverse($emojis) as $emoji) {
    $length = strlen($emoji['emoji']);
    $start = substr($string, 0, $emoji['byte_offset']);
    $end = substr($string, $emoji['byte_offset'] + $length, strlen($string) - ($emoji['byte_offset'] + $length));

    if(isset($opts['collapse']) && $opts['collapse']) {
      $end = trim($end);
    }

    $string = $start . $end;
  }

  return $string;
}

function _is_modifier($cp) {
  $modifiers = [
    "\u{1F3FB}",
    "\u{1F3FC}",
    "\u{1F3FD}",
    "\u{1F3FE}",
    "\u{1F3FF}",
    "\u{FE0F}",
  ];
  // Flag letters for subdivision flags
  $modifiers = array_merge($modifiers, [
    "\u{E0067}", "\u{E0062}", "\u{E0063}", "\u{E0065}",
    "\u{E006C}", "\u{E006E}", "\u{E0073}", "\u{E0074}", "\u{E0077}",
    "\u{E007F}", // Terminator
  ]);
  return in_array($cp, $modifiers);
}

function _is_zwj($cp) {
  return $cp == "\u{200D}";
}

function _is_country_flag($cp) {
  return mb_ord("\u{1F1E6}") <= mb_ord($cp) && mb_ord($cp) <= mb_ord("\u{1F1FF}");
}

function _load_map() {
  return json_decode(file_get_contents(__DIR__.'/map.json'), true);
}

function _load_basecodepoints() {
  return json_decode(file_get_contents(__DIR__.'/base-codepoints.json'), true);
}

function uniord($c) {
  $ord0 = ord($c[0]); if ($ord0>=0   && $ord0<=127) return $ord0;
  $ord1 = ord($c[1]); if ($ord0>=192 && $ord0<=223) return ($ord0-192)*64 + ($ord1-128);
  $ord2 = ord($c[2]); if ($ord0>=224 && $ord0<=239) return ($ord0-224)*4096 + ($ord1-128)*64 + ($ord2-128);
  $ord3 = ord($c[3]); if ($ord0>=240 && $ord0<=247) return ($ord0-240)*262144 + ($ord1-128)*4096 + ($ord2-128)*64 + ($ord3-128);
  return false;
}
