<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeductionItem extends Model
{
    protected $fillable = [
        'deduction_file_id',
        'national_code',
        'personnel_code',
        'amount',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(DeductionFile::class, 'deduction_file_id');
    }
}
