<?php

include 'vendor/autoload.php';

use Garden\Cli\Cli;
use Doctrine\Common\Annotations\DocLexer;

class OAAnnotationGenerator
{
    private $annotation;
    private $lexer;

    public function load($annotation)
    {
        $this->annotation = $annotation;
    }

    public static function indent($string)
    {
        $str = explode("\n", $string);
        foreach ($str as &$s) {
            $s = '    '.$s;
        }

        return implode("\n", $str);
    }

    public static function appendComment($string)
    {
        $str = explode("\n", $string);
        foreach ($str as &$s) {
            $s = '     * '.$s;
        }

        return "    /**\n".implode("\n", $str)."\n     */";
    }

    public static function removeComment($string)
    {
        return preg_replace('/^ *\/?\*+[ \*]*/m', '', $string);
    }

    public static function findClosingBracket($string)
    {
    }

    public function parseOATag($string)
    {
        $matches = [];
        preg_match('/@OA\\\([a-zA-Z]+) *(\()/', $string, $matches, PREG_OFFSET_CAPTURE);

        // var_dump($matches[2][1]);

        return $string;
    }

    private $indent_count = 0;
    public int $tab_size = 4;

    public function newLine()
    {
        $this->output .= "\n".str_repeat(' ', $indent_count * $tab_size);
    }

    public function generate()
    {
        $rc = '';
        $this->lexer = new DocLexer();
        $this->lexer->setInput($this->annotation);
        $this->lexer->moveNext();

        $tokens = [];
        while (null !== $this->lexer->lookahead) {
            $tokens[] = $this->lexer->lookahead;
            $this->lexer->moveNext();
        }

        $indent_count = 0;
        $pad = '';
        $curly_count = 0;
        for ($i = 0; $i < count($tokens); ++$i) {
            $token = $tokens[$i];
            if (DocLexer::T_OPEN_PARENTHESIS == $token['type']) {
                ++$indent_count;
                $pad = str_repeat(' ', $indent_count * 4);
                $rc .= "(\n".$pad;
            } elseif (DocLexer::T_CLOSE_PARENTHESIS == $token['type']) {
                --$indent_count;
                $pad = str_repeat(' ', $indent_count * 4);
                $rc .= "\n".$pad.')';
            // if($tokens[$i+1]['type'] ==DocLexer::T_COMMA)
            // {
                //     $i++;
                //     $rc .= ',' . "\n$pad";
            // }
            } elseif (DocLexer::T_IDENTIFIER == $token['type'] && DocLexer::T_EQUALS == $tokens[$i + 1]['type'] && DocLexer::T_STRING == $tokens[$i + 2]['type'] && DocLexer::T_COMMA == $tokens[$i + 3]['type']) {
                $rc .= $token['value'].'='.'"'.$tokens[$i + 2]['value'].'"'.','."\n{$pad}";
                $i += 3;
            } elseif (DocLexer::T_IDENTIFIER == $token['type'] && DocLexer::T_EQUALS == $tokens[$i + 1]['type'] && DocLexer::T_INTEGER == $tokens[$i + 2]['type'] && DocLexer::T_COMMA == $tokens[$i + 3]['type']) {
                $rc .= $token['value'].'='.''.$tokens[$i + 2]['value'].''.','."\n{$pad}";
                $i += 3;
            } elseif (DocLexer::T_IDENTIFIER == $token['type']) {
                $rc .= $token['value'];
            } elseif (DocLexer::T_EQUALS == $token['type']) {
                $rc .= '=';
            } elseif (DocLexer::T_STRING == $token['type']) {
                $rc .= '"'.$token['value'].'"';
            } elseif (DocLexer::T_INTEGER == $token['type']) {
                $rc .= ''.$token['value'].'';
            } elseif (DocLexer::T_AT == $token['type']) {
                $rc .= '@';
            } elseif (DocLexer::T_OPEN_CURLY_BRACES == $token['type']) {
                $rc .= '{';
                ++$curly_count;
            } elseif (DocLexer::T_CLOSE_CURLY_BRACES == $token['type']) {
                $rc .= '}';
                --$curly_count;
                if (DocLexer::T_COMMA == $tokens[$i + 1]['type'] && DocLexer::T_CLOSE_PARENTHESIS == $tokens[$i + 2]['type']) {
                    ++$i;
                } elseif (DocLexer::T_COMMA == $tokens[$i + 1]['type']) {
                    ++$i;
                    $rc .= ',';
                    if (0 === $curly_count) {
                        $rc .= "\n{$pad}";
                    }
                }
            } elseif (DocLexer::T_COMMA == $token['type']) {
                if (DocLexer::T_CLOSE_PARENTHESIS == $tokens[$i - 1]['type'] && DocLexer::T_CLOSE_PARENTHESIS == $tokens[$i + 1]['type']) {
                    ++$i;
                    --$indent_count;
                    $pad = str_repeat(' ', $indent_count * 4);
                    $rc .= "\n{$pad})";
                } elseif (DocLexer::T_CLOSE_PARENTHESIS == $tokens[$i - 1]['type']) {
                    $rc .= ",\n{$pad}";
                } else {
                    $rc .= ',';
                }
            } elseif (DocLexer::T_COLON == $token['type']) {
                $rc .= ':';
            } elseif (DocLexer::T_FALSE == $token['type']) {
                $rc .= 'false';
            } elseif (DocLexer::T_TRUE == $token['type']) {
                $rc .= 'true';
            } elseif (DocLexer::T_NULL == $token['type']) {
                $rc .= 'null';
            } elseif (DocLexer::T_NONE == $token['type']) {
                // $rc .= 'null';
            } else {
                var_dump($token);
            }
        }

        while ($indent_count > 0) {
            --$indent_count;
            $pad = str_repeat(' ', $indent_count * 4);
            $rc .= "\n".$pad.')';
        }

        return self::appendComment($rc);
    }
}

function replaceCallBack($arr)
{
    $gen = new OAAnnotationGenerator();
    $gen->load($arr[0]);

    // var_dump($arr[0]);
    return $gen->generate();
}

$cli = new Cli();
$cli->description('Beautify OA annocations inside your code.')
    ->opt('file:f', 'file or folder to scan for php files and fix.', true)
;

$args = $cli->parse($argv, true);

$filename = $args->getOpt('file');

$dir_iterator = new \RecursiveDirectoryIterator($filename);
$Iterator = new RecursiveIteratorIterator($dir_iterator);
$Regex = new RegexIterator($Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
foreach ($Regex as $f) {
    var_dump($f[0]);
    $filename = $f[0];

    $file_content = file_get_contents($filename);
    $cleaned_code = preg_replace_callback('/ *\/\*\*[\*\s]*\@OA.*\*\//smU', 'replaceCallBack', $file_content);
    // var_dump("\n".$cleaned_code);
    file_put_contents($filename, $cleaned_code);
}
