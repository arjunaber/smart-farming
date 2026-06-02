<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RAG Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the Python RAG (Retrieval-Augmented Generation) service.
    | All communication uses service-to-service authentication via a shared
    | secret token. No user credentials or bearer tokens are forwarded.
    |
    */

    'endpoint' => env('RAG_ENDPOINT', 'http://localhost:8000'),

    'token' => env('RAG_SERVICE_TOKEN'),

    'timeout' => env('RAG_TIMEOUT', 30),
];
