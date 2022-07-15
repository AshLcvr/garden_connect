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
        $formatedCityName = str_replace([' '], '+', $city);

        if (!empty($adress)){
            $formatedAdress   = str_replace([' ', ','], '+', $adress);
            $url = 'https://api-adresse.data.gouv.fr/search/?q='.$formatedAdress.'&postcode='.$postcode.'&city='.$formatedCityName;
        }else{
            $url = 'https://api-adresse.data.gouv.fr/search/?q=postcode='.$postcode.'&city='.$formatedCityName;
        }
        $response = $this->client->request('GET', $url);

        $content = $response->getContent();
        $content = $response->toArray();

        if (!empty($content['features'][0])){
            $coordinates = $content['features'][0]['geometry']['coordinates'];
        }else{
            $response = $this->client->request('GET','https://api-adresse.data.gouv.fr/search/?q=postcode='.$postcode.'&city='.$city);
            $content = $response->getContent();
            $content = $response->toArray();
            $coordinates = $content['features'][0]['geometry']['coordinates'];
        }

        return $coordinates;
    }
}