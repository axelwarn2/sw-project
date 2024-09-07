<?php

return [
    "GET" => [
        "/" => "App\Controllers\Controller@index",
    ],
    "POST" => [
        "/create-directory" => "App\Controllers\Controller@createDirectory",
        "/create-file" => "App\Controllers\Controller@createFile",
        "/delete" => "App\Controllers\Controller@delete",
    ],
];
