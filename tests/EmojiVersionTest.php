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
  }

  public function testEmoji51_1() {
    # Spot check a few emoji from Emoji 15.1
    # https://emojipedia.org/emoji-15.1/
    $string = '🍋‍🟩🏃‍♀️‍➡️🐦‍🔥🙂‍↕️';
    $emojis = detect_emoji($string);
    $this->assertCount(4, $emojis);

    $this->assertSame('lime', $emojis[0]['short_name']);
    $this->assertSame('woman_running_facing_right', $emojis[1]['short_name']);
    $this->assertSame('phoenix', $emojis[2]['short_name']);
    $this->assertSame('head_shaking_vertically', $emojis[3]['short_name']);
  }

}
