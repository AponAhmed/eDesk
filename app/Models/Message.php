<?php

namespace App\Models;

use App\Http\Traits\MessageTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory, MessageTraits;
    protected $fillable = [
        'name',
        'ip',
        'email',
        'whatsapp',
        'subject',
        'reminder',
        'message',
        'domain_id',
        'labels'
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


    function senderData_()
    {
        $ips = explode(',', $this->ip);
        //last ip address
        $this->ip = trim(end($ips));
        $url = "http://ip-api.com/json/$this->ip";
        $content = file_get_contents($url);
        $ob = json_decode($content);
        return $ob;
    }

    /**
     * Multiple IP address details
     * @return array
     */
    function senderData()
    {
        $ips = explode(',', $this->ip);
        //last ip address
        $info = [];
        foreach ($ips as $ip) {
            $ip = trim($ip);
            $url = "http://ip-api.com/json/$ip";
            $content = file_get_contents($url);
            $info[] = json_decode($content);
        }
        return $info;
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
