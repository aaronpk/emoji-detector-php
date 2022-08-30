<?php
namespace Emoji;

class EmojiDetectTest extends \PHPUnit\Framework\TestCase {

  public function testDetectSimpleEmoji() {
    $string = '😻';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('😻', $emoji[0]['emoji']);
    $this->assertSame('heart_eyes_cat', $emoji[0]['short_name']);
    $this->assertSame('1F63B', $emoji[0]['hex_str']);
    $this->assertSame(0, $emoji[0]['byte_offset']);
  }

  public function testDetectEvenSimplerEmoji() {
    $string = '❤️';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('❤️', $emoji[0]['emoji']);
    $this->assertSame('heart', $emoji[0]['short_name']);
    $this->assertSame('2764-FE0F', $emoji[0]['hex_str']);
    $this->assertSame(0, $emoji[0]['byte_offset']);

    $string = '☂';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('☂', $emoji[0]['emoji']);
    $this->assertSame('umbrella', $emoji[0]['short_name']);
    $this->assertSame('2602', $emoji[0]['hex_str']);
    $this->assertSame(0, $emoji[0]['byte_offset']);
    $this->assertSame('☂', is_single_emoji($string)['emoji']);
  }

  public function testDetectEmojiWithZWJ() {
    $string = '👨‍👩‍👦‍👦';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('man-woman-boy-boy', $emoji[0]['short_name']);
    $this->assertSame('1F468-200D-1F469-200D-1F466-200D-1F466', $emoji[0]['hex_str']);
    $this->assertSame(0, $emoji[0]['byte_offset']);
  }

  public function testDetectEmojiWithZWJ2() {
    $string = '👩‍❤️‍👩';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('woman-heart-woman', $emoji[0]['short_name']);
    $this->assertSame('1F469-200D-2764-FE0F-200D-1F469', $emoji[0]['hex_str']);
    $this->assertSame(0, $emoji[0]['byte_offset']);

    $string = '👩‍❤️‍💋‍👨';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('woman-kiss-man', $emoji[0]['short_name']);
    $this->assertSame('1F469-200D-2764-FE0F-200D-1F48B-200D-1F468', $emoji[0]['hex_str']);
    $this->assertSame(0, $emoji[0]['byte_offset']);
  }

  public function testDetectEmojiWithSkinTone() {
    $string = '👍🏼';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('👍🏼', $emoji[0]['emoji']);
    $this->assertSame('+1', $emoji[0]['short_name']);
    $this->assertSame('1F44D-1F3FC', $emoji[0]['hex_str']);
    $this->assertSame('skin-tone-3', $emoji[0]['skin_tone']);
    $this->assertSame(0, $emoji[0]['byte_offset']);
  }

  public function testDetectMultipleEmoji() {
    $string = '👩❤️';
    $emoji = detect_emoji($string);
    $this->assertCount(2, $emoji);
    $this->assertSame('woman', $emoji[0]['short_name']);
    $this->assertSame('heart', $emoji[1]['short_name']);
    $this->assertSame(0, $emoji[0]['grapheme_offset']);
    $this->assertSame(1, $emoji[1]['grapheme_offset']);
  }

  public function testDetectFlagEmoji() {
    $string = '🇩🇪';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('flag-de', $emoji[0]['short_name']);
    $this->assertSame(0, $emoji[0]['byte_offset']);
  }

  public function testDetectSymbolWithModifier() {
    $string = '♻️';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('recycle', $emoji[0]['short_name']);
    $this->assertSame(0, $emoji[0]['byte_offset']);
  }

  public function testDetectCharacterSymbol() {
    $string = '™️';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('tm', $emoji[0]['short_name']);
    $this->assertSame(0, $emoji[0]['byte_offset']);
  }

  public function testDetectEmojiWithZJW3() {
    $string = '🏳️‍🌈';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('rainbow-flag', $emoji[0]['short_name']);
    $this->assertSame('1F3F3-FE0F-200D-1F308', $emoji[0]['hex_str']);
    $this->assertSame(0, $emoji[0]['byte_offset']);
  }

  public function testDetectText() {
    $string = 'This has no emoji.';
    $emoji = detect_emoji($string);
    $this->assertCount(0, $emoji);
  }

  public function testDetectInText() {
    $string = 'This has an 🎉 emoji.';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('tada', $emoji[0]['short_name']);
    $this->assertSame(12, $emoji[0]['grapheme_offset']);
    $this->assertSame(12, $emoji[0]['byte_offset']);
  }

  public function testDetectGenderModifier() {
    // Added in June 2017 http://www.unicode.org/Public/emoji/5.0/emoji-test.txt
    $string = 'guardswoman 💂‍♀️';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('female-guard', $emoji[0]['short_name']);
    $this->assertSame(12, $emoji[0]['grapheme_offset']);
    $this->assertSame(12, $emoji[0]['byte_offset']);
  }

  public function testDetectGenderAndSkinToneModifier() {
    // Added in June 2017 http://www.unicode.org/Public/emoji/5.0/emoji-test.txt
    $string = 'guardswoman 💂🏼‍♀️';
    $emoji = detect_emoji($string);
    $this->assertCount(1, $emoji);
    $this->assertSame('female-guard', $emoji[0]['short_name']);
    $this->assertSame(12, $emoji[0]['byte_offset']);
    $this->assertSame(12, $emoji[0]['grapheme_offset']);
  }

  public function testDetectOffset() {
    $emojis = array(
        '👩',
        '❤️',
        '💂',
        '👨‍👩‍👦‍👦',
        '👩‍❤️‍👩',
        '♻️'
    );
    $separator = ' word ';
    $string = implode($separator, $emojis);

    // $string = '👩 word ❤️ word 💂 word 👨‍👩‍👦‍👦 word 👩‍❤️‍👩 word ♻️';

    $emoji = detect_emoji($string);
    $this->assertCount(sizeof($emojis), $emoji);
    $currentOffset = 0;
    $currentMbOffset = 0;

    $index = 0;

    $this->assertSame($index, $emoji[0]['byte_offset']);
    $this->assertSame(0, $emoji[0]['grapheme_offset']);

    $index += strlen($emoji[0]['emoji'].' word ');

    $this->assertSame($index, $emoji[1]['byte_offset']);
    $this->assertSame(7, $emoji[1]['grapheme_offset']);

    $index += strlen($emoji[1]['emoji'].' word ');

    $this->assertSame($index, $emoji[2]['byte_offset']);
    $this->assertSame(14, $emoji[2]['grapheme_offset']);

    $index += strlen($emoji[2]['emoji'].' word ');

    $this->assertSame($index, $emoji[3]['byte_offset']);
    $this->assertSame(21, $emoji[3]['grapheme_offset']);

    $index += strlen($emoji[3]['emoji'].' word ');

    $this->assertSame($index, $emoji[4]['byte_offset']);
    $this->assertSame(28, $emoji[4]['grapheme_offset']);

    $index += strlen($emoji[4]['emoji'].' word ');

    $this->assertSame($index, $emoji[5]['byte_offset']);
    $this->assertSame(35, $emoji[5]['grapheme_offset']);
  }

  public function testDetectAndReplace() {
    $string = 'I like 🌮 and 🌯';
    $emojis = detect_emoji($string);
    $this->assertCount(2, $emojis);
    $this->assertSame(7, $emojis[0]['grapheme_offset']);
    $this->assertSame(13, $emojis[1]['grapheme_offset']);
    while (sizeof($emojis = detect_emoji($string)) > 0) {
      $offset = $emojis[0]['byte_offset'];
      $length = strlen($emojis[0]['emoji']);
      $strLength = strlen($string);
      $start = substr($string, 0, $offset);
      $end = substr($string, $offset + $length, $strLength - ($offset + $length));
      $string = $start.$emojis[0]['short_name'].$end;
    }
    $this->assertSame('I like taco and burrito', $string);
  }

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

  public function testLongEmojiString() {
    $string = "⬛️⬛️⬛️⬛️⬛ ️⬛️⬛️⬛️⬛️⬛ ️☎️🙅🏻‍♀️🙅🏾‍♂️⬛️⬛️⬛️⬛️⬛️📲🎉⬛️⬛️⬛️⬛️⬛️a⬛️b⬛️c⬛️d⬛️e⬛ ️";
    $emojis = detect_emoji($string);
    $this->assertCount(30, $emojis);
  }

  public function testUnrecognizedChars() {
    $string = "Not Wordle, just us improving your contact center experience. \n\n⬛️⬛️⬛️⬛️⬛ ️\n⬛️⬛️⬛️⬛️⬛ ️\n☎️🙅🏻‍♀️🙅🏾‍♂️⬛️⬛️\n⬛️⬛️⬛️📲🎉\n⬛️⬛️⬛️⬛️⬛️\n⬛️⬛️⬛️⬛️⬛ ️";
    $emojis = detect_emoji($string);
    $this->assertCount(30, $emojis);
    $this->assertSame('⬛', $emojis[29]['emoji']);
  }

  public function testOffsets() {
    $string = "åक😱äbç";
    $this->assertSame(6, grapheme_strlen($string));
    $emojis = detect_emoji($string);
    $this->assertSame(2, $emojis[0]['grapheme_offset']);
    $this->assertSame(6, $emojis[0]['byte_offset']);
    $prefix = grapheme_substr($string, 0, $emojis[0]['grapheme_offset']);
    $this->assertSame('åक', $prefix);
    $prefix = substr($string, 0, $emojis[0]['byte_offset']);
    $this->assertSame('åक', $prefix);
    $suffix = grapheme_substr($string, $emojis[0]['grapheme_offset']+1);
    $this->assertSame('äbç', $suffix);
    $suffix = substr($string, $emojis[0]['byte_offset']+strlen($emojis[0]['emoji']));
    $this->assertSame('äbç', $suffix);
  }

}
