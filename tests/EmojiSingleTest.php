<?php
namespace Emoji;

class EmojiSingleTest extends \PHPUnit\Framework\TestCase {

  public function testSingleEmoji() {
    $string = '😻';
    $emoji = is_single_emoji($string);
    $this->assertSame($string, $emoji['emoji']);
  }

  public function testSingleCompositeEmoji() {
    $string = '👨‍👩‍👦‍👦';
    $emoji = is_single_emoji($string);
    $this->assertSame($string, $emoji['emoji']);
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

}
