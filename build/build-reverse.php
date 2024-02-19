<?php
// build the reverse mapping arrays for each skin variation 

$emoji_data = json_decode(file_get_contents('https://raw.githubusercontent.com/iamcal/emoji-data/master/emoji_pretty.json'), true);

$map = [];

$skin_tones = array(
    '1F3FB' => 'skin-tone-2',
    '1F3FC' => 'skin-tone-3',
    '1F3FD' => 'skin-tone-4',
    '1F3FE' => 'skin-tone-5',
    '1F3FF' => 'skin-tone-6',
);

foreach($emoji_data as $emoji) {
    foreach ($emoji['short_names'] as $short_name) {
        $map['unified'][$short_name] = $emoji['unified'];
        foreach($skin_tones as $code => $type) {
            $map[$type][$short_name] = $emoji['unified'];
        }

        if(isset($emoji['skin_variations'])) {
            foreach($emoji['skin_variations'] as $key => $var) {
                if (isset($skin_tones[$key])) {
                    $type = $skin_tones[$key];
                } else if (isset($skin_tones[$key . '-' . $key])) {
                    $type = $skin_tones[$key . '-' . $key];
                } else {
                    continue;
                }
                $map[$type][$short_name] = $var['unified'];
            }
        }
    }
}

foreach ($map as $type => $data) {
    file_put_contents(dirname(__FILE__).'/../src/map-reverse-' . $type . '.json', json_encode($data, JSON_PRETTY_PRINT));
}
