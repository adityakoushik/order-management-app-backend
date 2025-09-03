<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}
	public function rules(): array
	{
		return [
			'name' => 'sometimes|required|string|max:255',
			'desc' => 'sometimes|nullable|string',
			'image' => 'sometimes|file|image|mimes:jpeg,png,jpg,webp|max:5120',
			'status' => 'sometimes|boolean',
		];
	}

}
