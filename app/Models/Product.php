<?php

namespace App\Models;

use App\Common\CascadeSoftDeletes;
use App\Common\Feedbackable;
use App\Common\Imageable;
use App\Common\Taggable;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

// Uncomment below line to enable Inspector plugin. (Have to install the plugin.)

class Product extends Inspectable
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Taggable, Imageable, Searchable, Feedbackable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Cascade Soft Deletes Relationships
     *
     * @var array
     */
    protected $cascadeDeletes = ['inventories'];

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'requires_shipping' => 'boolean',
        'downloadable' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * The attributes that should be inspectable for restricted keywords.
     *
     * @var array
     */
    protected static $inspectable = [
        'name',
        'description',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'manufacturer_id',
        'brand',
        'name',
        'model_number',
        'mpn',
        'gtin',
        'gtin_type',
        'description',
        'min_price',
        'max_price',
        'origin_country',
        'requires_shipping',
        'downloadable',
        'slug',
        // 'meta_title',
        // 'meta_description',
        'sale_count',
        'active',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $searchable = [];
        $searchable['id'] = $this->id;
        $searchable['shop_id'] = $this->shop_id;
        $searchable['name'] = $this->name;
        $searchable['slug'] = $this->slug;
        $searchable['model_number'] = $this->model_number;
        $searchable['manufacturer'] = $this->manufacturer_id ? $this->manufacturer->name : null;
        $searchable['mpn'] = $this->mpn;
        $searchable['gtin'] = $this->gtin;
        $searchable['description'] = strip_tags($this->description);
        $searchable['active'] = $this->active;

        return $searchable;
    }

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeAllSearchableUsing($query)
    {
        return $query->with('manufacturer');
    }

    /**
     * Overwrote the image method in the imageable
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function image()
    {
        return $this->morphOne(\App\Models\Image::class, 'imageable')
            ->where(function ($q) {
                $q->whereNull('featured')->orWhere('featured', 0);
            })->orderBy('order', 'asc');
    }

    /**
     * Get the origin associated with the product.
     */
    public function origin()
    {
        return $this->belongsTo(Country::class, 'origin_country');
    }

    /**
     * Get the manufacturer associated with the product.
     */
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class)->withDefault();
    }

    /**
     * Get the shop associated with the product.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the categories for the product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * Get the inventories for the product.
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get the product's translations
     */
    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    /**
     * Set the requires_shipping for the Product.
     */
    public function setRequiresShippingAttribute($value)
    {
        $this->attributes['requires_shipping'] = (bool) $value;
    }

    /**
     * Set the downloadable for the Product.
     */
    public function setDownloadableAttribute($value)
    {
        $this->attributes['downloadable'] = (bool) $value;
    }

    /**
     * Get the category list for the product.
     *
     * @return array
     */
    public function getCategoryListAttribute()
    {
        if (count($this->categories)) {
            return $this->categories->pluck('id')->toArray();
        }
    }

    /**
     * Get the other vendors listings count for the product.
     *
     * @return int
     */
    public function getOffersAttribute()
    {
        return $this->inventories()->distinct('shop_id')->count('shop_id');
    }

    /**
     * Get the type for the product.
     *
     * @return array
     */
    public function getTypeAttribute()
    {
        return $this->downloadable ? trans('app.digital') : trans('app.physical');
    }

    /**
     * Set the Minimum price zero if the value is Null.
     */
    public function setMinPriceAttribute($value)
    {
        $this->attributes['min_price'] = $value ? $value : 0;
    }

    /**
     * Set the Maximum price null if the value is zero.
     */
    public function setMaxPriceAttribute($value)
    {
        $this->attributes['max_price'] = (bool) $value ? $value : null;
    }

    /**
     * Checking product has attributes or not
     */
    public function hasAttributes(): bool
    {
        if ($attrs = $this->categories->pluck('attrsList')) {
            return count($attrs->flatten()->unique('id')) > 0;
        }

        return false;
    }

    /**
     * Checking product has translation for given language code
     * @param string $language_code - language code to check translation
     * @return bool - true if translation exists, false otherwise
     */
    public function hasTranslation($language_code = null): bool
    {
        if (!$language_code) {
            $language_code = app()->getLocale();
        }

        return $this->translations()->where('lang', $language_code)->exists();
    }

    /**
     * Translate given attributes value from translation_products table
     * @param string $attribute - attribute name to translate
     * @return string - translated value of the attribute
     */
    public function translateAttribute(string $attribute)
    {
        if (Route::currentRouteName() == 'admin.catalog.product.edit') {
            return null;
        }

        $product_translation = $this->translations()
                            ->where('lang', app()->getLocale())
                            ->whereNotNull('translation')
                            ->first();

        if (!$product_translation || !isset($product_translation->translation[$attribute])) {
            return null;
        }
        
        return $product_translation->translation[$attribute];
    }
}
