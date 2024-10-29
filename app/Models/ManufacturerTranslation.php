<?php

namespace App\Models;

class ManufacturerTranslation extends BaseModel
{
    protected $table = 'translation_manufacturers';

    protected $fillable = [
        'manufacturer_id',
        'lang',
        'translation'
    ];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function setTranslationAttribute($value)
    {
        $this->attributes['translation'] = serialize($value);
    }

    public function getTranslationAttribute($value)
    {
        return unserialize($value);
    }
}