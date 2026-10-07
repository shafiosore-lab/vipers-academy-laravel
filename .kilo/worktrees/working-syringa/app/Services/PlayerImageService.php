m<?php

namespace App\Services;

class PlayerImageService
{
    /**
     * Array of available player avatar images
     */
    protected array $avatarImages = [
        'player-avatar-01.jpg',
        'player-avatar-02.jpg',
        'player-avatar-03.jpg',
        'player-avatar-04.jpg',
        'player-avatar-05.jpg',
        'player-avatar-06.jpg',
        'player-avatar-07.jpg',
        'player-avatar-08.jpg',
        'player-avatar-09.jpg',
        'player-avatar-10.jpg',
        'player-avatar-11.jpg',
        'player-avatar-12.jpg',
        'player-avatar-13.jpg',
        'player-avatar-14.jpg',
        'player-avatar-15.jpg',
        'player-avatar-16.jpg',
        'player-avatar-17.jpg',
        'player-avatar-18.jpg',
        'player-avatar-19.jpg',
        'player-avatar-20.jpg',
    ];

    /**
     * Get a random avatar image path
     */
    public function getRandomAvatar(): string
    {
        $randomImage = $this->avatarImages[array_rand($this->avatarImages)];
        return 'images/avatars/' . $randomImage;
    }

    /**
     * Get all available avatar images
     */
    public function getAllAvatars(): array
    {
        return array_map(function($image) {
            return 'images/avatars/' . $image;
        }, $this->avatarImages);
    }

    /**
     * Get avatar based on player ID for consistency
     */
    public function getConsistentAvatar(int $playerId): string
    {
        $index = $playerId % count($this->avatarImages);
        $image = $this->avatarImages[$index];
        return 'images/avatars/' . $image;
    }
}
