<?php

namespace SentimentAnalysis;

use SentimentAnalysis\Contracts\DictionaryInterface;

class Dictionary implements DictionaryInterface
{
    /**
     * Dictionary data directory.
     *
     * @var string
     */
    public $dataDirectory;

    /**
     * Dictionary's positive words.
     *
     * @var array
     */
    protected $positiveWords;

    /**
     * Dictionary's negative words.
     *
     * @var array
     */
    protected $negativeWords;

    /**
     * Dictionary's neutral words.
     *
     * @var array
     */
    protected $neutralWords;

    /**
     * Dictionary's negation words.
     *
     * @var array
     */
    protected $negationWords;

    /**
     * Dictionary's ignored words.
     *
     * @var array
     */
    protected $ignoredWords;

    /**
     * Create a new instance of Dictionary class.
     *
     * @param string $dataDirectory
     */
    public function __construct($dataDirectory)
    {
        $this->dataDirectory = $dataDirectory;

        $this->loadWordsForAllCategories($dataDirectory);
    }

    /**
     * Get dictionary data directory.
     *
     * @return string
     */
    public function dataDirectory()
    {
        return $this->dataDirectory;
    }

    /**
     * Load all words for all categories.
     *
     * @param string $dataDirectory
     *
     * @return \SentimentAnalysis\Contracts\DictionaryInterface
     */
    public function loadWordsForAllCategories($dataDirectory)
    {
        $this->positiveWords = $this->loadWordsForCategory($dataDirectory, 'positive');
        $this->negativeWords = $this->loadWordsForCategory($dataDirectory, 'negative');
        $this->neutralWords = $this->loadWordsForCategory($dataDirectory, 'neutral');
        $this->negationWords = $this->loadWordsForCategory($dataDirectory, 'negation');
        $this->ignoredWords = $this->loadWordsForCategory($dataDirectory, 'ignored');

        return $this;
    }

    /**
     * Load words for a given category.
     *
     * @param string $dataDirectory
     * @param string $category
     *
     * @return array
     */
    public function loadWordsForCategory($dataDirectory, $category)
    {
        $words = require "{$dataDirectory}/{$category}.php";

        $words = array_map(function ($word) {
            return trim($word);
        }, $words);

        return array_unique($words);
    }

    /**
     * Get all positive words.
     *
     * @return array
     */
    public function positiveWords()
    {
        return $this->positiveWords;
    }

    /**
     * Get all negative words.
     *
     * @return array
     */
    public function negativeWords()
    {
        return $this->negativeWords;
    }

    /**
     * Get all neutral words.
     *
     * @return array
     */
    public function neutralWords()
    {
        return $this->neutralWords;
    }

    /**
     * Get all negation words.
     *
     * @return array
     */
    public function negationWords()
    {
        return $this->negationWords;
    }

    /**
     * Get all ignored words.
     *
     * @return array
     */
    public function ignoredWords()
    {
        return $this->ignoredWords;
    }

    /**
     * Check whether word is found on the given category.
     *
     * @param string $word
     * @param string $category
     *
     * @return bool
     */
    public function isWordFoundOnCategory($word, $category)
    {
        $categoryWords = "{$category}Words";

        return in_array($word, $this->{$categoryWords});
    }
}
