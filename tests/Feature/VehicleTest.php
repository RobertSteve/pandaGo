<?php

namespace Tests\Feature;

use App\Enum\Filter\ContractLengthEnum;
use App\Enum\Filter\PriceRangeEnum;
use App\Models\ClientType;
use App\Models\Feature;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\VehiclePrice;
use App\Models\VehicleReview;
use App\Models\VehicleUse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    const BASE_URL = '/api/vehicles/search';

    protected function setUp(): void
    {
        parent::setUp();

        $features = Feature::factory(5)->create();
        $clientTypes = ClientType::factory(3)->create();
        $vehicleUses = VehicleUse::factory(5)->create();

        Vehicle::factory(9)
            ->has(VehicleImage::factory()->count(2), 'images')
            ->has(VehicleReview::factory()->count(3), 'reviews')
            ->has(VehiclePrice::factory()->count(5), 'prices')
            ->create()
            ->each(function (Vehicle $vehicle) use ($features, $clientTypes, $vehicleUses) {
                $vehicle->images()->save(VehicleImage::factory()->make([
                    'is_main' => true,
                ]));
                $selectedFeatures = $features->random(rand(1, 3));
                $selectedClientTypes = $clientTypes->random(rand(1, $clientTypes->count()));
                $selectedVehicleUses = $vehicleUses->random(rand(1, 3));
                $vehicle->features()->attach($selectedFeatures);
                $vehicle->clientTypes()->attach($selectedClientTypes);
                $vehicle->uses()->attach($selectedVehicleUses);
            });
    }

    #[Test]
    public function it_validates_client_type_id()
    {
        // ClientTypeId es un entero
        $this->json('GET', self::BASE_URL, [
            'client_type_id' => 'invalid_type',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['client_type_id']);

        // ClientTypeId no existe en la tabla client_types
        $this->json('GET', self::BASE_URL, [
            'client_type_id' => 0,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['client_type_id']);
    }

    #[Test]
    public function it_validates_vehicle_use_id()
    {
        // VehicleUseId es un entero
        $this->json('GET', self::BASE_URL, [
            'vehicle_use_id' => 'invalid_type',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['vehicle_use_id']);

        // VehicleUseId no existe en la tabla vehicle_uses
        $this->json('GET', self::BASE_URL, [
            'vehicle_use_id' => 0,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['vehicle_use_id']);
    }

    #[Test]
    public function it_validates_price_range_id()
    {
        // PriceRangeId es un entero
        $this->json('GET', self::BASE_URL, [
            'price_range_id' => 'invalid_type',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['price_range_id']);

        // PriceRangeId no existe en la tabla price_ranges
        $this->json('GET', self::BASE_URL, [
            'price_range_id' => 0,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['price_range_id']);
    }

    #[Test]
    public function it_validates_autonomy_id()
    {
        // Autonomía es un entero
        $this->json('GET', self::BASE_URL, [
            'autonomy_id' => 'invalid_type',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['autonomy_id']);

        // Autonomía no existe en el enum AutonomyEnum
        $this->json('GET', self::BASE_URL, [
            'autonomy_id' => 0,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['autonomy_id']);
    }

    #[Test]
    public function it_validates_contract_length()
    {
        // ContractType es un entero
        $this->json('GET', self::BASE_URL, [
            'contract_length' => 'invalid_type',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['contract_length']);

        // ContractType no existe en el enum ContractLengthEnum
        $this->json('GET', self::BASE_URL, [
            'contract_length' => 0,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['contract_length']);
    }

    #[Test]
    public function it_validates_features()
    {
        // Features es un arreglo
        $this->json('GET', self::BASE_URL, [
            'features' => 1,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['features']);

        // El contenido features existe en la tabla features
        $this->json('GET', self::BASE_URL, [
            'features' => [0],
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['features']);
    }

    #[Test]
    public function it_filters_by_client_type_id()
    {
        $clientTypeId = ClientType::query()->first()->id;
        $expectedCount = Vehicle::query()
            ->whereHas('clientTypes', fn($query) => $query->where('client_type_id', $clientTypeId))
            ->count();

        $this->json('GET', self::BASE_URL, [
            'client_type_id' => $clientTypeId,
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($expectedCount, 'data')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'image_path',
                        'reviews' => [
                            'average',
                            'count',
                        ],
                        'minimum_price',
                    ],
                ],
            ]);
    }

    #[Test]
    public function it_filters_by_vehicle_use_id()
    {
        $vehicleUseId = VehicleUse::query()->first()->id;
        $expectedCount = Vehicle::query()
            ->whereHas('uses', fn($query) => $query->where('vehicle_use_id', $vehicleUseId))
            ->count();

        $this->json('GET', self::BASE_URL, [
            'vehicle_use_id' => $vehicleUseId,
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($expectedCount, 'data')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'image_path',
                        'reviews' => [
                            'average',
                            'count',
                        ],
                        'minimum_price',
                    ],
                ],
            ]);
    }

    #[Test]
    public function it_filters_by_price_range_id()
    {
        $expectedCount = Vehicle::query()
            ->whereHas('prices', fn($query) => $query->whereBetween('price', [100, 150]))
            ->count();

        $this->json('GET', self::BASE_URL, [
            'price_range_id' => PriceRangeEnum::BETWEEN_100_AND_150->value,
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($expectedCount, 'data')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'image_path',
                        'reviews' => [
                            'average',
                            'count',
                        ],
                        'minimum_price',
                    ],
                ],
            ]);
    }

    #[Test]
    public function it_filters_by_contract_length()
    {
        $expectedCount = Vehicle::query()
            ->whereHas('prices', fn($query) => $query->where('contract_length_months', ContractLengthEnum::MONTHS_12->value))
            ->count();

        $this->json('GET', self::BASE_URL, [
            'contract_length' => ContractLengthEnum::MONTHS_12->value,
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($expectedCount, 'data')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'image_path',
                        'reviews' => [
                            'average',
                            'count',
                        ],
                        'minimum_price',
                    ],
                ],
            ]);
    }

    #[Test]
    public function it_filters_by_features()
    {
        $features = Feature::query()->limit(2)->pluck('id');
        $expectedCount = Vehicle::query()
            ->whereHas('features', fn($query) => $query->whereIn('feature_id', $features))
            ->count();

        $this->json('GET', self::BASE_URL, [
            'features' => $features,
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($expectedCount, 'data')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'image_path',
                        'reviews' => [
                            'average',
                            'count',
                        ],
                        'minimum_price',
                    ],
                ],
            ]);
    }
}
