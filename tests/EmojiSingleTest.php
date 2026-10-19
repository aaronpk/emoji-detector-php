<?php
namespace Emoji;

class EmojiSingleTest extends \PHPUnit\Framework\TestCase {

  public function testSingleEmoji() {
    $string = '😻';
    $emoji = is_single_emoji($string);
    $this->assertSame($string, $emoji['emoji']);
    $this->assertSame(0, $emoji['byte_offset']);
  }

  public function testSingleCompositeEmoji() {
    $string = '👨‍👩‍👦‍👦';
    $emoji = is_single_emoji($string);
    $this->assertSame($string, $emoji['emoji']);
    $this->assertSame(0, $emoji['byte_offset']);
  }

  public function testSingleLongestEmoji() {
    $string = '🧑🏻‍❤️‍💋‍🧑🏼';
    $emoji = is_single_emoji($string);
    $this->assertSame($string, $emoji['emoji']);
    $this->assertSame('couplekiss', $emoji['short_name']);
    $this->assertSame(10, $emoji['num_points']);
  }

  public function testMultipleEmoji() {
    $string = '😻🐈';
    $emoji = is_single_emoji($string);
    $this->assertFalse($emoji);
  }

  public function testSingleEmojiWithText() {
    $string = 'kitty 😻';
    $emoji = is_single_emoji($string);
    $this->assertFalse($emoji);
  }

  public function testLongStringRestoresEncoding() {
    $prev_encoding = mb_internal_encoding();
    mb_internal_encoding('ISO-8859-1');
    $emoji = is_single_emoji('this string is longer than any emoji');
    $encoding = mb_internal_encoding();
    mb_internal_encoding($prev_encoding);
    $this->assertFalse($emoji);
    $this->assertSame('ISO-8859-1', $encoding);
  }

}
