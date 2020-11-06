<?php

namespace ExceptionLive;

use ExceptionLive\Exceptions\Exception;
use ExceptionLive\Exceptions\ExceptionFactory;
use ExceptionLive\Notifications\Notification;
use \GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\HttpFoundation\Response;

class Client
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * Client constructor.
     *
     * @param Config $config
     * @param GuzzleClient $client
     */
    public function __construct(Config $config, GuzzleClient $client = null)
    {
        $this->config = $config;
        $this->client = $client ?? $this->makeClient();
    }

    /**
     * @param Notification $notification
     * @return array
     *
     * @throws Exception
     */
    public function notification(Notification $notification): array
    {
        $payload = $notification->make();

        try {
            $response = $this->client->post(
                '/api/'. $notification->getMethod() . '/'. $this->config->api_key,
                ['body' => json_encode($payload, JSON_PARTIAL_OUTPUT_ON_ERROR)]
            );
        } catch (\Exception $e) {
            throw Exception::generic();
        }

        if ($response->getStatusCode() !== Response::HTTP_CREATED) {
            throw (new ExceptionFactory($response))->make();
        }

        return (string) $response->getBody()
            ? json_decode($response->getBody(), true)
            : [];
    }

    /**
     * @param string $key
     * @return void
     *
     * @throws Exception
     */
    public function checkin(string $key): void
    {
        try {
            $response = $this->client->head(sprintf('check_in/%s', $key));
        } catch (Exception $e) {
            throw Exception::generic();
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw (new ExceptionFactory($response))->make();
        }
    }

    /**
     * @return GuzzleClient
     */
    private function makeClient(): GuzzleClient
    {
        return new GuzzleClient([
            'curl'            => [CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false],
            'base_uri' => ExceptionLive::API_URL,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => $this->config->client['timeout'],
            'proxy' => $this->config->client['proxy'],
        ]);
    }
}
