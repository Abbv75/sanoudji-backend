<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

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
     * Serve the OpenAPI YAML specification as JSON.
     */
    public function spec()
    {
        $yamlPath = app_path('Http/SwaggerDocumentations/openapi.yaml');

        if (!File::exists($yamlPath)) {
            return response()->json(['error' => 'Spec not found'], 404);
        }

        // Parse YAML to JSON pour Swagger UI
        $yaml = File::get($yamlPath);

        return response($yaml, 200)->header('Content-Type', 'text/yaml');
    }
}
