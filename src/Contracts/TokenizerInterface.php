<?php

namespace SentimentAnalysis\Contracts;

interface TokenizerInterface
{
    /**
     * Tokenize document.
     *
     * @param string $document
     *
     * @return array
     */
    public function tokenize($document);
}
