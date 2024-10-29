<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductTranslation extends BaseModel
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'translation_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'lang',
        'translation',
    ];

    /**
     * Get the product that owns the translation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Set the translation attribute as serialized object.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setTranslationAttribute($value)
    {
        $this->attributes['translation'] = serialize($value);
    }

    /**
     * Get the translation attribute as a php object.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public function getTranslationAttribute($value)
    {
        return unserialize($value);
    }
}
