<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryGroupTranslation extends BaseModel
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'translation_category_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_group_id',
        'lang',
        'translation'
    ];

    /**
     * Get the category group that owns the translation.
     */
    public function categoryGroup()
    {
        return $this->belongsTo(CategoryGroup::class);
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
