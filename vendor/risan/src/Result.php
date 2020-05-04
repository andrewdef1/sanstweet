<?php

namespace SentimentAnalysis;

use SentimentAnalysis\Contracts\ResultInterface;

class Result implements ResultInterface
{
    /**
     * Sentiment scores.
     *
     * @var array
     */
    protected $scores;

    /**
     * Create a new instance of Result class.
     *
     * @param array $scores
     */
    public function __construct(array $scores)
    {
        $this->scores = $scores;
    }

    /**
     * Get sentiment scores.
     *
     * @return array
     */
    public function scores()
    {
        return $this->scores;
    }

    /**
     * Get sentiment category.
     *
     * @return string
     */
    public function category()
    {
        arsort($this->scores);

        return key($this->scores);
    }
}
