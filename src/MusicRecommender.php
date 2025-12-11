<?php

namespace MoodTune;

class MusicRecommender
{
    public static function getRecommendations(string $mood): array
    {
        return Database::getMusicByMood($mood);
    }

    public static function formatRecommendations(array $songs): array
    {
        return array_map(function($song) {
            return [
                'title' => $song['title'],
                'artist' => $song['artist'],
                'genre' => $song['genre'],
                'emoji' => $song['emoji'],
                'energyLevel' => $song['energy_level']
            ];
        }, $songs);
    }
}