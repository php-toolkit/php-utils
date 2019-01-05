<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-01-06
 * Time: 00:09
 */

namespace Toolkit\PhpUtil;

/**
 * Class PhpDoc
 * @package Toolkit\PhpUtil
 */
class PhpDoc
{
    /**
     * 以下三个方法来自 yii2 console/Controller.php 做了一些调整
     */

    /**
     * Parses the comment block into tags.
     * @param string $comment The comment block text
     * @param array  $ignored
     * @return array The parsed tags
     */
    public static function getTags(string $comment, array $ignored = ['param', 'return']): array
    {
        $comment = (string)\str_replace("\r\n", "\n", \trim($comment, "/ \n"));
        $comment = "@description \n" . \str_replace("\r", '',
                \trim(\preg_replace('/^\s*\**( |\t)?/m', '', $comment))
            );

        $tags = [];
        $parts = \preg_split('/^\s*@/m', $comment, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($parts as $part) {
            if (\preg_match('/^(\w+)(.*)/ms', \trim($part), $matches)) {
                $name = $matches[1];
                if (\in_array($name, $ignored, true)) {
                    continue;
                }

                if (!isset($tags[$name])) {
                    $tags[$name] = \trim($matches[2]);
                } elseif (\is_array($tags[$name])) {
                    $tags[$name][] = \trim($matches[2]);
                } else {
                    $tags[$name] = [$tags[$name], \trim($matches[2])];
                }
            }
        }

        return $tags;
    }

    /**
     * Returns the first line of docBlock.
     *
     * @param string $comment
     * @return string
     */
    public static function firstLine(string $comment): string
    {
        $docLines = \preg_split('~\R~u', $comment);

        if (isset($docLines[1])) {
            return \trim($docLines[1], "/\t *");
        }

        return '';
    }

    /**
     * Returns full description from the doc-block.
     * If have multi line text, will return multi line.
     *
     * @param string $comment
     * @return string
     */
    public static function description(string $comment): string
    {
        $comment = (string)\str_replace("\r", '', \trim(\preg_replace('/^\s*\**( |\t)?/m', '', trim($comment, '/'))));

        if (\preg_match('/^\s*@\w+/m', $comment, $matches, PREG_OFFSET_CAPTURE)) {
            $comment = \trim(\substr($comment, 0, $matches[0][1]));
        }

        return $comment;
    }
}