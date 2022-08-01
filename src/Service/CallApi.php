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

    public function getCityInfosbyName(Boutique $boutique)
    {
        $url = 'https://geo.api.gouv.fr/communes?codeDepartement=27';
        $response = $this->client->request('GET', $url);
        $content = $response->getContent();
        $content = $response->toArray();
        $randCityIndex = array_rand($content);
        $cityname = $content[$randCityIndex]['nom'];
        $city_code = $content[$randCityIndex]['code'];
        $post_code = $content[$randCityIndex]['codesPostaux'][0];
        $boutique
            ->setCity($cityname)
            ->setCitycode($city_code)
            ->setPostcode($post_code);
        $this->getBoutiqueAdressCoordinates($boutique, $cityname, $city_code);
    }

    public function getBoutiqueAdressCoordinates(Boutique $boutique, $cityname, $city_code, $adress = null)
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

        $boutique
            ->setLng($coordinates[0])
            ->setLat($coordinates[1]);
    }
}