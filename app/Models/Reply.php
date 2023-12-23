<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;
    protected $fillable = ['to', 'subject', 'replyBody', 'options', 'message_id', 'labels'];


    function getOptions($arr = true)
    {
        return json_decode($this->options, $arr);
    }

    public function snippet($length = 100)
    {
        // Get the message content and trim it to the specified length
        $snippet = substr(strip_tags($this->replyBody), 0, $length);

        // If the message is longer than the snippet, add an ellipsis
        if (strlen($this->replyBody) > $length) {
            $snippet .= '...';
        }

        return $snippet;
    }
    public function date()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
    }
}
