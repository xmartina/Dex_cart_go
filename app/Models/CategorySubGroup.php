<?php

namespace App\Models;

use App\Common\CascadeSoftDeletes;
use App\Common\Imageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class CategorySubGroup extends BaseModel
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category_sub_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'category_group_id', 'slug', 'description', 'active', 'order', 'meta_title', 'meta_description'];

    /**
     * Cascade Soft Deletes Relationships
     *
     * @var array
     */
    protected $cascadeDeletes = ['categories'];

    private $translationExists = [];
    /**
     * The boot method for the Category model.
     *
     * This method is called when the Category model is being booted.
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
     * Get the categoryGroup that owns the SubGroup.
     */
    public function group()
    {
        return $this->belongsTo(CategoryGroup::class, 'category_group_id')->withTrashed();
    }

    /**
     * Get the categories for the CategorySubGroup.
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'category_sub_group_id')->orderBy('order', 'asc');
    }

    public function translations()
    {
        return $this->hasMany(CategorySubGroupTranslation::class);
    }

    // /**
    //  * Get all listings for the category.
    //  */
    // public function getListingsAttribute()
    // {
    //     return \DB::table('inventories')
    //     ->join('category_product', 'inventories.product_id', '=', 'category_product.product_id')
    //     ->select('inventories.*', 'category_product.category_id')
    //     ->where('category_product.category_id', '=', $this->id)->get();

    //     // return $this->belongsToMany(Inventory::class, 'category_product', null, 'product_id', null, 'product_id');
    // }

    /**
     * Setters
     */
    public function setOrderAttribute($value)
    {
        $this->attributes['order'] = $value ?? 100;
    }

    public function hasTranslation($lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        if (!array_key_exists($lang, $this->translationExists)) {
            $this->translationExists[$lang] = $this->translations()->where('lang', $lang)->exists();
        }

        return $this->translationExists[$lang];
    }
}
