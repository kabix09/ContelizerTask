<?php

function validArguments(array $args)
{
    if(!isset($argv[1]))
        throw new Exception(('Input filename is required!!!'));

    if(!file_exists($argv[1]))
        throw new Exception("{$argv[1]} file was not found");

}

function scheduleContent(string $word): string
{
    if(strlen($word) <= 2)
        return $word;

    /**
     * Get
     * - first char
     * - last char
     * - rest of the letters in passed string
     */
    $firstLetter = mb_substr($word, 0, 1, 'UTF-8');
    $lastLetter = mb_substr($word, -1, 1, 'UTF-8');
    $core = mb_substr($word, 1, -1, 'UTF-8');

    /** mix content */
    $core = str_split($core);

    shuffle($core);

    $core = implode('', $core);

    return sprintf('%s%s%s', $firstLetter, $core, $lastLetter);
}

function parseContent(string $content): string
{
    $lines = explode("\n", $content);

    foreach ($lines as $index => &$line)
    {
        /**
         * preg_split() - function to split string
         * '/(\s+)/u' -
         *      1. (\s+) - split based on whitespaces like tabs or spaces
         *      2. /u - UTF-8 encoding
         * -1 - max amount of splits
         * PREG_SPLIT_DELIM_CAPTURE - it's a flag meaning whitespaces won't be loss
         */
        $words = preg_split('/(\s+)/u', $line, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach ($words as $index => &$word)
        {
            /** Note: don't use \s+ or \t+ */
            $clearPattern = '/([^a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+)/u';

            $clearWords = preg_split(pattern: $clearPattern, subject: $word, limit: -1, flags: PREG_SPLIT_DELIM_CAPTURE);

            $newWord = '';
            foreach ($clearWords as $clearWord)
            {

                if (!preg_match(pattern: $clearPattern, subject: $clearWord) && strlen($clearWord) > 0) {
                    $newWord .= scheduleContent($clearWord);
                } else {
                    $newWord .= $clearWord;
                }
            }

            $word = $newWord;
        }

        $line = implode('', $words);
    }

    return implode("\n", $lines);
}

try {
    /** Valid input arguments */
    validArguments($argv);

    /**
     * Get file content
     * @var string $content
     */
    $content = file_get_contents($argv[1]);

    if(!isset($content))
        throw new Exception("{$argv[1]} file is empty");

    /** @var string $shuffledText */
    $shuffledText = parseContent($content);

    /** save result in new file */
    file_put_contents("output.txt", implode("\n", $shuffledText));

}catch (Exception $e) {
    printf("%s", $e->getMessage());
}





