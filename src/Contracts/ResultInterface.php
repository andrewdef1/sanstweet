<?php

namespace SentimentAnalysis\Contracts;

interface ResultInterface
{
    /**
     * Get sentiment scores.
     *
     * @return array
     */
    public function scores();

    /**
     * Get sentiment category.
     *
     * @return string
     */
    public function category();
}
