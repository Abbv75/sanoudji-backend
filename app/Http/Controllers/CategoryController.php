<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::all();
        return $this->success($categories, 'Liste des catégories récupérée avec succès');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('coverUrl')) {
            $path = $request->file('coverUrl')->store('images', 'public');
            $data['coverUrl'] = Storage::url($path);
        }

        $category = Category::create($data);
        return $this->success($category, 'Catégorie créée avec succès', 201);
    }

    /**
     * Display the specified category with its books.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->notFound('Catégorie non trouvée');
        }

        // Charger les livres de cette catégorie
        $category->setRelation('books', $category->books()->with('author')->get());

        return $this->success($category, 'Détails de la catégorie récupérés avec succès');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->notFound('Catégorie non trouvée');
        }

        $data = $request->validated();

        if ($request->hasFile('coverUrl')) {
            // Supprimer l'ancienne image si elle existe
            if ($category->coverUrl) {
                $oldPath = str_replace('/storage/', '', $category->coverUrl);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('coverUrl')->store('images', 'public');
            $data['coverUrl'] = Storage::url($path);
        }

        $category->update($data);

        return $this->success($category, 'Catégorie mise à jour avec succès');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->notFound('Catégorie non trouvée');
        }

        // Supprimer l'image associée
        if ($category->coverUrl) {
            $oldPath = str_replace('/storage/', '', $category->coverUrl);
            Storage::disk('public')->delete($oldPath);
        }

        $category->delete();

        return $this->success(null, 'Catégorie supprimée avec succès');
    }
}
