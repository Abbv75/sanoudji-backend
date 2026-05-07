<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class SwaggerController extends Controller
{
    /**
     * Serve the Swagger UI.
     */
    public function ui()
    {
        return view('swagger', [
            'specUrl' => url('/api/docs/spec'),
        ]);
    }

    /**
     * Merge and serve the OpenAPI specification.
     * Combines openapi.yaml (base) with individual domain files.
     */
    public function spec()
    {
        $docsPath = app_path('Http/SwaggerDocumentations');

        // Chargement du fichier de base (info, servers, components, tags)
        $base = Yaml::parseFile("{$docsPath}/openapi.yaml");
        $base['paths'] = [];

        // Fichiers de domaines à fusionner (ordre d'affichage dans l'UI)
        $domainFiles = ['auth', 'users', 'books', 'categories', 'attributes'];

        foreach ($domainFiles as $domain) {
            $filePath = "{$docsPath}/{$domain}.yaml";

            if (File::exists($filePath)) {
                $parsed = Yaml::parseFile($filePath);
                if (!empty($parsed['paths'])) {
                    $base['paths'] = array_merge($base['paths'], $parsed['paths']);
                }
            }
        }

        // Retourner en JSON pour une compatibilité totale avec Swagger UI
        return response()->json($base);
    }
}
