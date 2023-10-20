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
    // Cast the 'labels' field to an array
    protected $casts = [
        'labels' => 'json',
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
     * Add a label to the 'labels' field.
     *
     * @param string $label
     * @return void
     */
    public function addLabel($label)
    {
        $labels = $this->getLabels();
        $labels[] = $label;
        $this->updateLabels($labels);
        return $this;
    }

    /**
     * Remove a label from the 'labels' field.
     *
     * @param string $label
     * @return void
     */
    public function removeLabel($label)
    {
        $labels = $this->getLabels();

        $labels = array_diff($labels, [$label]);
        $this->updateLabels($labels);
        return $this;
    }

    /**
     * Get the 'labels' field as an array.
     *
     * @return array
     */
    public function getLabels()
    {
        $labels = $this->labels ? explode(',', $this->labels) : [];
        return array_filter(array_unique($labels));
    }

    /**
     * Update the 'labels' field with the given array of labels.
     *
     * @param array $labels
     * @return void
     */
    public function updateLabels(array $labels)
    {
        $this->update(['labels' => implode(',', $labels)]);
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

    public function getCreatedAtAttribute($value)
    {
        // Format the created_at timestamp as per your desired format
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }


    function senderData()
    {
        $url = "http://ip-api.com/json/$this->ip";
        $content = file_get_contents($url);
        $ob = json_decode($content);
        return $ob;
    }

    function country()
    {
        //?fields=country,city,lat,lon
        $ob = $this->senderData();
        if (isset($ob->status) && $ob->status == 'success') {
            return $ob->city . "," . $ob->country;
        }
    }
}
