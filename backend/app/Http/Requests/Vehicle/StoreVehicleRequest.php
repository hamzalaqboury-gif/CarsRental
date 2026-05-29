<?php

namespace App\Http\Requests\Vehicle;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'brand'         => ['required', 'string', 'max:100'],
            'model'         => ['required', 'string', 'max:100'],
            'category'      => ['required', 'string', 'in:sedan,suv,truck,van,luxury,economy,sports,convertible'],
            'transmission'  => ['required', 'in:automatic,manual'],
            'fuel_type'     => ['required', 'in:petrol,diesel,electric,hybrid'],
            'price_per_day' => ['required', 'numeric', 'min:1', 'max:99999'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'is_available'  => ['sometimes', 'boolean'],
            'year'          => ['nullable', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'seats'         => ['nullable', 'integer', 'min:1', 'max:20'],
            'color'         => ['nullable', 'string', 'max:50'],
            'license_plate' => ['nullable', 'string', 'max:20', 'unique:vehicles,license_plate'],
            'image'         => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ];
    }
}
