<?php

class EmojiDetectTest extends PHPUnit_Framework_TestCase {

  public function testDetectSimpleEmoji() {
    $string = '😻';
    $emoji = Emoji\detect_emoji($string);
    $this->assertEquals(1, count($emoji));
    $this->assertEquals('😻', $emoji[0]['emoji']);
    $this->assertEquals('heart_eyes_cat', $emoji[0]['short_name']);
    $this->assertEquals('1F63B', $emoji[0]['hex_str']);
  }

  public function testDetectEmojiWithZJW() {
    $string = '👨‍👩‍👦‍👦';
    $emoji = Emoji\detect_emoji($string);
    $this->assertEquals(1, count($emoji));
    $this->assertEquals('man-woman-boy-boy', $emoji[0]['short_name']);
    $this->assertEquals('1F468-200D-1F469-200D-1F466-200D-1F466', $emoji[0]['hex_str']);
  }

  public function testDetectEmojiWithZJW2() {
    $string = '👩‍❤️‍👩';
    $emoji = Emoji\detect_emoji($string);
    $this->assertEquals(1, count($emoji));
    $this->assertEquals('woman-heart-woman', $emoji[0]['short_name']);
    $this->assertEquals('1F469-200D-2764-FE0F-200D-1F469', $emoji[0]['hex_str']);
  }

  public function testDetectEmojiWithSkinTone() {
    $string = '👍🏼';
    $emoji = Emoji\detect_emoji($string);
    $this->assertEquals(1, count($emoji));
    $this->assertEquals('👍🏼', $emoji[0]['emoji']);
    $this->assertEquals('+1', $emoji[0]['short_name']);
    $this->assertEquals('1F44D-1F3FC', $emoji[0]['hex_str']);
    $this->assertEquals('skin-tone-3', $emoji[0]['skin_tone']);
  }

  public function testDetectMultipleEmoji() {
    $string = '👩❤️';
    $emoji = Emoji\detect_emoji($string);
    $this->assertEquals(2, count($emoji));
    $this->assertEquals('woman', $emoji[0]['short_name']);
    $this->assertEquals('heart', $emoji[1]['short_name']);
  }

}
