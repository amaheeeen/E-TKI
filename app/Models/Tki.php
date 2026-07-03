<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tki extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    public function physicalDocuments()
    {
        return $this->hasMany(PhysicalDocument::class);
    }
}
