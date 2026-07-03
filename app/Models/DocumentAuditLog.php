<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAuditLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function physicalDocument()
    {
        return $this->belongsTo(PhysicalDocument::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
