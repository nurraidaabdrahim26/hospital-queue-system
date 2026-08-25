<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'description', 'category'];

    public $timestamps = true;

    /**
     * Get a setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     */
    public static function setValue($key, $value, $description = null, $category = 'general')
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'description' => $description, 'category' => $category]
        );
    }

    /**
     * Get all settings as key-value array
     */
    public static function getAllSettings()
    {
        return static::all()->pluck('value', 'key')->toArray();
    }

    /**
     * Get settings by category
     */
    public static function getByCategory($category)
    {
        return static::where('category', $category)->get();
    }
}