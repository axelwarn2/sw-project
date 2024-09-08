<?php

return [
    "GET" => [
        "/" => "App\Controllers\ViewController@index",
    ],
    "POST" => [
        "/create-directory" => "App\Controllers\DirectoryController@createDirectory",
        "/create-file" => "App\Controllers\FileController@createFile",
        "/delete" => "App\Controllers\DirectoryController@delete",
    ],
];
