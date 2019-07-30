<?php

namespace App\Models\Web;

use App\Models\AbstractPivot as BasePivot;

class EventEmailSeriesPivot extends BasePivot
{

    public $incrementing = false;

    protected $table = 'event_email_series';

    protected $casts = [
        'send_nonmember' => 'boolean',
        'send_member' => 'boolean',
        'send_sustaining_fellow' => 'boolean',
        'send_affiliate' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo('App\Models\Web\Event', 'event_id', 'id');
    }

    public function emailSeries()
    {
        return $this->belongsTo('App\Models\Web\EmailSeries', 'email_series_id', 'id');
    }

}
