<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'ip', 'email', 'whatsapp', 'subject', 'message', 'domain_id', 'labels'
    ];


    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:99',
        'email' => 'required|email|max:256',
        'whatsapp' => 'nullable|max:30',
        'subject' => 'required|max:256',
        'message' => 'required',
        'domain_id' => 'required|exists:domains,id',
        'labels' => 'nullable|max:255',
    ];

    /**
     * Relationship: Get the domain associated with the message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function snippet($length = 100)
    {
        // Get the message content and trim it to the specified length
        $snippet = substr(strip_tags($this->message), 0, $length);

        // If the message is longer than the snippet, add an ellipsis
        if (strlen($this->message) > $length) {
            $snippet .= '...';
        }

        return $snippet;
    }

    /**
     * Accessor: Decode the 'labels' attribute as JSON when retrieving it.
     *
     * @param mixed $value
     * @return array
     */
    public function getLabelsAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Mutator: Encode the 'labels' attribute as JSON when setting it.
     *
     * @param mixed $value
     * @return void
     */
    public function setLabelsAttribute($value)
    {
        $this->attributes['labels'] = json_encode($value);
    }

    /**
     * Create a new message.
     *
     * @param array $data
     * @return \App\Message
     */
    public static function createMessage($data)
    {
        return self::create($data);
    }

    /**
     * Update a message by ID.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateMessage($id, $data)
    {
        return self::where('id', $id)->update($data);
    }

    /**
     * Delete a message by ID.
     *
     * @param int $id
     * @return bool|null
     */
    public static function deleteMessage($id)
    {
        return self::where('id', $id)->delete();
    }

    /**
     * Get all messages for a specific domain.
     *
     * @param int $domainId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getMessagesByDomain($domainId)
    {
        return self::where('domain_id', $domainId)->get();
    }
}
