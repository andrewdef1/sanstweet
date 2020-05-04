<?php

namespace SentimentAnalysis\Contracts;

interface TokenValidatorInterface
{
    /**
     * Determine whether token should be calculated or note.
     *
     * @param string $token
     * @param array  $ignoredWords
     *
     * @return bool
     */
    public function shouldBeCalculated($token, array $ignoredWords = []);

    /**
     * Determine whether token has a valid length.
     *
     * @param string $token
     *
     * @return bool
     */
    public function hasValidLength($token);

    /**
     * Check whether token is listed on the ignored words.
     *
     * @param string $token
     * @param array  $ignoredWords
     *
     * @return bool
     */
    public function isOnIgnoredWords($token, array $ignoredWords = []);
}
