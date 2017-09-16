<?php
namespace HackTheNorth\CockroachNest\Services;

use SimpleXMLElement;

class GeocodeService
{
    public function geocodeCounty($county, $state)
    {
        $results = new SimpleXMLElement($this->curlGet($county . ', ' . $state));

        if (!($results->status == 'OK')) {
            return false;
        }

        $newLat = $results->result->geometry->location->lat;
        $newLng = $results->result->geometry->location->lng;

        return [$newLat, $newLng];
    }

    private function curlGet($query)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($query) . '&region=us&key=AIzaSyCUjzCk3Z-_3-jAWwpHbLLkGp5YJguv-2o');
        curl_setopt($ch, CURLOPT_USERAGENT, "CockroachNest Geocoder/1.0");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $output = curl_exec($ch);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
            curl_close($ch);
            echo $output;
            return $output;
        } else {
            print_r(curl_getinfo($ch));
            echo $output;
            curl_close($ch);
            return 'Query Failed';
        }
    }
}
