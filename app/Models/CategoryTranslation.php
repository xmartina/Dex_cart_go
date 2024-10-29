<?php

namespace App\Models;

class CategoryTranslation extends BaseModel
{
    protected $table = 'translation_categories';

    protected $fillable = [
        'category_id',
        'slug',
        'lang',
        'translation',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
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
