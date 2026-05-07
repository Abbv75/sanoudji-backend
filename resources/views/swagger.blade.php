<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sanoudji API – Documentation</title>
  <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-themes@3.0.0/themes/3.x/theme-material.css" />
  <style>
    body { 
      background-color: #1b1b1b; 
      margin: 0; 
      padding: 0; 
    }
    .swagger-ui {
      filter: invert(88%) hue-rotate(180deg) brightness(1.05) contrast(1.05);
    }
    .swagger-ui .topbar { 
      display: none; 
    }
    /* Restauration des couleurs d'images et certains éléments après inversion */
    .swagger-ui img, .swagger-ui .opblock-summary-method {
      filter: invert(100%) hue-rotate(180deg);
    }
  </style>
</head>
<body>
  <div id="swagger-ui"></div>

  <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
  <script>
    window.onload = function () {
      SwaggerUIBundle({
        url: "{{ $specUrl }}",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
          SwaggerUIBundle.presets.apis,
          SwaggerUIBundle.SwaggerUIStandalonePreset
        ],
        layout: "BaseLayout",
        persistAuthorization: true,
      });
    };
  </script>
</body>
</html>
