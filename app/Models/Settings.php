<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Settings extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'value'];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:99|unique:settings',
        'value' => 'required',
    ];

    /**
     * Scope method to retrieve a setting by its name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Accessor to decode the 'value' attribute as JSON when retrieving it.
     *
     * @param mixed $value
     * @return array
     */
    public function getValueAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Mutator to encode the 'value' attribute as JSON when setting it.
     *
     * @param mixed $value
     * @return void
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = json_encode($value);
    }

    /**
     * Create or update a setting by name.
     *
     * @param string $name
     * @param mixed $value
     * @return \App\Setting
     */
    public static function set($name, $value)
    {
        return self::updateOrCreate(['name' => $name], ['value' => $value]);
    }

    /**
     * Get the value of a setting by name.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function get($name, $default = null)
    {
        $setting = self::where('name', $name)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Delete a setting by name.
     *
     * @param string $name
     * @throws \Illuminate\Validation\ValidationException
     * @return bool
     */
    public static function remove($name)
    {
        $setting = self::where('name', $name)->first();

        if (!$setting) {
            throw ValidationException::withMessages([
                'name' => ['The setting does not exist.'],
            ]);
        }

        return $setting->delete();
    }
}
