<?php

namespace App\Models;

use App\Common\Addressable;
use App\Common\Imageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends BaseModel
{
    use HasFactory, SoftDeletes, Addressable, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'warehouses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'name',
        'email',
        'incharge',
        'description',
        'opening_time',
        'close_time',
        'business_days',
        'pickup_instruction',
        'active',
    ];

    /**
     * Get the country for the warehouse.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the manager of the warehouse.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'incharge')->withDefault();
    }

    /**
     * Get the Users associated with the warehouse.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /** get warehouse address*/
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * Get staff list for the user.
     *
     * @return array
     */
    public function getUserListAttribute()
    {
        return $this->users->pluck('id')->toArray();
    }

    /**
     * Get the Shop associated with the blog post.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the Inventories for the warehouse.
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get all of the products for the warehouse.
     */
    public function products()
    {
        return $this->hasManyThrough(Product::class, Inventory::class);
    }

    /**
     * Set business_days as serialized data for the model.
     */
    public function setBusinessDaysAttribute($business_days)
    {
        $this->attributes['business_days'] = serialize($business_days);
    }

    /**
     * Get business_days for the model.
     *
     * @return array
     */
    public function getBusinessDaysAttribute($business_days)
    {
        // check if business days is a comma separated string if it is return it as an array
        // This is for ensuring compatibility with older versions.
        if (is_string($business_days) && strpos($business_days, ',') !== false) {
            return explode(',', $business_days);
        }

        return unserialize($business_days);
    }
}
