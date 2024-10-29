<?php

namespace App\Models;

class ShopTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'translation_shops';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'lang',
        'slug',
        'translation',
    ];

    /**
     * Get the shop that owns the translation.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
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