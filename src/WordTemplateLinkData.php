<?php

namespace Alterindonesia\Procurex;

class WordTemplateLinkData
{
    /**
     * @param  string  $text the run of text to be inserted (required)
     * @param  string  $url URL or #bookmarkName (required)
     * @param  array|null  $options
     *   Possible keys and values:
     *   'pStyle' (string) paragraph style to be used
     *   'backgroundColor' (string) hexadecimal value (FFFF00, CCCCCC, ...)
     *   'bidi' (bool) if true sets right to left paragraph orientation
     *   'bold' (bool)
     *   'border' (none, single, double, dashed, threeDEngrave, threeDEmboss, outset, inset, ...)
     *        this value can be override for each side with 'borderTop', 'borderRight', 'borderBottom' and 'borderLeft'
     *   'borderColor' (ffffff, ff0000)
     *        this value can be override for each side with 'borderTopColor', 'borderRightColor', 'borderBottomColor' and 'borderLeftColor'
     *   'borderSpacing' (0, 1, 2...)
     *        this value can be override for each side with 'borderTopSpacing', 'borderRightSpacing', 'borderBottomSpacing' and 'borderLeftSpacing'
     *   'borderWidth' (10, 11...) in eights of a point
     *        this value can be override for each side with 'borderTopWidth', 'borderRightWidth', 'borderBottomWidth' and 'borderLeftWidth'
     *   'caps' (bool) display text in capital letters
     *   'color' (ffffff, ff0000...)
     *   'contextualSpacing' (bool) ignore spacing above and below when using identical styles
     *   'doubleStrikeThrough' (bool)
     *   'em' (none, dot, circle, comma, underDot) emphasis mark type
     *   'emboss' (bool) emboss style
     *   'firstLineIndent' first line indent in twentieths of a point (twips)
     *   'font' (Arial, Times New Roman...)
     *   'fontSize' (8, 9, 10, ...) size in points
     *   'hanging' 100, 200, ...
     *   'headingLevel' (int) the heading level, if any
     *   'indentLeft' 100, ...
     *   'indentRight' 100, ...
     *   'italic' (bool)
     *   'keepLines' (bool) keep all paragraph lines on the same page
     *   'keepNext' (bool) keep in the same page the current paragraph with next paragraph
     *   'lineSpacing' 120, 240 (standard), 360, 480...
     *   'noProof' (bool) ignore spelling and grammar errors
     *   'outline' (bool) outline style
     *   'pageBreakBefore' (bool)
     *   'parseLineBreaks' (bool) if true (default is false) parses the line breaks to include them in the Word document
     *   'position' (int) position value, positive value for raised and negative value for lowered
     *   'rtl' (bool) if true sets right to left text orientation
     *   'scaling' (int) scaling value, 100 is the default value
     *   'shadow' (bool) shadow style
     *   'smallCaps' (bool) displays text in small capital letters
     *   'spacing' (int) character spacing, positive value for expanded and negative value for condensed
     *   'spacingBottom' (int) bottom margin in twentieths of a point
     *   'spacingTop' (int) top margin in twentieths of a point
     *   'strikeThrough' (bool)
     *   'suppressLineNumbers' (bool) suppress line numbers
     *   'tabPositions' (array) each entry is an associative array with the following keys and values
     *        'type' (string) can be clear, left (default), center, right, decimal, bar and num
     *        'leader' (string) can be none (default), dot, hyphen, underscore, heavy and middleDot
     *        'position' (int) given in twentieths of a point
     *    if there is a tab and the tabPositions array is not defined the standard tab position (default of 708) will be used
     *   'textAlign' (both, center, distribute, left, right)
     *   'textDirection' (lrTb, tbRl, btLr, lrTbV, tbRlV, tbLrV) text flow direction
     *   'underline' (none, dash, dotted, double, single, wave, words)
     *   'underlineColor' (ffffff, ff0000, ...)
     *   'vanish' (bool)
     *   'widowControl' (bool)
     *   'wordWrap' (bool)
     */
    public function __construct(
        public readonly string $text,
        public readonly string $url,
        public readonly ?array $options = null
    ){
    }
}