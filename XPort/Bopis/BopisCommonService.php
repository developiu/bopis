<?php

namespace XPort\Bopis;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class BopisCommonService
{
    public static function buildUrl($path)
    {
        return AMAZON_CONNECTOR_URL . '/' . $path;
    }

    /**
     * Create an ajax request to Bopis api
     *
     * @param ClientInterface $client
     * @param string $method the method (GET,PUT,POST,DELETE...)
     * @param string $url the url to be contacted
     * @param array|null $inputData the array to be sent  in the request body
     * @return array|null the response from the api converted to an array with json_decode, or null in case of error
     */
    public static function request(ClientInterface $client, string $method, string $url, array $inputData = null) :?array
    {
        try {
            $response = $client->request('GET',$url,[
                'headers' => [
                    'Accept'     => 'application/json'
                ]
            ]);
        }
        catch(GuzzleException $e) {
            return null;
        }

        $body = $response->getBody()->getContents();

        $answer = json_decode($body, true);

        // NB: $answer potrebbe essere null se il corpo non è in formato json corretto
        return $answer;
    }
    
}