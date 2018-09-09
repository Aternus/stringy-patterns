<?php

namespace Stringy;

use InvalidArgumentException;

/**
 * Class Patterns
 *
 * pat·tern : (noun) an arrangement or sequence regularly found in comparable objects or events.
 *
 * @package Stringy
 */
class Patterns
{
    /**
     * An instance's string.
     *
     * @var string
     */
    protected $str;

    /**
     * The string's encoding, which should be one of the mbstring module's supported encodings.
     *
     * @var string
     */
    protected $encoding;

    /**
     * Initializes a Patterns object and assigns both str and encoding properties the supplied values.
     * $str is cast to a string prior to assignment, and if $encoding is not specified,
     * it defaults to mb_detect_encoding().
     *
     * Throws an InvalidArgumentException if the first argument is an array or object without a __toString method.
     *
     * @param  mixed  $str      Value to modify, after being cast to string
     * @param  string $encoding The character encoding
     *
     * @throws \InvalidArgumentException if an array or object without a __toString method is passed as the first
     *                                   argument
     */
    public function __construct($str = '', $encoding = null)
    {
        if (is_array($str)) {
            throw new InvalidArgumentException(
                'Passed value cannot be an array'
            );
        } elseif (is_object($str) && !method_exists($str, '__toString')) {
            throw new InvalidArgumentException(
                'Passed object must have a __toString method'
            );
        }

        $this->str = (string)$str;
        $this->encoding = $encoding ?: \mb_detect_encoding($str);

        \mb_internal_encoding($this->encoding);
        \mb_regex_encoding($this->encoding);
    }

    /**
     * Creates a Patterns object and assigns both str and encoding properties the supplied values.
     * $str is cast to a string prior to assignment, and if $encoding is not specified,
     * it defaults to mb_detect_encoding().
     *
     * Throws an InvalidArgumentException if the first argument is an array or object without a __toString method.
     *
     * @param  mixed  $str      Value to modify, after being cast to string
     * @param  string $encoding The character encoding
     *
     * @return static A Patterns object
     *
     * @throws \InvalidArgumentException if an array or object without a __toString method is passed as the first
     *                                   argument
     */
    public static function create($str = '', $encoding = null)
    {
        return new static($str, $encoding);
    }

    /**
     * Returns the value in $str.
     *
     * @return string The current value of the $str property
     */
    public function __toString()
    {
        return $this->str;
    }

    /**
     * Returns the encoding used by the Patterns object.
     *
     * @return string The current value of the $encoding property
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Returns an key value array where keys are the patterns and the values are the occurrences.
     *
     * When $withSingleOccurrences is <i>true</i>, substrings which were suspected as a pattern
     * but didn't qualify will return NULL.
     *
     * @param bool $withSingleOccurrences [optional]
     *                                    <p>
     *                                    When set to true will also return the substrings which only have a single
     *                                    occurrence
     *                                    </p>
     *
     * @return array a key value array where the keys are the patterns and the values are the occurrences
     */
    public function getPatterns($withSingleOccurrences = false)
    {
        $results_table = [];
        $string_length = $this->strlen($this->str);
        $split_by = 1;
        $shift_idx = 0;

        while ($string_length) {
            $string_length = $this->strlen($this->str);

            while ($split_by <= $string_length) {
                // split the string into pieces by using the split by number
                $pieces = [];
                $tmp_string = $this->str;
                $tmp_string_length = $this->strlen($tmp_string);
                while ($tmp_string_length) {
                    $pieces[] = $this->substr($tmp_string, 0, $split_by);
                    $tmp_string = $this->substr($tmp_string, $split_by);
                    $tmp_string_length = $this->strlen($tmp_string);
                }

                // for each piece
                foreach ($pieces as $piece) {
                    // if the pattern is not inside the results table
                    if (!isset($results_table[$piece])) {
                        // count the number of times it appears in the string
                        $count = $this->substr_count($this->str, $piece);
                        if ($count > 1) {
                            // insert the pattern and count into the results table
                            $results_table[$piece] = $count;
                        } else {
                            // insert the pattern and NULL as count into the results table
                            $results_table[$piece] = null;
                        }
                    }
                }
                $split_by++;
            }

            $this->str = $this->substr($this->str, ++$shift_idx);
            // set split by number to 2
            // if we split by 1 again it will be a waste of resources since
            // we're 100% sure that we've already checked these patterns
            $split_by = 2;
        }

        if ($withSingleOccurrences) {
            return $results_table;
        }

        return array_filter($results_table, function ($v) {
            return $v !== null;
        });
    }

    /**
     * Get string length
     *
     * @param string $string <p>
     *                       The string being measured for length.
     *                       </p>
     *
     * @return int The length of the <i>string</i> on success,
     *             and 0 if the <i>string</i> is empty.
     */
    protected function strlen($string)
    {
        if ($this->encoding !== 'ASCII') {
            return \mb_strlen($string, $this->encoding);
        }

        return \strlen($string);
    }

    /**
     * Return part of a string
     *
     * @param string $string <p>
     *                       The input string.
     *                       </p>
     * @param int    $start  <p>
     *                       If start is non-negative, the returned string will start at the start'th position in
     *                       string, counting from zero.
     *                       </p>
     *                       <p>
     *                       If start is negative, the returned string will start at the start'th character
     *                       from the end of string.
     *                       </p>
     *                       <p>
     *                       If string is less than or equal to start characters long, false will be returned.
     *                       </p>
     * @param int    $length [optional]
     *                       <p>
     *                       If length is given and is positive, the string returned will contain
     *                       at most length characters beginning from start (depending on the length of string).
     *                       </p>
     *                       <p>
     *                       If length is given and is negative, then that many characters will be omitted from
     *                       the end of string (after the start position has been calculated when a
     *                       start is negative).
     *                       </p>
     *                       <p>
     *                       If start denotes a position beyond this truncation, an empty string will be returned.
     *                       </p>
     *                       <p>
     *                       If length is given and is 0, false or null; an empty string will be returned.
     *                       </p>
     *
     * @return string|bool the extracted part of string or false on failure.
     */
    protected function substr($string, $start, $length = 2147483647)
    {
        if ($this->encoding !== 'ASCII') {
            return \mb_substr($string, $start, $length);
        }

        return \substr($string, $start, $length);
    }

    /**
     * Count the number of substring occurrences
     *
     * @param string $haystack <p>
     *                         The string to search in
     *                         </p>
     * @param string $needle   <p>
     *                         The substring to search for
     *                         </p>
     *
     * @return int This functions returns an integer.
     */
    protected function substr_count($haystack, $needle) // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    {
        if ($this->encoding !== 'ASCII') {
            return \mb_substr_count($haystack, $needle);
        }

        return \substr_count($haystack, $needle);
    }
}
