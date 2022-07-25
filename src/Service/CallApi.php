<?php

namespace App\Service;

use App\Entity\Boutique;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApi
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getBoutiqueAdressCoordinates(Boutique $boutique, $cityname, $city_code, $adress = null): array
    {
        $formatedCityName = str_replace([' '], '+', $cityname);

        if (!empty($adress)){
            $formatedAdress   = str_replace([' ', ','], '+', $adress);
            $url = 'https://api-adresse.data.gouv.fr/search/?q='.$formatedAdress.'&city='.$formatedCityName.'&citycode='.$city_code;
        }else{
            $url = 'https://api-adresse.data.gouv.fr/search/?q='.$formatedCityName.'&citycode='.$city_code;
        }
        $response = $this->client->request('GET', $url);
        $content = $response->getContent();
        $content = $response->toArray();

        if (!empty($content['features'][0])){
            $coordinates = $content['features'][0]['geometry']['coordinates'];
        }else{
            $response = $this->client->request('GET','https://api-adresse.data.gouv.fr/search/?q='.$formatedCityName.'&citycode='.$city_code);
            $content = $response->getContent();
            $content = $response->toArray();
            $coordinates = $content['features'][0]['geometry']['coordinates'];
        }

        $boutique->setLng($coordinates[0]);
        $boutique->setLat($coordinates[1]);
    }
}