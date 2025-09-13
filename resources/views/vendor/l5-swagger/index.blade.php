<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>{{ $documentationTitle ?? 'Swagger UI' }}</title>

  <!-- Swagger UI CSS -->
  <link href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
    }

    #swagger-ui {
      width: 100%;
      height: 100vh;
    }

    @if(config('l5-swagger.defaults.ui.display.dark_mode')) body {
      background-color: #1e1e1e;
      color: #ffffff;
    }

    @endif
  </style>
</head>

<body>
  <div id="swagger-ui"></div>

  <!-- Swagger UI JS -->

  <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"></script>
  <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}"></script>
  <script>
    window.onload = function() {
      const urls = [];

      @foreach($urlsToDocs as $title => $url)
      urls.push({
        name: "{{ $title }}",
        url: "{{ $url }}"
      });
      @endforeach

      const ui = SwaggerUIBundle({
        dom_id: '#swagger-ui',
        urls: urls,
        "urls.primaryName": "{{ $documentationTitle }}",
        operationsSorter: {
          !!isset($operationsSorter) ? json_encode($operationsSorter) : 'null'!!
        },
        configUrl: {
          !!isset($configUrl) ? json_encode($configUrl) : 'null'!!
        },
        validatorUrl: {
          !!isset($validatorUrl) ? json_encode($validatorUrl) : 'null'!!
        },
        oauth2RedirectUrl: "{{ route('l5-swagger.'.$documentation.'.oauth2_callback', [], $useAbsolutePath) }}",

        requestInterceptor: function(request) {
          request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
          return request;
        },

        presets: [
          SwaggerUIBundle.presets.apis,
          SwaggerUIStandalonePreset
        ],

        plugins: [
          SwaggerUIBundle.plugins.DownloadUrl
        ],

        layout: "StandaloneLayout",
        docExpansion: {
          !!json_encode(config('l5-swagger.defaults.ui.display.doc_expansion', 'none')) !!
        },
        deepLinking: true,
        filter: {
          {
            config('l5-swagger.defaults.ui.display.filter') ? 'true' : 'false'
          }
        },
        persistAuthorization: {
          {
            config('l5-swagger.defaults.ui.authorization.persist_authorization') ? 'true' : 'false'
          }
        },
      });

      window.ui = ui;

      @if(in_array('oauth2', array_column(config('l5-swagger.defaults.securityDefinitions.securitySchemes'), 'type')))
      ui.initOAuth({
        usePkceWithAuthorizationCodeGrant: {
          {
            config('l5-swagger.defaults.ui.authorization.oauth2.use_pkce_with_authorization_code_grant') ? 'true' : 'false'
          }
        }
      });
      @endif
    }
  </script>
</body>

</html>