<?php

namespace App\Services;


use App\Models\PositiveWord;
use App\Models\NegativeWord;



class SentimentService
{


    public function analyze($text)
    {
        $text = strtolower($text);
        
        // Clean text to extract words accurately
        $cleanText = preg_replace('/[^\w\s]/', '', $text);
        $words = preg_split('/\s+/', $cleanText, -1, PREG_SPLIT_NO_EMPTY);
        $totalWords = count($words);

        $positiveWords = PositiveWord::pluck('word')->toArray();
        $negativeWords = NegativeWord::pluck('word')->toArray();

        $positive = 0;
        $negative = 0;

        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) {
                $positive++;
            }
            if (in_array($word, $negativeWords)) {
                $negative++;
            }
        }

        if ($positive > $negative) {
            $result = "Positive";
        } elseif ($negative > $positive) {
            $result = "Negative";
        } else {
            $result = "Neutral";
        }

        $neutral = max(0, $totalWords - $positive - $negative);

        // Confidence calculation based on relative dominance of sentiment words
        $totalSentimentWords = $positive + $negative;
        if ($totalSentimentWords > 0) {
            $confidence = round((abs($positive - $negative) / $totalSentimentWords) * 100);
        } else {
            $confidence = 100; // if no emotional words, it is confidently neutral
        }

        return [
            'sentiment' => $result,
            'positive_score' => $positive,
            'negative_score' => $negative,
            'neutral_score' => $neutral,
            'confidence' => $confidence,
        ];
    }


}