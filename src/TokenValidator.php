<?php

namespace SentimentAnalysis;

use SentimentAnalysis\Contracts\TokenValidatorInterface;

class TokenValidator implements TokenValidatorInterface
{
    /**
     * Minimum token length to calculate.
     */
    const MIN_TOKEN_LENGTH = 1;

    /**
     * Maximum token length to calculate.
     */
    const MAX_TOKEN_LENGTH = 15;

    /**
     * Determine whether token should be calculated or note.
     *
     * @param string $token
     * @param array  $ignoredWords
     *
     * @return bool
     */
    public function shouldBeCalculated($token, array $ignoredWords = [])
    {
        if (!$this->hasValidLength($token)) {
            return false;
        }

        return !$this->isOnIgnoredWords($token, $ignoredWords);
    }

    /**
     * Determine whether token has a valid length.
     *
     * @param string $token
     *
     * @return bool
     */
    public function hasValidLength($token)
    {
        $tokenLength = strlen($token);

        return $tokenLength >= self::MIN_TOKEN_LENGTH && $tokenLength <= self::MAX_TOKEN_LENGTH;
    }

    /**
     * Check whether token is listed on the ignored words.
     *
     * @param string $token
     * @param array  $ignoredWords
     *
     * @return bool
     */
    public function isOnIgnoredWords($token, array $ignoredWords = [])
    {
        return in_array($token, $ignoredWords);
    }
}
