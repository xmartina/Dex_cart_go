<?php

namespace App\Models;

class AttributeTranslation extends BaseModel
{
    protected $table = 'translation_attributes';

    protected $fillable = [
        'attribute_id',
        'lang',
        'translation',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
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