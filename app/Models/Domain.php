<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['url', 'key'];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'url' => 'required|max:99|unique:domains',
        'key' => 'required|max:32|unique:domains',
    ];

    /**
     * Relationship: Get all messages associated with the domain.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Create a new domain.
     *
     * @param array $data
     * @return \App\Domain
     */
    public static function createDomain($data)
    {
        return self::create($data);
    }

    /**
     * Update a domain by ID.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateDomain($id, $data)
    {
        return self::where('id', $id)->update($data);
    }

    /**
     * Delete a domain by ID.
     *
     * @param int $id
     * @return bool|null
     */
    public static function deleteDomain($id)
    {
        return self::where('id', $id)->delete();
    }

    /**
     * Get all domains.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllDomains()
    {
        return self::all();
    }
}
