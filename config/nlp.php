<?php
/**
 * This contains a simple NLP config for now, but the plan is to expand here in the nearest future
 * to define more complex data for analysing text.
 */
return [
    'stop_words' => ["a", "an", "and", "as", "at", "but", "by", "for", "if", "in", "is", "it", "of", "on",
        "or", "the", "to", "with"],
    'categories' => [
        'Criminal Law' => file_get_contents(public_path('trainingTexts/criminal.txt')),
        'Corporate and Business Law' => file_get_contents(public_path('trainingTexts/corporate.txt')),
        'Constitutional Law' => file_get_contents(public_path('trainingTexts/constitutional.txt')),
        'Family Law' => file_get_contents(public_path('trainingTexts/family.txt')),
        'Civil Law' => file_get_contents(public_path('trainingTexts/civil.txt'))
    ],
    'content_length' => 1200
];
