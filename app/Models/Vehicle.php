<?php

namespace App\Models;

use App\Enum\Filter\PriceRangeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
    ];

    /////////////////////
    /// Relationships ///
    /////////////////////
    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(VehicleReview::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(VehiclePrice::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'vehicle_features');
    }

    public function clientTypes(): BelongsToMany
    {
        return $this->belongsToMany(ClientType::class, 'vehicle_client_types');
    }

    public function uses(): BelongsToMany
    {
        return $this->belongsToMany(VehicleUse::class, 'vehicle_vehicle_use');
    }

    //////////////
    /// Scopes ///
    //////////////

    public function scopeFilterByClientType(Builder $query, int $clientTypeId): Builder
    {
        return $query->whereHas('clientTypes', fn (Builder $q) => $q->where('client_type_id', $clientTypeId));
    }

    public function scopeFilterByVehicleUse(Builder $query, int $vehicleUseId): Builder
    {
        return $query->whereHas('uses', fn (Builder $q) => $q->where('vehicle_use_id', $vehicleUseId));
    }

    public function scopeFilterByPriceRange(Builder $query, int $priceRangeId): Builder
    {
        return $query->whereHas('prices', function (Builder $q) use ($priceRangeId) {
            match ($priceRangeId) {
                PriceRangeEnum::LESS_THAN_100->value => $q->where('price', '<', 100),
                PriceRangeEnum::BETWEEN_100_AND_150->value => $q->whereBetween('price', [100, 150]),
                PriceRangeEnum::BETWEEN_150_AND_200->value => $q->whereBetween('price', [150, 200]),
                PriceRangeEnum::OVER_200->value => $q->where('price', '>', 200),
            };
        });
    }

    public function scopeFilterByContractLength(Builder $query, int $contractType): Builder
    {
        return $query->whereHas('prices', fn (Builder $q) => $q->where('contract_length_months', $contractType));
    }

    public function scopeFilterByFeatures(Builder $query, array $features): Builder
    {
        return $query->whereHas('features', fn (Builder $q) => $q->whereIn('feature_id', $features));
    }
}
