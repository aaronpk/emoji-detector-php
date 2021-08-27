<?php
namespace Emoji;

class EmojiReplaceTest extends \PHPUnit\Framework\TestCase {

  public function testReplaceEmojiDefault() {
    $string = "I like 🌮 and 🌯";
    $replaced = replace_emoji($string);
    $this->assertSame('I like taco and burrito', $replaced);
  }

  public function testReplaceEmojiPrefix() {
    $string = "I like 🌮 and 🌯";
    $replaced = replace_emoji($string, ':');
    $this->assertSame('I like :taco and :burrito', $replaced);
  }

  public function testReplaceEmojiSuffix() {
    $string = "I like 🌮 and 🌯";
    $replaced = replace_emoji($string, '', ':');
    $this->assertSame('I like taco: and burrito:', $replaced);
  }

  public function testReplaceEmojiPrefixAndSuffix() {
    $string = "I like 🌮 and 🌯";
    $replaced = replace_emoji($string, ':', ':');
    $this->assertSame('I like :taco: and :burrito:', $replaced);
  }

}
