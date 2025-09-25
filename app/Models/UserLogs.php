<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class UserLogs extends Model
{
    protected $fillable = [
        'user_id',
        'ip',
        'device',
        'activity',
    ];

    use Prunable;

    /*         
        Run pruning daily at 11:59 PM
        this  command will be place the server cron job to run every minute
        * * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
        this will ensure that the scheduled tasks are executed as per the defined schedule 
        place the above line in your server's crontab file
        
        */
    public function prunable()
    {
        return static::where('created_at', '<', now()->subDay());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
