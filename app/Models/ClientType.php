<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClientType extends Model
{
    use HasFactory;

    public function clientTypes(): BelongsToMany
    {
        return $this->belongsToMany(ClientType::class, 'vehicle_client_types');
    }
}
