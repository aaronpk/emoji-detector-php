<?php

class EmojiSingleTest extends \PHPUnit\Framework\TestCase {

  public function testSingleEmoji() {
    $string = '😻';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertSame($string, $emoji['emoji']);
    $this->assertSame(0, $emoji['offset']);
  }

  public function testSingleCompositeEmoji() {
    $string = '👨‍👩‍👦‍👦';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertSame($string, $emoji['emoji']);
    $this->assertSame(0, $emoji['offset']);
  }

  public function testMultipleEmoji() {
    $string = '😻🐈';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertFalse($emoji);
  }

  public function testSingleEmojiWithText() {
    $string = 'kitty 😻';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertFalse($emoji);
  }

}
