<?php

namespace App\Models;

use App\Interfaces\MailReceiver;
use App\Interfaces\MailSender;
use App\Utilities\GmailApiMailer;
use App\Utilities\ImapHandler;
use App\Utilities\SMTPMailer;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_address',
        'auth_login_type',
        'smtp_options',
        'imap_options',
        'auth_token',
        'other_options',
        'daily_limit',
        'daily_send_count',
    ];

    protected $casts = [
        'smtp_options' => 'array',
        'imap_options' => 'array',
        'auth_token' => 'array',
        'other_options' => 'array',
    ];

    /**
     * Get SMTP options as JSON.
     *
     * @return array
     */
    public function getSmtpOptionsAttribute($value)
    {
        $default = [
            'host' => null,
            'port' => null,
            'account' => null,
            'password' => null,
            'security' => null,
        ];
        if ($value == null) {
            return $default;
        }
        return array_merge($default, json_decode($value, true));
    }

    /**
     * Set SMTP options as JSON.
     *
     * @param  array  $value
     * @return void
     */
    public function setSmtpOptionsAttribute($value)
    {
        $this->attributes['smtp_options'] = json_encode($value);
    }

    /**
     * Get IMAP options as JSON.
     *
     * @return array
     */
    public function getImapOptionsAttribute($value)
    {
        $default = [
            'host' => null,
            'port' => null,
            'account' => null,
            'password' => null,
            'security' => null,
        ];
        if ($value == null) {
            return $default;
        }
        return array_merge($default, json_decode($value, true));
    }

    /**
     * Set IMAP options as JSON.
     *
     * @param  array  $value
     * @return void
     */
    public function setImapOptionsAttribute($value)
    {
        $this->attributes['imap_options'] = json_encode($value);
    }

    /**
     * Get auth token as JSON.
     *
     * @return array
     */
    public function getAuthTokenAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Set auth token as JSON.
     *
     * @param  array  $value
     * @return void
     */
    public function setAuthTokenAttribute($value)
    {
        $this->attributes['auth_token'] = json_encode($value);
    }

    /**
     * Get other options as JSON.
     *
     * @return array
     */
    public function getOtherOptionsAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Set other options as JSON.
     *
     * @param  array  $value
     * @return void
     */
    public function setOtherOptionsAttribute($value)
    {
        $this->attributes['other_options'] = json_encode($value);
    }

    public function updateCount()
    {
        $this->daily_send_count = $this->daily_send_count + 1;
        $this->save();
    }

    public function hasQuota()
    {
        return $this->getQuota() > 0;
    }
    
    public function getQuota()
    {
        return ($this->daily_limit - $this->daily_send_count);
    }



    public function getMailer()
    {
        if ($this->auth_login_type == 1) {
            return new GmailApiMailer($this);
        } else {
            return new SMTPMailer($this);
        }
    }

    public function getMailReceiver()
    {
        if ($this->auth_login_type == 1) {
            return new GmailApiMailer($this);
        } else {
            return new ImapHandler($this);
        }
    }
}
