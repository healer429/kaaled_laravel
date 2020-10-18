<?php


namespace App\Helper;


use App\Offering;

class ListSearchHelper
{
    public function searchNearByOffer($lat, $lng, $radius) {
        $range = $this->computeRange($lat, $lng, $radius);

        return Offering::where('lat', '>=', $range["latLower"])
            ->where('is_expired', 0)
            ->where('lat', '<=', $range["latHigher"])
            ->where('lng', '>=', $range["lngLower"])
            ->where('lng', '<=', $range["lngHigher"])
            ->where('user_id', '!=', auth()->id());
    }

    private function computeRange($lat, $lng, $radius) {
        $latVariation = $radius * env("LATITUDE_PER_KM");
        $lngVariation = $radius * env("LONGITUDE_PER_KM");

        return array(
            'latLower' => $lat - $latVariation,
            'latHigher' => $lat + $latVariation,
            'lngLower' => $lng - $lngVariation,
            'lngHigher' => $lng + $lngVariation
        );
    }
}
