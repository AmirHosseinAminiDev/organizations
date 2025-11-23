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
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeductionItem::class);
    }


}
