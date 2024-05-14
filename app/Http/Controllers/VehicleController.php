<?php

namespace App\Http\Controllers;

use App\Enum\Filter\AutonomyEnum;
use App\Enum\Filter\ContractLengthEnum;
use App\Enum\Filter\PriceRangeEnum;
use App\Http\Requests\VehicleSearchRequest;
use App\Http\Resources\VehicleResource;
use App\Models\ClientType;
use App\Models\Feature;
use App\Models\VehicleUse;
use App\Repository\VehicleRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class VehicleController extends Controller
{
    public function __construct(
        private VehicleRepository $vehicleRepository
    ) {
    }

    public function search(VehicleSearchRequest $request): JsonResponse
    {
        $vehicles = $this->vehicleRepository->getAllVehicles($request);

        return response()
            ->json([
                'success' => true,
                'message' => 'Vehicles fetched successfully.',
                'data' => VehicleResource::collection($vehicles),
                'pagination' => [
                    'total' => $vehicles->total(),
                    'per_page' => $vehicles->perPage(),
                    'current_page' => $vehicles->currentPage(),
                    'last_page' => $vehicles->lastPage(),
                    'from' => $vehicles->firstItem(),
                    'to' => $vehicles->lastItem(),
                ],
            ], Response::HTTP_OK);
    }

    public function filters(): JsonResponse
    {
        $prices = collect(PriceRangeEnum::cases())
            ->map(function ($price) {
                return [
                    'id' => $price->value,
                    'label' => $price->getLabel(),
                ];
            });

        $autonomies = collect(AutonomyEnum::cases())
            ->map(function ($autonomy) {
                return [
                    'id' => $autonomy->value,
                    'label' => $autonomy->getLabel(),
                ];
            });

        return response()
            ->json([
                'success' => true,
                'message' => 'Filters fetched successfully.',
                'data' => [
                    'client_types' => ClientType::all(['id', 'name']),
                    'vehicle_uses' => VehicleUse::all(['id', 'name']),
                    'prices' => $prices,
                    'autonomies' => $autonomies,
                    'contract_lengths' => ContractLengthEnum::cases(),
                    'features' => Feature::all(['id', 'name']),
                ],
            ], Response::HTTP_OK);
    }
}
