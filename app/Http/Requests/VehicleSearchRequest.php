<?php

namespace App\Http\Requests;

use App\Enum\Filter\AutonomyEnum;
use App\Enum\Filter\ContractLengthEnum;
use App\Enum\Filter\PriceRangeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleSearchRequest extends FormRequest
{
    public function authorize(): bool
    {

        return true;
    }

    public function rules(): array
    {

        return [
            'client_type_id' => [
                'integer',
                Rule::exists('client_types', 'id'),
            ],
            'vehicle_use_id' => [
                'integer',
                Rule::exists('vehicle_uses', 'id'),
            ],
            'price_range_id' => [
                'integer',
                Rule::enum(PriceRangeEnum::class),
            ],
            'autonomy_id' => [
                'integer',
                Rule::enum(AutonomyEnum::class),
            ],
            'contract_length' => [
                'integer',
                Rule::enum(ContractLengthEnum::class),
            ],
            'features' => [
                'array',
                Rule::exists('features', 'id'),
            ],
        ];
    }
}
