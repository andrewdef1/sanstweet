<?php

namespace SentimentAnalysis;

use SentimentAnalysis\Contracts\AnalyzerInterface;
use SentimentAnalysis\Contracts\TokenizerInterface;
use SentimentAnalysis\Contracts\DictionaryInterface;
use SentimentAnalysis\Contracts\TokenValidatorInterface;

class Analyzer implements AnalyzerInterface
{
    /**
     * Sentiment categories.
     *
     * @var array
     */
    protected $categories = [
        'positive',
        'negative',
        'neutral',
    ];

    /**
     * Prior probalbility of each category.
     *
     * @var array
     */
    protected $priorProbability = [
        'positive' => 0.333333333333,
        'negative' => 0.333333333333,
        'neutral' => 0.333333333334,
    ];

    /**
     * Dictionary instance.
     *
     * @var \SentimentAnalysis\Contracts\DictionaryInterface
     */
    protected $dictionary;

    /**
     * Tokenizer instance.
     *
     * @var \SentimentAnalysis\Contracts\TokenizerInterface
     */
    protected $tokenizer;

    /**
     * Token validator instance.
     *
     * @var \SentimentAnalysis\Contracts\TokenValidatorInterface
     */
    protected $tokenValidator;

    /**
     * Create a new instance of Analyzer class.
     *
     * @param \SentimentAnalysis\Contracts\DictionaryInterface     $dictionary
     * @param \SentimentAnalysis\Contracts\TokenizerInterface      $tokenizer
     * @param \SentimentAnalysis\Contracts\TokenValidatorInterface $tokenValidator
     */
    public function __construct(
        DictionaryInterface $dictionary,
        TokenizerInterface $tokenizer,
        TokenValidatorInterface $tokenValidator
    ) {
        $this->dictionary = $dictionary;
        $this->tokenizer = $tokenizer;
        $this->tokenValidator = $tokenValidator;
    }

    /**
     * Get dictionary instance.
     *
     * @return \SentimentAnalysis\Contracts\DictionaryInterface
     */
    public function dictionary()
    {
        return $this->dictionary;
    }

    /**
     * Get tokenizer instance.
     *
     * @return \SentimentAnalysis\Contracts\TokenizerInterface
     */
    public function tokenizer()
    {
        return $this->tokenizer;
    }

    /**
     * Get token validator instance.
     *
     * @return \SentimentAnalysis\Contracts\TokenValidatorInterface
     */
    public function tokenValidator()
    {
        return $this->tokenValidator;
    }

    /**
     * Analyze document.
     *
     * @param string $document
     *
     * @return \SentimentAnalysis\Contracts\ResultInterface
     */
    public function analyze($document)
    {
        $tokens = $this->cleanUpAndTokenizeDocument($document);

        $scores = [];

        foreach ($this->categories as $category) {
            $scores[$category] = $this->calculateTokensScore($tokens, $category);
        }

        $scores = $this->normalizeScoreValues($scores);

        return new Result($scores);
    }

    /**
     * Clean up and tokenize document.
     *
     * @param string $document
     *
     * @return array
     */
    protected function cleanUpAndTokenizeDocument($document)
    {
        $document = $this->removeWhiteSpaceAfterNegationWords($document);

        return $this->tokenizer()->tokenize($document);
    }

    /**
     * Remove white space after negation words.
     *
     * @param string $document
     *
     * @return string
     */
    protected function removeWhiteSpaceAfterNegationWords($document)
    {
        foreach ($this->dictionary()->negationWords() as $negationWord) {
            if (strpos($document, $negationWord) !== false) {
                $document = str_replace("{$negationWord} ", $negationWord, $document);
            }
        }

        return $document;
    }

    /**
     * Calculate tokens score.
     *
     * @param array  $tokens
     * @param string $category
     *
     * @return float
     */
    protected function calculateTokensScore(array $tokens, $category)
    {
        $score = 1;

        foreach ($tokens as $token) {
            if (!$this->shouldTokenBeCalculated($token)) {
                continue;
            }

            $count = $this->isTokenFoundOnCategory($token, $category) ? 1 : 0;

            $score *= ($count + 1);
        }

        return $score * $this->priorProbability[$category];
    }

    /**
     * Check whether token should be calculated or not.
     *
     * @param string $token
     *
     * @return bool
     */
    protected function shouldTokenBeCalculated($token)
    {
        return $this->tokenValidator()->shouldBeCalculated(
            $token,
            $this->dictionary()->ignoredWords()
        );
    }

    /**
     * Check whether token is found on the given dictionary category.
     *
     * @param string $token
     * @param string $category
     *
     * @return bool
     */
    protected function isTokenFoundOnCategory($token, $category)
    {
        return $this->dictionary()->isWordFoundOnCategory($token, $category);
    }

    /**
     * Normalize score values.
     *
     * @param array $scores
     *
     * @return array
     */
    protected function normalizeScoreValues(array $scores)
    {
        $totalScore = array_sum($scores);

        foreach ($this->categories as $category) {
            $scores[$category] = round($scores[$category] / $totalScore, 3, 10);
        }

        return $scores;
    }

    /**
     * Create analyzer instance with default configuration.
     *
     * @return \SentimentAnalysis\Contracts\AnalyzerInterface
     */
    public static function withDefaultConfig()
    {
        return new static(
            new Dictionary(__DIR__.'/data'),
            new Tokenizer(),
            new TokenValidator()
        );
    }
}
