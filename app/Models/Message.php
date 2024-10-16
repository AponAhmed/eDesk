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

        if (!empty($this->senderinfo)) {
            return $this->senderinfo;
        }

        $ips = explode(',', $this->ip);


        //foreach ($ips as $ip) {
        $ip = $ips[0];
        $ip = trim($ip);
        //$url = "http://ip-api.com/json/$ip";
        $url = "https://freeipapi.com/api/json/$ip";
        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response as a string
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Follow redirects if any
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);  // Timeout after 10 seconds

        // Set custom headers, including User-Agent and others
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:131.0) Gecko/20100101 Firefox/131.0",
        //     "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/png,image/svg+xml,*/*;q=0.8",
        //     "Accept-Language: en-US,en;q=0.5",
        //     "Accept-Encoding: gzip, deflate",
        //     "Connection: keep-alive",
        //     "Upgrade-Insecure-Requests: 1",
        //     "Pragma: no-cache"
        // ]);

        // Execute the request and store the response
        $response = curl_exec($ch);
        // Check for errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            $this->senderinfo = "Error: $error";
        } else {
            // Decode the response and add it to the info array
            $this->senderinfo = json_decode($response);
        }

        // Close the cURL session
        curl_close($ch);
        //}


        return $this->senderinfo;
    }


    function country()
    {
        //?fields=country,city,lat,lon
        $ob = $this->senderData();
        return $ob->cityName . ", " . $ob->countryName;
    }
}
