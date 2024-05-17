<?php

namespace App\Services;

/**
 * Class BrandService.
 */
class BrandService
{
    public function handleUploadImage($image, $brand)
    {
        $image->storeAs('public/brand', $brand->id . '.' . $image->extension());
        $brand->image = 'brand/' . $brand->id . '.' . $image->extension();
        $brand->save();
    }

    public function handleDeleteImage($brand)
    {
        if (file_exists(storage_path('app/public/' . $brand->image))) {
            unlink(storage_path('app/public/' . $brand->image));
        }
    }
}
