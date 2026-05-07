<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MetadataAttribute;
use App\Http\Requests\MetadataAttribute\StoreMetadataAttributeRequest;
use App\Http\Requests\MetadataAttribute\UpdateMetadataAttributeRequest;

class MetadataAttributeController extends Controller
{
    /**
     * Display a listing of the attributes.
     */
    public function index()
    {
        $attributes = MetadataAttribute::all();
        return $this->success($attributes, 'Liste des attributs récupérée avec succès');
    }

    /**
     * Store a newly created attribute in storage.
     */
    public function store(StoreMetadataAttributeRequest $request)
    {
        $attribute = MetadataAttribute::create($request->validated());
        return $this->success($attribute, 'Attribut créé avec succès', 201);
    }

    /**
     * Display the specified attribute.
     */
    public function show(string $id)
    {
        $attribute = MetadataAttribute::find($id);

        if (!$attribute) {
            return $this->notFound('Attribut non trouvé');
        }

        return $this->success($attribute, 'Détails de l\'attribut récupérés avec succès');
    }

    /**
     * Update the specified attribute in storage.
     */
    public function update(UpdateMetadataAttributeRequest $request, string $id)
    {
        $attribute = MetadataAttribute::find($id);

        if (!$attribute) {
            return $this->notFound('Attribut non trouvé');
        }

        $attribute->update($request->validated());

        return $this->success($attribute, 'Attribut mis à jour avec succès');
    }

    /**
     * Remove the specified attribute from storage.
     */
    public function destroy(string $id)
    {
        $attribute = MetadataAttribute::find($id);

        if (!$attribute) {
            return $this->notFound('Attribut non trouvé');
        }

        $attribute->delete();

        return $this->success(null, 'Attribut supprimé avec succès');
    }
}
