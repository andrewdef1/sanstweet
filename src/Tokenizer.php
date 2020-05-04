<?php

namespace SentimentAnalysis;

use SentimentAnalysis\Contracts\TokenizerInterface;

class Tokenizer implements TokenizerInterface
{
    /**
     * Tokenize document.
     *
     * @param string $document
     *
     * @return array
     */
    public function tokenize($document)
    {
        $document = strtolower(trim($document));

        $document = str_replace("\r\n", ' ', $document);

        return explode(' ', $document);
    }
}
