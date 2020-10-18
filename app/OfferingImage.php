<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class OfferingImage extends Model
{
    protected $appends = ['URL'];

    public function getURLAttribute() {
        return Storage::url($this->image);
    }

    public static function createImage(Offering $offering, $file) {
        try {
            $offerImage = new OfferingImage();
            $offerImage->offering_id = $offering->id;
            $offerImage->image = self::imageName($offering->id);

            $image = Image::make($file);
            $image->encode('jpg');

            Storage::disk(env("STORAGE"))->put($offerImage->image, $image->getEncoded());

            $offerImage->save();
            return true;
        }
        catch(\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

    }

    private static function imageName($id) {
        return $id . "_" . round(microtime(true) * 1000) . ".jpg";
    }

    public function user() {
        if($offer = $this->belongsTo(Offering::class, 'offering_id', 'id')->first()) {
            return $offer->user();
        }
        else {
            return null;
        }
    }
}
