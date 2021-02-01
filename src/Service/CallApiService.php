<?php
namespace App\Service;

use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTube_ChannelSnippet;
use Google_Service_YouTube_VideoSnippet;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService {

    /**
     * @param $url
     * @return mixed|null
     */
    public function getYoutubeId($url)
    {
        $id = null;
        if(strpos($url, 'youtube') !== false){
            if(preg_match('/(.+)youtube\.com\/watch\?v=([\w-]+)/', $url, $id)){
                $id = $id[2];
            }
        }elseif(strpos($url, 'youtu.be') !== false){
            if(preg_match('/(.+)youtu.be\/([\w-]+)/', $url, $id)){
                $id = $id[2];
            }
        }
        return $id;
    }

    /**
     * @param $id
     * @return Google_Service_YouTube_VideoSnippet|null
     */
    public function getVideoInformations($id): ?Google_Service_YouTube_VideoSnippet
    {
        $service = $this->getYoutubeApi();

        $queryParams = [
            'id' => $id
        ];

        $response = $service->videos->listVideos('snippet,contentDetails', $queryParams);
        return $response['pageInfo']['totalResults'] === 0 ? null : $response->getItems()[0]->getSnippet();
    }

    /**
     * @param $id
     * @return Google_Service_YouTube_ChannelSnippet|null
     */
    public function getChannelInformations($id): ?Google_Service_YouTube_ChannelSnippet
    {
        $service = $this->getYoutubeApi();

        $queryParams = [
            'id' => $id
        ];

        $response = $service->channels->listChannels('snippet,contentDetails', $queryParams);
        return $response['pageInfo']['totalResults'] === 0 ? null : $response->getItems()[0]->getSnippet();
    }

    /**
     * @return Google_Service_YouTube
     */
    private function getYoutubeApi(): Google_Service_YouTube
    {
        $client = new Google_Client();
        $client->setApplicationName('tutomarks');
        $client->setDeveloperKey('AIzaSyAhMAnEh05Dg5ORAbuxNsTo6eENnL8-Jy8');

        return new Google_Service_YouTube($client);
    }
}