<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxiRequest extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'rut',
        'phone',
        'is_associated_ot',
        'project_number',
        'start_address',
        'destination_address',
        'scheduled_date',
        'scheduled_time',
        'price',
        'status',
        'current_taxi_id',
        'started_at',
        'completed_at',
        'request_time',
        'estimated_arrival_time',
    ];

    /**
     * Get the current taxi driver assigned to this request.
     */
    public function currentTaxi()
    {
        return $this->belongsTo(User::class, 'current_taxi_id');
    }

    /**
     * Users who rejected this request.
     */
    public function rejectedBy()
    {
        return $this->belongsToMany(User::class, 'taxi_rejections', 'taxi_request_id', 'user_id')->withTimestamps();
    }

    /**
     * Get the user that owns the taxi request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'is_associated_ot' => 'boolean',
        'request_time' => 'datetime',
        'estimated_arrival_time' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
}
