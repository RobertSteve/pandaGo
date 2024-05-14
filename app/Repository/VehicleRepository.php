<?php

namespace App\Repository;

use App\Http\Requests\VehicleSearchRequest;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class VehicleRepository
{
    public function getAllVehicles(VehicleSearchRequest $request): LengthAwarePaginator
    {
        return Vehicle::query()
            ->with([
                'images' => function (HasMany $query) {
                    $query->select('vehicle_id', 'image_path');
                    $query->where('is_main', 1);
                },
                'reviews' => function (HasMany $query) {
                    $query->select('vehicle_id', 'rating');
                },
                'prices' => function (HasMany $query) {
                    $query->select('vehicle_id', 'price', 'contract_length_months');
                    $query->orderBy('price');
                },
            ])
            ->when($request->filled('client_type_id'), fn (Builder $q) => $q->filterByClientType($request->client_type_id))
            ->when($request->filled('vehicle_use_id'), fn (Builder $q) => $q->filterByVehicleUse($request->vehicle_use_id))
            ->when($request->filled('price_range_id'), fn (Builder $q) => $q->filterByPriceRange($request->price_range_id))
            ->when($request->filled('contract_length'), fn (Builder $q) => $q->filterByContractLength($request->contract_length))
            ->when($request->filled('features'), fn (Builder $q) => $q->filterByFeatures($request->features))
            ->select(['id', 'name', 'description'])
            ->orderByDesc('id')
            ->paginate(10);
    }
}
