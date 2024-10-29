<?php

namespace App\Models;

use App\Common\CascadeSoftDeletes;
use App\Common\Imageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;

class CategoryGroup extends BaseModel
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'slug', 'icon', 'order', 'active', 'meta_title', 'meta_description'];

    /**
     * Cascade Soft Deletes Relationships
     *
     * @var array
     */
    protected $cascadeDeletes = ['subGroups'];

    private $translationExists = [];

    /**
     * The boot method for the CategoryGroup model.
     *
     * This method is called when the CategoryGroup model is being booted.
     * It adds a global scope to the model to include translations based on the current locale.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('withTranslations', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                $query->where('lang', app()->getLocale())->whereNotNull('translation');
            }]);
        });
    }

    /**
     * Get the subGroups associated with the CategoryGroup.
     */
    public function subGroups()
    {
        return $this->hasMany(CategorySubGroup::class, 'category_group_id')->orderBy('order', 'asc');
    }

    /**
     * Get the categories associated with the CategoryGroup.
     */
    public function categories()
    {
        return $this->hasManyThrough(
            Category::class,
            CategorySubGroup::class,
            'category_group_id', // Foreign key on CategorySubGroup table...
            'category_sub_group_id', // Foreign key on Category table...
            'id', // Local key on CategoryGroup table...
            'id' // Local key on CategorySubGroup table...
        );
    }

    public function translations()
    {
        return $this->hasMany(CategoryGroupTranslation::class);
    }

    /**
     * Setters
     */
    public function setOrderAttribute($value)
    {
        $this->attributes['order'] = $value ?? 100;
    }

    public function getNameAttribute($value)
    {
        return $this->translateAttribute('name') ?? $value;
    }

    public function getDescriptionAttribute($value)
    {
        return $this->translateAttribute('description') ?? $value;
    }

    /**
     * Check if the CategoryGroup has a translation for the specified language.
     *
     * @param string|null $selected_language The language code to check for translation. 
     * 
     * @return bool
     */
    public function hasTranslation($lang = null)
    {
        $lang = $lang ?? app()->getLocale();  // Use application locale as default.

        if (!array_key_exists($lang, $this->translationExists)) {
            $this->translationExists[$lang] = $this->translations()->where('lang', $lang)->exists();
        }

        return $this->translationExists[$lang];
    }

    /**
     * Translate given attributes value from translation_inventories table
     * @param string $attribute - attribute name to translate
     * @return string - translated value of the attribute
     */
    public function translateAttribute(string $attribute)
    {
        if (Route::currentRouteName() == 'admin.catalog.categoryGroup.edit') {
            return null;
        }

        $category_group_translation = $this->translations->first();

        if (!$category_group_translation || !isset($category_group_translation->translation[$attribute])) {
            return null;
        }

        return $category_group_translation->translation[$attribute];
    }
}
