<?php

namespace App\Service;

use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTube_ChannelSnippet;
use Google_Service_YouTube_VideoSnippet;

class CallApiService
{
    public function __construct(
        private string $appName,
        private string $key
    ) {
    }

    public function getYoutubeId($url): mixed
    {
        $id = null;
        if (str_contains($url, 'youtube')) {
            if (preg_match('/(.+)youtube\.com\/watch\?v=([\w-]+)/', $url, $id)) {
                $id = $id[2];
            }
        } elseif (str_contains($url, 'youtu.be')) {
            if (preg_match('/(.+)youtu.be\/([\w-]+)/', $url, $id)) {
                $id = $id[2];
            }
        }

        return $id;
    }

    public function getVideoInformations($id): ?Google_Service_YouTube_VideoSnippet
    {
        $service = $this->getYoutubeApi();

        $queryParams = [
            'id' => $id,
        ];

        $response = $service->videos->listVideos('snippet,contentDetails', $queryParams);

        return 0 === $response['pageInfo']['totalResults'] ? null : $response->getItems()[0]->getSnippet();
    }

    public function getChannelInformations($id): ?Google_Service_YouTube_ChannelSnippet
    {
        $service = $this->getYoutubeApi();

        $queryParams = [
            'id' => $id,
        ];

        $response = $service->channels->listChannels('snippet,contentDetails', $queryParams);

        return 0 === $response['pageInfo']['totalResults'] ? null : $response->getItems()[0]->getSnippet();
    }

    public function getPlaylistInformations(string $id): ?array
    {
        $service = $this->getYoutubeApi();

        $queryParams = [
            'id' => $id,
        ];

        $response = $service->playlists->listPlaylists('snippet,contentDetails', $queryParams);

        return 0 === $response['pageInfo']['totalResults'] ? null : $response->getItems();
    }

    public function getPlaylistItemsInformations(string $id): ?array
    {
        $service = $this->getYoutubeApi();

        $queryParams = [
            'playlistId' => $id,
        ];

        $response = $service->playlistItems->listPlaylistItems('snippet,contentDetails', $queryParams);

        return 0 === $response['pageInfo']['totalResults'] ? null : $response->getItems();
    }

    private function getYoutubeApi(): Google_Service_YouTube
    {
        $client = new Google_Client();
        $client->setApplicationName($this->appName);
        $client->setDeveloperKey($this->key);

        return new Google_Service_YouTube($client);
    }
}
