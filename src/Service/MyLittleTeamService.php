<?php
namespace App\Service;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MyLittleTeamService
{

    private HttpClientInterface $client;
    private ObjectNormalizer $normalizer;
    private string $base_url;
    private string $url;
    private string $airtable_key;
    private string $airtable_id;

    public function __construct(HttpClientInterface $client, ObjectNormalizer $objectNormalizer, string $airtable_key, string $airtable_id)
    {
        $this->client = $client;
        $this->normalizer = $objectNormalizer;
        $this->base_url = 'https://api.airtable.com/v0/';
        $this->airtable_key = $airtable_key;
        $this->airtable_id = $airtable_id;

        $this->url = $this->base_url . $this->airtable_id;
    }

    public function findLatest(string $table, string $view, int $nb = 3, ?string $orderfield = null, ?string $orderby = 'asc'): array
    {
        $opt = [];
        if(!is_null($orderfield)) {
            $opt = [
                'sort' => [
                    0 => [
                        'field' => $orderfield,
                        'direction' => $orderby,
                    ],
                ],
            ];
        }

        $params = [
            'view'          => $view,
            'maxRecords'    => $nb,
            $opt,
        ];

        $url = sprintf(
            '%s?%s',
            $table,
            http_build_query($params)
        );

        $response = $this->client->request(
            'GET',
            $this->url . '/' . $url,
            [
                'auth_bearer' => $this->airtable_key
            ]
        );

        return $response->toArray()['records'];
    }
}