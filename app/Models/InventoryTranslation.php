<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryTranslation extends BaseModel
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'translation_inventories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inventory_id',
        'lang',
        'translation',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
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