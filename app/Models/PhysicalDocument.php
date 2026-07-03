<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PhysicalDocument extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function tki()
    {
        return $this->belongsTo(Tki::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(DocumentAuditLog::class);
    }
}
