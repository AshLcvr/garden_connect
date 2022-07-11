<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApi
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
    $this->client = $client;
    }

    public function getBoutiqueAdressCoordinates($postcode, $city, $adress = null): array
    {
        if (!empty($adress)){
            $url = 'https://api-adresse.data.gouv.fr/search/?q='.$adress.'&postcode='.$postcode.'&city='.$city;
        }else{
            $url = 'https://api-adresse.data.gouv.fr/search/?q=postcode='.$postcode.'&city='.$city;

        }
        $response = $this->client->request('GET', $url);

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        if (!empty($content['features'][0])){
            $coordinates = $content['features'][0]['geometry']['coordinates'];
        }else{

            $response = $this->client->request('GET','https://api-adresse.data.gouv.fr/search/?q=postcode='.$postcode.'&city='.$city);
            $response->getContent();
            $response->toArray();
            dd($response);
        }


        return $coordinates;
    }
}