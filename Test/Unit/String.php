<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2014, Ivan Enderlin. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\String\Test\Unit;

use Hoa\Test;
use Hoa\String as LUT;

/**
 * Class \Hoa\String\Test\Unit\String.
 *
 * Test suite of the string class.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Ivan Enderlin.
 * @license    New BSD License
 */

class String extends Test\Unit\Suite {

    public function case_no_mbstring ( ) {

        $this
            ->given(
                $this->function->function_exists = function ( $name ) {

                    if('mb_substr' === $name)
                        return false;

                    return true;
                }
            )
            ->exception(function ( ) {

                new LUT();
            })
                ->isInstanceOf('Hoa\String\Exception');
    }

    public function case_append_ltr ( ) {

        $this
            ->given($string = new LUT('je'))
            ->when($result = $string->append(' t\'aime'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('je t\'aime');
    }

    public function case_append_rtl ( ) {

        $this
            ->given($string = new LUT('أ'))
            ->when($result = $string->append('حبك'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('أحبك');
    }

    public function case_prepend_ltr ( ) {

        $this
            ->given($string = new LUT(' t\'aime'))
            ->when($result = $string->prepend('je'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('je t\'aime');
    }

    public function case_prepend_rtl ( ) {

        $this
            ->given($string = new LUT('ك'))
            ->when($result = $string->prepend('أحب'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('أحبك');
    }

    public function case_pad_beginning_ltr ( ) {

        $this
            ->given($string = new LUT('je t\'aime'))
            ->when($result = $string->pad(20, '👍 💩 😄 ❤️ ', LUT::BEGINNING))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('👍 💩 😄 ❤️ 👍 je t\'aime');
    }

    public function case_pad_beginning_rtl ( ) {

        $this
            ->given($string = new LUT('أحبك'))
            ->when($result = $string->pad(20, '👍 💩 😄 ❤️ ', LUT::BEGINNING))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('👍 💩 😄 ❤️ 👍 💩 😄 ❤أحبك');
    }

    public function case_pad_end_ltr ( ) {

        $this
            ->given($string = new LUT('je t\'aime'))
            ->when($result = $string->pad(20, '👍 💩 😄 ❤️ ', LUT::END))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('je t\'aime👍 💩 😄 ❤️ 👍 ');
    }

    public function case_pad_end_rtl ( ) {

        $this
            ->given($string = new LUT('أحبك'))
            ->when($result = $string->pad(20, '👍 💩 😄 ❤️ ', LUT::END))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('أحبك👍 💩 😄 ❤️ 👍 💩 😄 ❤');
    }

    public function case_compare_no_collator ( ) {

        $this
            ->given(
                $this->function->class_exists = function ( $name ) {

                    if('Collator' === $name)
                        return false;

                    return true;
                },
                $string = new LUT('b')
            )
            ->case_compare();
    }

    public function case_compare ( ) {

        $this
            ->given($string = new LUT('b'))
            ->when($result = $string->compare('a'))
            ->then
                ->integer($result)
                    ->isEqualTo(1)

            ->when($result = $string->compare('b'))
            ->then
                ->integer($result)
                    ->isEqualTo(0)

            ->when($result = $string->compare('c'))
            ->then
                ->integer($result)
                    ->isEqualTo(-1);
    }

    public function case_collator ( ) {

        $this
            ->given(
                $this->function->setlocale = 'fr_FR',
                $collator = LUT::getCollator()
            )
            ->when($result = $collator->getLocale(\Locale::VALID_LOCALE))
            ->then
                ->string($result)
                    ->isEqualTo('fr');
    }

    public function case_safe_unsafe_pattern ( ) {

        $this
            ->given($pattern = '/foo/i')
            ->when($result = LUT::safePattern($pattern))
            ->then
                ->string($result)
                    ->isEqualto('/foo/iu');
    }

    public function case_safe_safe_pattern ( ) {

        $this
            ->given($pattern = '/foo/ui')
            ->when($result = LUT::safePattern($pattern))
            ->then
                ->string($result)
                    ->isEqualto('/foo/ui');
    }

    public function case_match_default ( ) {

        $this
            ->given(
                $pattern = '/💩/u',
                $string  = new LUT('foo 💩 bar')
            )
            ->when($result = $string->match($pattern, $matches))
            ->then
                ->integer($result)
                    ->isEqualTo(1)
                ->array($matches)
                    ->isEqualTo([
                        0 => '💩'
                    ]);
    }

    public function case_match_offset ( ) {

        $this
            ->given(
                $pattern = '/💩/u',
                $string  = new LUT('foo 💩 bar')
            )
            ->when($result = $string->match($pattern, $matches, 0, 0))
            ->then
                ->integer($result)
                    ->isEqualTo(1)
                ->array($matches)
                    ->isEqualTo([0 => '💩'])

            ->when($result = $string->match($pattern, $matches, 0, 4))
            ->then
                ->integer($result)
                    ->isEqualTo(1)
                ->array($matches)
                    ->isEqualTo([0 => '💩'])

            ->when($result = $string->match($pattern, $matches, 0, 5))
            ->then
                ->integer($result)
                    ->isEqualTo(0)
                ->array($matches)
                    ->isEqualTo([]);
    }

    public function case_match_with_offset ( ) {

        $this
            ->given(
                $pattern = '/💩/u',
                $string  = new LUT('foo 💩 bar')
            )
            ->when($result = $string->match($pattern, $matches, $string::WITH_OFFSET))
            ->then
                ->integer($result)
                    ->isEqualTo(1)
                ->array($matches)
                    ->isEqualTo([
                        0 => [
                            0 => '💩',
                            1 => 4
                        ]
                    ]);
    }

    public function case_match_all_default ( ) {

        $this
            ->given(
                $pattern = '/💩/u',
                $string  = new LUT('foo 💩 bar 💩 baz')
            )
            ->when($result = $string->match($pattern, $matches, 0, 0, true))
            ->then
                ->integer($result)
                    ->isEqualTo(2)
                ->array($matches)
                    ->isEqualTo([
                        0 => [
                            0 => '💩',
                            1 => '💩'
                        ]
                    ]);
    }

    public function case_match_all_with_offset ( ) {

        $this
            ->given(
                $pattern = '/💩/u',
                $string  = new LUT('foo 💩 bar 💩 baz')
            )
            ->when($result = $string->match($pattern, $matches, $string::WITH_OFFSET, 0, true))
            ->then
                ->integer($result)
                    ->isEqualTo(2)
                ->array($matches)
                    ->isEqualTo([
                        0 => [
                            0 => [
                                0 => '💩',
                                1 => 4
                            ],
                            1 => [
                                0 => '💩',
                                1 => 13
                            ]
                        ]
                    ]);
    }

    public function case_match_all_grouped_by_pattern ( ) {

        $this
            ->given(
                $pattern = '/(💩)/u',
                $string  = new LUT('foo 💩 bar 💩 baz')
            )
            ->when($result = $string->match($pattern, $matches, $string::GROUP_BY_PATTERN, 0, true))
            ->then
                ->integer($result)
                    ->isEqualTo(2)
                ->array($matches)
                    ->isEqualTo([
                        0 => [
                            0 => '💩',
                            1 => '💩'
                        ],
                        1 => [
                            0 => '💩',
                            1 => '💩'
                        ]
                    ]);
    }

    public function case_match_all_grouped_by_tuple ( ) {

        $this
            ->given(
                $pattern = '/(💩)/u',
                $string  = new LUT('foo 💩 bar 💩 baz')
            )
            ->when($result = $string->match($pattern, $matches, $string::GROUP_BY_TUPLE, 0, true))
            ->then
                ->integer($result)
                    ->isEqualTo(2)
                ->array($matches)
                    ->isEqualTo([
                        0 => [
                            0 => '💩',
                            1 => '💩'
                        ],
                        1 => [
                            0 => '💩',
                            1 => '💩'
                        ]
                    ]);
    }

    public function case_replace ( ) {

        $this
            ->given($string = new LUT('❤️ 💩 💩'))
            ->when($result = $string->replace('/💩/u', '😄'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('❤️ 😄 😄');
    }

    public function case_replace_limited ( ) {

        $this
            ->given($string = new LUT('❤️ 💩 💩'))
            ->when($result = $string->replace('/💩/u', '😄', 1))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('❤️ 😄 💩');
    }

    public function case_split_default ( ) {

        $this
            ->given($string = new LUT('❤️💩❤️💩❤️'))
            ->when($result = $string->split('/💩/'))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => '❤️',
                        1 => '❤️',
                        2 => '❤️'
                    ]);
    }

    public function case_split_default_limited ( ) {

        $this
            ->given($string = new LUT('❤️💩❤️💩❤️'))
            ->when($result = $string->split('/💩/', 1))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => '❤️💩❤️💩❤️'
                    ]);
    }

    public function case_split_with_delimiters ( ) {

        $this
            ->given($string = new LUT('❤️💩❤️💩❤️'))
            ->when($result = $string->split('/💩/', -1, $string::WITH_DELIMITERS))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => '❤️',
                        1 => '❤️',
                        2 => '❤️'
                    ]);
    }

    public function case_split_with_offset ( ) {

        $this
            ->given($string = new LUT('❤️💩❤️💩❤️'))
            ->when($result = $string->split('/💩/', -1, $string::WITH_OFFSET))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => [
                            0 => '❤️',
                            1 => 0
                        ],
                        1 => [
                            0 => '❤️',
                            1 => 10
                        ],
                        2 => [
                            0 => '❤️',
                            1 => 20
                        ]
                    ]);
    }

    public function case_iterator_ltr ( ) {

        $this
            ->given($string = new LUT('je t\'aime'))
            ->when($result = iterator_to_array($string))
            ->then
                ->array($result)
                    ->isEqualTo([
                        'j',
                        'e',
                        ' ',
                        't',
                        '\'',
                        'a',
                        'i',
                        'm',
                        'e'
                    ]);
    }

    public function case_iterator_rtl ( ) {

        $this
            ->given($string = new LUT('أحبك'))
            ->when($result = iterator_to_array($string))
            ->then
                ->array($result)
                    ->isEqualTo([
                        'أ',
                        'ح',
                        'ب',
                        'ك'
                    ]);
    }

    public function case_to_lower ( ) {

        $this
            ->given($string = new LUT('Σ \'ΑΓΑΠΏ'))
            ->when($result = $string->toLowerCase())
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('σ \'αγαπώ')

            ->given($string = new LUT('JE T\'AIME'))
            ->when($result = $string->toLowerCase())
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('je t\'aime');
    }

    public function case_to_upper ( ) {

        $this
            ->given($string = new LUT('σ \'αγαπώ'))
            ->when($result = $string->toUpperCase())
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('Σ \'ΑΓΑΠΏ')

            ->given($string = new LUT('je t\'aime'))
            ->when($result = $string->toUpperCase())
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('JE T\'AIME');
    }

    public function case_trim_default ( ) {

        $this
            ->given($string = new LUT('💩💩❤️💩💩'))
            ->when($result = $string->trim('💩'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('❤️');
    }

    public function case_trim_beginning ( ) {

        $this
            ->given($string = new LUT('💩💩❤️💩💩'))
            ->when($result = $string->trim('💩', $string::BEGINNING))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('❤️💩💩');
    }

    public function case_trim_end ( ) {

        $this
            ->given($string = new LUT('💩💩❤️💩💩'))
            ->when($result = $string->trim('💩', $string::END))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('💩💩❤️');
    }

    public function case_offset_get_ltr ( ) {

        $this
            ->given($string = new LUT('je t\'aime'))
            ->when($result = $string[0])
            ->then
                ->string($result)
                    ->isEqualTo('j')

            ->when($result = $string[-1])
            ->then
                ->string($result)
                    ->isEqualTo('e');
    }

    public function case_offset_get_rtl ( ) {

        $this
            ->given($string = new LUT('أحبك'))
            ->when($result = $string[0])
            ->then
                ->string($result)
                    ->isEqualTo('أ')

            ->when($result = $string[-1])
            ->then
                ->string($result)
                    ->isEqualTo('ك');
    }

    public function case_offset_set ( ) {

        $this
            ->given($string = new LUT('أحبﻙ'))
            ->when($string[-1] = 'ك')
            ->then
                ->string((string) $string)
                    ->isEqualTo('أحبك');
    }

    public function case_offset_unset ( ) {

        $this
            ->given($string = new LUT('أحبك😄'))
            ->when(function ( ) use ( $string ) {

                unset($string[-1]);
            })
            ->then
                ->string((string) $string)
                    ->isEqualTo('أحبك');
    }

    public function case_reduce ( ) {

        $this
            ->given($string = new LUT('أحبك'))
            ->when($result = $string->reduce(0, 1))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('أ');
    }

    public function case_count ( ) {

        $this
            ->given($string = new LUT('je t\'aime'))
            ->when($result = count($string))
            ->then
                ->integer($result)
                    ->isEqualTo(9)

            ->given($string = new LUT('أحبك'))
            ->when($result = count($string))
            ->then
                ->integer($result)
                    ->isEqualTo(4)

            ->given($string = new LUT('💩'))
            ->when($result = count($string))
            ->then
                ->integer($result)
                    ->isEqualTo(1);
    }

    public function case_byte_at ( ) {

        $this
            ->given($string = new LUT('💩'))
            ->when($result = $string->getByteAt(0))
            ->then
                ->integer(ord($result))
                    ->isEqualTo(0xf0)

            ->when($result = $string->getByteAt(1))
            ->then
                ->integer(ord($result))
                    ->isEqualTo(0x9f)

            ->when($result = $string->getByteAt(2))
            ->then
                ->integer(ord($result))
                    ->isEqualTo(0x92)

            ->when($result = $string->getByteAt(3))
            ->then
                ->integer(ord($result))
                    ->isEqualTo(0xa9)

            ->when($result = $string->getByteAt(-1))
            ->then
                ->integer(ord($result))
                    ->isEqualTo(0xa9);
    }

    public function case_bytes_length ( ) {

        $this
            ->given($string = new LUT('💩'))
            ->when($result = $string->getBytesLength())
            ->then
                ->integer($result)
                    ->isEqualTo(4);
    }

    public function case_get_width ( ) {

        $this
            ->given($string = new LUT('💩'))
            ->when($result = $string->getWidth())
            ->then
                ->integer($result)
                    ->isEqualTo(1)

            ->given($string = new LUT('習'))
            ->when($result = $string->getWidth())
            ->then
                ->integer($result)
                    ->isEqualTo(2);
    }

    public function case_get_char_direction ( ) {

        $this
            ->when($result = LUT::getCharDirection('A'))
            ->then
                ->integer($result)
                    ->isEqualTo(LUT::LTR)

            ->when($result = LUT::getCharDirection('ا'))
            ->then
                ->integer($result)
                    ->isEqualTo(LUT::RTL);
    }

    public function case_from_code ( ) {

        $this
            // U+0000 to U+007F
            ->when($result = LUT::fromCode(0x7e))
            ->then
                ->string($result)
                    ->isEqualTo('~')

            // U+0080 to U+07FF
            ->when($result = LUT::fromCode(0xa7))
            ->then
                ->string($result)
                    ->isEqualTo('§')

            // U+0800 to U+FFFF
            ->when($result = LUT::fromCode(0x1207))
            ->then
                ->string($result)
                    ->isEqualTo('ሇ')

            // U+10000 to U+10FFFF
            ->when($result = LUT::fromCode(128169))
            ->then
                ->string($result)
                    ->isEqualTo('💩');
    }

    public function case_to_code ( ) {

        $this
            // U+0000 to U+007F
            ->when($result = LUT::toCode('~'))
            ->then
                ->integer($result)
                    ->isEqualTo(0x7e)

            // U+0080 to U+07FF
            ->when($result = LUT::toCode('§'))
            ->then
                ->integer($result)
                    ->isEqualTo(0xa7)

            // U+0800 to U+FFFF
            ->when($result = LUT::toCode('ሇ'))
            ->then
                ->integer($result)
                    ->isEqualTo(0x1207)

            // U+10000 to U+10FFFF
            ->when($result = LUT::toCode('💩'))
            ->then
                ->integer($result)
                    ->isEqualTo(128169);
    }

    public function case_to_binary_code ( ) {

        $this
            // U+0000 to U+007F
            ->when($result = LUT::toBinaryCode('~'))
            ->then
                ->string($result)
                    ->isEqualTo('00000000000000000000000001111110')

            // U+0080 to U+07FF
            ->when($result = LUT::toBinaryCode('§'))
            ->then
                ->string($result)
                    ->isEqualTo('00000000000000000000000010100111')

            // U+0800 to U+FFFF
            ->when($result = LUT::toBinaryCode('ሇ'))
            ->then
                ->string($result)
                    ->isEqualTo('00000000000000000001001000000111')

            // U+10000 to U+10FFFF
            ->when($result = LUT::toBinaryCode('💩'))
            ->then
                ->string($result)
                    ->isEqualTo('00000000000000011111010010101001');
    }

    public function case_transcode_and_isUtf8 ( ) {

        $this
            ->given($uΣ = 'Σ')
            ->when($Σ = LUT::transcode($uΣ, 'UTF-8', 'UTF-16'))
            ->then
                ->string($Σ)
                    ->isNotEqualTo($uΣ)
                ->boolean(LUT::isUtf8($Σ))
                    ->isFalse()

            ->when($Σ = LUT::transcode($Σ, 'UTF-16', 'UTF-8'))
                ->string($Σ)
                    ->isEqualTo($uΣ)
                ->boolean(LUT::isUtf8($Σ))
                    ->isTrue()
                ->boolean(LUT::isUtf8($uΣ))
                    ->isTrue();
    }

    public function case_to_ascii_no_normalizer ( ) {

        $this
            ->given(
                $this->function->class_exists = function ( $name ) {

                    if('Normalizer' === $name)
                        return false;

                    return true;
                },
                $string = new LUT('Un été brûlant sur la côte')
            )
            ->exception(function ( ) use ( $string ) {

                $string->toAscii();
            })
                ->isInstanceOf('Hoa\String\Exception');
    }

    public function case_to_ascii_no_normalizer_try ( ) {

        $this
            ->given(
                $this->function->class_exists = function ( $name ) {

                    if('Normalizer' === $name)
                        return false;

                    return true;
                },
                $string = new LUT('Un été brûlant sur la côte')
            )
            ->when($result = $string->toAscii(true))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('Un ete brulant sur la cote');
    }

    public function case_to_ascii ( ) {

        $this
            ->given($string = new LUT('Un été brûlant sur la côte'))
            ->when($result = $string->toAscii())
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('Un ete brulant sur la cote');
    }

    public function case_copy ( ) {

        $this
            ->given($string = new LUT('foo'))
            ->when($result = $string->copy())
            ->then
                ->object($result)
                    ->isEqualTo($string);
    }

    public function case_toString ( ) {

        $this
            ->given(
                $datum  = $this->sample($this->realdom->regex('/\w{7,42}/')),
                $string = new LUT($datum)
            )
            ->when($result = (string) $string)
            ->then
                ->string($result)
                    ->isEqualTo($datum);
    }
}
