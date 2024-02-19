<?php
namespace Emoji;

class EmojiRemoveTest extends \PHPUnit\Framework\TestCase {

  public function testRemoveEmojiSimple() {
    $string = "I like 🌮 and 🌯";
    $removed = remove_emoji($string);
    $this->assertSame('I like  and ', $removed);
  }

  public function testRemoveEmojiCollapse() {
    $string = "I like 🌮 and 🌯";
    $removed = remove_emoji($string, ['collapse' => true]);
    $this->assertSame('I like and', $removed);
  }

}
