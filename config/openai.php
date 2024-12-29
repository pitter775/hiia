<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key
    |--------------------------------------------------------------------------
    |
    | Sua chave da API da OpenAI. Essa chave é usada para autenticar as
    | requisições feitas para os serviços da OpenAI.
    |
    */

    'api_key' => env('OPENAI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Organization (Opcional)
    |--------------------------------------------------------------------------
    |
    | Caso sua conta da OpenAI pertença a uma organização, você pode definir
    | o ID da organização aqui ou no arquivo .env.
    |
    */

    'organization' => env('OPENAI_ORGANIZATION'),
];
