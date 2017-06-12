<?php

class EmojiSingleTest extends \PHPUnit\Framework\TestCase {

  public function testSingleEmoji() {
    $string = '😻';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertEquals($string, $emoji['emoji']);
  }

  public function testSingleCompositeEmoji() {
    $string = '👨‍👩‍👦‍👦';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertEquals($string, $emoji['emoji']);
  }

  public function testMultipleEmoji() {
    $string = '😻🐈';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertEquals(false, $emoji);
  }

  public function testSingleEmojiWithText() {
    $string = 'kitty 😻';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertEquals(false, $emoji);
  }

}
