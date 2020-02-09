<?php
/**
 * Created by PhpStorm.
 * User: Yulius Ardian Febrianto <yuliusardin@gmail.com>
 * Date: 09/02/2020
 * Time: 14:30
 */

namespace SaltId\CountlyBundle\Countly\Api\v1;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

class Countly
{
    /** @var Client $client */
    private $client;

    /** @var string $baseUrl */
    private $baseUrl;

    /** @var string $apiKey */
    protected $apiKey;

    /** @var string $appId */
    protected $appId;

    /** @var array $queryAuth */
    private $queryAuth;

    public function __construct()
    {
        $this->apiKey = getenv('COUNTLY_API_KEY', null);
        $this->appId = getenv('COUNTLY_APP_ID', null);
        $this->baseUrl = 'https://try.count.ly';

        $this->queryAuth = [
            'query' => [
                'app_id' => $this->appId,
                'api_key' => $this->apiKey
            ]
        ];

        $this->client = new Client([
            'debug' => false,
            'verify' => false,
        ]);
    }

    public function executeRequest($method, $endpoint, $params, $read = true)
    {
        $readOrWriteRequestBaseEndPoint = $read ? '/o' : '/i';
        try {
            $paramsMerged = array_merge_recursive($this->queryAuth, $params);
            $request = $this->client->request(
                $method,
                $this->baseUrl . $readOrWriteRequestBaseEndPoint . $endpoint,
                $paramsMerged
            );

            if ($request->getStatusCode() === Response::HTTP_OK) {
                $body = $request->getBody()->getContents();

                if (!is_json($body)) {
                    return [];
                }

                return json_decode($body, true);
            }
        } catch (GuzzleException $guzzleException) {
            throw $guzzleException;
        }
    }
}