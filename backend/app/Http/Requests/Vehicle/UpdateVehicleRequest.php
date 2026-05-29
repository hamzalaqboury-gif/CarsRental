<?php

namespace App\Http\Requests\Vehicle;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $vehicleId = $this->route('vehicle')?->id ?? $this->route('id');

        return [
            'brand'         => ['sometimes', 'string', 'max:100'],
            'model'         => ['sometimes', 'string', 'max:100'],
            'category'      => ['sometimes', 'string', 'in:sedan,suv,truck,van,luxury,economy,sports,convertible'],
            'transmission'  => ['sometimes', 'in:automatic,manual'],
            'fuel_type'     => ['sometimes', 'in:petrol,diesel,electric,hybrid'],
            'price_per_day' => ['sometimes', 'numeric', 'min:1', 'max:99999'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'is_available'  => ['sometimes', 'boolean'],
            'year'          => ['nullable', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'seats'         => ['nullable', 'integer', 'min:1', 'max:20'],
            'color'         => ['nullable', 'string', 'max:50'],
            'license_plate' => ['nullable', 'string', 'max:20', "unique:vehicles,license_plate,{$vehicleId}"],
            'image'         => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ];
    }
}
