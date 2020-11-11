<?php

namespace Ignite\Crud\Models\Concerns;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasMedia
{
    /**
     * Register media conversions for field.
     *
     * @param Media $media
     *
     * @return void
     */
    public function registerCrudMediaConversions(Media $media = null)
    {
        foreach (config('lit.mediaconversions.default') as $key => $value) {
            $this->addMediaConversion($key)
                 ->keepOriginalImageFormat()
                 ->withResponsiveImages()
                 ->width($value[0])
                 ->height($value[1])
                 ->sharpen($value[2]);
        }
    }
}
