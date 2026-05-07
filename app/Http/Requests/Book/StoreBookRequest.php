<?php

namespace App\Http\Requests\Book;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'coverUrl' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'publicationDate' => 'nullable|date',
            'id_author' => 'required|exists:authors,id',
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
            'title.required' => 'Le titre est requis.',
            'price.required' => 'Le prix est requis.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'stock.required' => 'Le stock est requis.',
            'id_author.required' => 'L\'auteur est requis.',
            'id_author.exists' => 'L\'auteur sélectionné n\'existe pas.',
            'categories.array' => 'Les catégories doivent être un tableau.',
            'categories.*.exists' => 'Une des catégories sélectionnées n\'existe pas.',
            'coverUrl.image' => 'Le fichier doit être une image.',
            'coverUrl.mimes' => 'L\'image doit être au format: jpeg, png, jpg, gif ou svg.',
        ];
    }
}
