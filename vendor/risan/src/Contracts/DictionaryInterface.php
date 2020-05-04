<?php

namespace SentimentAnalysis\Contracts;

interface DictionaryInterface
{
    /**
     * Get dictionary data directory.
     *
     * @return string
     */
    public function dataDirectory();

    /**
     * Load all words for all categories.
     *
     * @param string $dataDirectory
     *
     * @return \SentimentAnalysis\Contracts\DictionaryInterface
     */
    public function loadWordsForAllCategories($dataDirectory);

    /**
     * Get all positive words.
     *
     * @return array
     */
    public function positiveWords();

    /**
     * Get all negative words.
     *
     * @return array
     */
    public function negativeWords();

    /**
     * Get all neutral words.
     *
     * @return array
     */
    public function neutralWords();

    /**
     * Get all negation words.
     *
     * @return array
     */
    public function negationWords();

    /**
     * Get all ignored words.
     *
     * @return array
     */
    public function ignoredWords();

    /**
     * Check whether word is found on the given category.
     *
     * @param string $word
     * @param string $category
     *
     * @return bool
     */
    public function isWordFoundOnCategory($word, $category);
}
