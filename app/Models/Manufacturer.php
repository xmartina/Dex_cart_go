<?php

namespace App\Models;

use App\Common\Imageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use App\Models\ManufacturerTranslation;
use Illuminate\Database\Eloquent\Builder;

class Manufacturer extends BaseModel
{
    use HasFactory, SoftDeletes, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'manufacturers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'name',
        'slug',
        'email',
        'url',
        'phone',
        'description',
        'country_id',
        'active',
    ];

    private $translationExists = [];

    /**
     * Boot the Manufacturer model.
     *
     * This method is called when the Manufacturer model is being booted.
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

    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the country for the manufacturer.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the products for the manufacturer.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all of the inventories for the country.
     */
    public function inventories()
    {
        return $this->hasManyThrough(Inventory::class, Product::class);
    }

    /**
     * Get the count of all inventories for this brand.
     */
    public function inventoryCount()
    {
        return $this->hasManyThrough(Inventory::class, Product::class)->count();
    }

    public function translations()
    {
        return $this->hasMany(ManufacturerTranslation::class);
    }

    /**
     * Accessors for translation
     */
    public function getNameAttribute($value)
    {
        return $this->translateAttribute('name') ?? $value;
    }

    public function getDescriptionAttribute($value)
    {
        return $this->translateAttribute('description') ?? $value;
    }

    public function hasTranslation($lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        if (!array_key_exists($lang, $this->translationExists)) {
            $this->translationExists[$lang] = $this->translations()->where('lang', $lang)->exists();
        }

        return $this->translationExists[$lang];
    }

    /**
     * Translate given attributes value from translation_manufacturers table
     * @param string $attribute - attribute name to translate
     * @return string - translated value of the attribute
     */
    public function translateAttribute(string $attribute)
    {
        if (Route::getCurrentRoute() == 'admin.catalog.manufacturer.edit') {
            return null;
        }

        $manufacturer_translation = $this->translations->first();

        if (!$manufacturer_translation || !isset($manufacturer_translation->translation[$attribute])) {
            return null;
        }

        return $manufacturer_translation->translation[$attribute];
    }
}
