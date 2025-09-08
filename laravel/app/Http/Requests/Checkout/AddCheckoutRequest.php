<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class AddCheckoutRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'delivery_date' => ['required', 'date', 'after_or_equal:today'],
			'notes' => ['nullable', 'string', 'max:2000'],
			// Optional: shipping fields if you collect them now
			'shipping_name' => ['nullable', 'string', 'max:255'],
			'shipping_phone' => ['nullable', 'string', 'max:50'],
			'shipping_address_1' => ['nullable', 'string', 'max:255'],
			'shipping_address_2' => ['nullable', 'string', 'max:255'],
			'shipping_city' => ['nullable', 'string', 'max:100'],
			'shipping_state' => ['nullable', 'string', 'max:100'],
			'shipping_postal_code' => ['nullable', 'string', 'max:30'],
			'shipping_country' => ['nullable', 'string', 'max:2'],
		];
	}
}
