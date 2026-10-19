<?php
namespace Emoji;

class EmojiVersionTest extends \PHPUnit\Framework\TestCase {

  public function testEmoji13_0() {
    # Spot check a few emoji from Emoji 13.0
    # https://emojipedia.org/emoji-13.0/
    $string = '🫁 🤌🏽 🤵🏾‍♀️';
    $emojis = detect_emoji($string);
    $this->assertCount(3, $emojis);
    $this->assertSame('lungs', $emojis[0]['short_name']);
    $this->assertSame('pinched_fingers', $emojis[1]['short_name']);
    $this->assertSame('skin-tone-4', $emojis[1]['skin_tone']);
    $this->assertSame('woman_in_tuxedo', $emojis[2]['short_name']);
    $this->assertSame('skin-tone-5', $emojis[2]['skin_tone']);
  }

  public function testEmoji13_1() {
    # Spot check a few emoji from Emoji 13.1
    # https://emojipedia.org/emoji-13.1/
    $string = '❤️‍🔥 👩🏿‍❤️‍💋‍👨🏽 ❤️‍🩹';
    $emojis = detect_emoji($string);
    $this->assertCount(3, $emojis);
    $this->assertSame('heart_on_fire', $emojis[0]['short_name']);
    $this->assertSame('woman-kiss-man', $emojis[1]['short_name']);
    $this->assertSame('mending_heart', $emojis[2]['short_name']);
  }

  public function testEmoji14_0() {
    # Spot check a few emoji from Emoji 14.0
    # https://emojipedia.org/emoji-14.0/
    $string = '🫳🫗';
    $emojis = detect_emoji($string);
    $this->assertCount(2, $emojis);
    $this->assertSame('palm_down_hand', $emojis[0]['short_name']);
    $this->assertSame('pouring_liquid', $emojis[1]['short_name']);
  }

  public function testDetectEmoji15() {
    $string = '🫨';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('🫨', $emoji[0]['emoji']);
    $this->assertSame('shaking_face', $emoji[0]['short_name']);
    $this->assertSame('1FAE8', $emoji[0]['hex_str']);
    $this->assertSame(0, $emoji[0]['byte_offset']);
    $this->assertSame('🫨', is_single_emoji($string)['emoji']);

    $string = '🪿';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('🪿', $emoji[0]['emoji']);
    $this->assertSame('goose', $emoji[0]['short_name']);
    $this->assertSame('1FABF', $emoji[0]['hex_str']);
    $this->assertSame(0, $emoji[0]['byte_offset']);
    $this->assertSame('🪿', is_single_emoji($string)['emoji']);

    $string = "👨🏻‍🦰👨🏿‍🦰";
    $emojis = detect_emoji($string);
    $this->assertCount(2, $emojis);
    $this->assertSame('red_haired_man', $emojis[0]['short_name']);
    $this->assertSame('red_haired_man', $emojis[1]['short_name']);
    $this->assertSame('skin-tone-6', $emojis[1]['skin_tone']);
  }

  public function testEmoji15_1() {
    # Spot check a few emoji from Emoji 15.1
    # https://emojipedia.org/emoji-15.1/
    $string = '🍋‍🟩🏃‍♀️‍➡️🐦‍🔥🙂‍↕️';
    $emojis = detect_emoji($string);
    $this->assertCount(4, $emojis);

    $this->assertSame('lime', $emojis[0]['short_name']);
    $this->assertSame('woman_running_facing_right', $emojis[1]['short_name']);
    $this->assertSame('phoenix', $emojis[2]['short_name']);
    $this->assertSame('head_shaking_vertically', $emojis[3]['short_name']);

    $this->assertSame('🍋‍🟩', is_single_emoji("🍋‍🟩")['emoji']);
  }

  public function testEmoji16_0() {
    # Spot check a few emoji from Emoji 16.0
    # https://emojipedia.org/emoji-16.0/
    $string = '🫩🫆🪾🫜🪉🪏';
    $emojis = detect_emoji($string);
    $this->assertCount(6, $emojis);

    $this->assertSame('face_with_bags_under_eyes', $emojis[0]['short_name']);
    $this->assertSame('fingerprint', $emojis[1]['short_name']);
    $this->assertSame('leafless_tree', $emojis[2]['short_name']);
    $this->assertSame('root_vegetable', $emojis[3]['short_name']);
    $this->assertSame('harp', $emojis[4]['short_name']);
    $this->assertSame('shovel', $emojis[5]['short_name']);

    $this->assertSame('🫩', is_single_emoji("🫩")['emoji']);
  }

  public function testEmoji17_0() {
    # Spot check a few emoji from Emoji 17.0
    # https://emojipedia.org/emoji-17.0/
    $string = '🫪🫯🫈🫍🛘🪊🪎🧑‍🩰';
    $emojis = detect_emoji($string);
    $this->assertCount(8, $emojis);

    $this->assertSame('distorted_face', $emojis[0]['short_name']);
    $this->assertSame('fight_cloud', $emojis[1]['short_name']);
    $this->assertSame('hairy_creature', $emojis[2]['short_name']);
    $this->assertSame('orca', $emojis[3]['short_name']);
    $this->assertSame('landslide', $emojis[4]['short_name']);
    $this->assertSame('trombone', $emojis[5]['short_name']);
    $this->assertSame('treasure_chest', $emojis[6]['short_name']);
    $this->assertSame('ballet_dancer', $emojis[7]['short_name']);

    $this->assertSame('🫪', is_single_emoji("🫪")['emoji']);

    # Emoji 17.0 also adds skin tones to wrestlers and people with bunny ears
    $string = '🧑🏽‍🩰 🤼🏽 👯🏿 🧑🏻‍🫯‍🧑🏿 👩🏾‍🐰‍👩🏻';
    $emojis = detect_emoji($string);
    $this->assertCount(5, $emojis);

    $this->assertSame('ballet_dancer', $emojis[0]['short_name']);
    $this->assertSame('skin-tone-4', $emojis[0]['skin_tone']);
    $this->assertSame('wrestlers', $emojis[1]['short_name']);
    $this->assertSame('skin-tone-4', $emojis[1]['skin_tone']);
    $this->assertSame('dancers', $emojis[2]['short_name']);
    $this->assertSame('skin-tone-6', $emojis[2]['skin_tone']);
    $this->assertSame('wrestlers', $emojis[3]['short_name']);
    $this->assertSame('1F9D1-1F3FB-200D-1FAEF-200D-1F9D1-1F3FF', $emojis[3]['hex_str']);
    $this->assertSame('women-with-bunny-ears-partying', $emojis[4]['short_name']);
    $this->assertSame('1F469-1F3FE-200D-1F430-200D-1F469-1F3FB', $emojis[4]['hex_str']);

    $this->assertSame('wrestlers', is_single_emoji("🧑🏻‍🫯‍🧑🏿")['short_name']);
  }

}
