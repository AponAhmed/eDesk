<?php

namespace App\Models;

use App\Http\Traits\MessageTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GMessage extends Model
{
    use HasFactory, MessageTraits;

    protected $fillable = [
        'name', 'email', 'subject', 'message', 'sender_id', 'labels', 'reminder', 'header'
    ];

    protected $casts = [
        'labels' => 'json',
        'header' => 'json',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:99',
        'email' => 'required|email|max:256',
        'subject' => 'nullable|max:256',
        'message' => 'nullable',
        'sender_id' => 'required|exists:senders,id',
        'labels' => 'nullable|max:255',
        'reminder' => 'integer',
    ];

    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }



    public static function createMessage($data)
    {
        return self::create($data);
    }

    public static function updateMessage($id, $data)
    {
        return self::where('id', $id)->update($data);
    }

    public static function deleteMessage($id)
    {
        return self::where('id', $id)->delete();
    }

    public static function getMessagesBySender($senderId)
    {
        return self::where('sender_id', $senderId)->get();
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
