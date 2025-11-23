<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeductionFile extends Model
{
    protected $fillable = [
        'organization_id',
        'title',
        'year',
        'month',
        'original_name',
        'stored_path',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function items()
    {
        return $this->hasMany(DeductionItem::class);
    }
}
