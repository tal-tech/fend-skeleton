<?php

namespace Installer;

class Color
{
    /**
     * text style
     */
    const STYLE_NORMAL = 0;
    const STYLE_BRIGHT = 1;
    const STYLE_DIM = 2;
    const STYLE_UNDERLINE = 3;
    const STYLE_BLINK = 5;
    const STYLE_REVERSE = 7;
    const STYLE_HIDDEN = 8;

    /**
     * font color
     */
    const FONT_BLACK = 30;
    const FONT_RED = 31;
    const FONT_GREEN = 32;
    const FONT_YELLOW = 33;
    const FONT_BLUE = 34;
    const FONT_MAGENTA = 35;
    const FONT_CYAN = 36;
    const FONT_WHITE = 37;

    /**
     * backgroud color
     */
    const BG_BLACK = 40;
    const BG_RED = 41;
    const BG_GREEN = 42;
    const BG_YELLOW = 43;
    const BG_BLUE = 44;
    const BG_MAGENTA = 45;
    const BG_CYAN = 46;
    const BG_WHITE = 47;

    /**
     * 彩色 输出文字
     * @param string $text 文字内容
     * @param int $style 文字样式
     * @param int $fg 文字颜色
     * @param int $bg 文字背景颜色
     * @return string
     */
    public static function colorFormat($text, $style = Color::STYLE_NORMAL, $fg = Color::FONT_WHITE, $bg = Color::BG_BLACK)
    {
        return "\033[$style;$fg;$bg" . "m $text \033[0m";
    }

    public static function colorFormatPrefix($text, $style = Color::STYLE_NORMAL, $fg = Color::FONT_WHITE, $bg = Color::BG_BLACK)
    {
        return "\033[$style;$fg;$bg" . "m $text";
    }

    public static function colorTable($mask, $data)
    {
        return sprintf($mask, ...$data);
    }

}