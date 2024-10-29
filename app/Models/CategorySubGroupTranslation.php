<?php

namespace App\Models;

class CategorySubGroupTranslation extends BaseModel
{
    protected $table = 'translation_category_sub_groups';

    protected $fillable = [
        'category_sub_group_id',
        'lang',
        'translation',
    ];

    public function subGroup()
    {
        return $this->belongsTo(CategorySubGroup::class);
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