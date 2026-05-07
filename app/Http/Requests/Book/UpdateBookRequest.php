<?php

namespace App\Http\Requests\Book;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'coverUrl' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'publicationDate' => 'nullable|date',
            'id_author' => 'sometimes|exists:authors,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'price.numeric' => 'Le prix doit être un nombre.',
            'id_author.exists' => 'L\'auteur sélectionné n\'existe pas.',
            'categories.array' => 'Les catégories doivent être un tableau.',
            'coverUrl.image' => 'Le fichier doit être une image.',
        ];
    }
}
