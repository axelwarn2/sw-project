<?php

return [
    "GET" => [
        "/" => "App\Controllers\ViewController@index",
        "/download" => "App\Controllers\FileController@download",
    ],
    "POST" => [
        "/create-directory" => "App\Controllers\DirectoryController@createDirectory",
        "/create-file" => "App\Controllers\FileController@createFile",
        "/delete" => "App\Controllers\DirectoryController@delete",
    ],
];
