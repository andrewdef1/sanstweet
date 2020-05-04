# Sentiment Analysis

PHP Sentiment analysis library for classifying text into positive, negative, or neutral.

## Installation

To install this library using [Composer](https://getcomposer.org/), run the following command inside your project directory:

```bash
composer require risan/sentiment-analysis
```

## Basic Usage

```php
<?php
// Include composer autoloader file.
require __DIR__ . '/vendor/autoload.php';

// Create a new instance of analyzer with default configuration.
$analyzer = SentimentAnalysis\Analyzer::withDefaultConfig();

// Analyze the text.
$result = $analyzer->analyze('This PHP package is awesome');

// Get and print the category.
echo $result->category();
```
