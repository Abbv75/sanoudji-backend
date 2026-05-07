<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

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
        $category = Category::create($request->validated());
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

        // Charger les livres de cette catégorie avec pagination
        $category->setRelation('books', $category->books()->with('author')->paginate(15));

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

        $category->update($request->validated());

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

        // Vérifier si la catégorie contient des livres avant de supprimer ? 
        // Ou laisser la cascade/contrainte faire son travail.
        $category->delete();

        return $this->success(null, 'Catégorie supprimée avec succès');
    }
}
