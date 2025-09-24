<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    "PARAMETERS" => [
        "FILTER_NAME" => [
            "NAME" => "Имя фильтра",
            "TYPE" => "STRING",
            "DEFAULT" => "arrFilter"
        ],
        "AJAX_MODE" => [
            "NAME" => "Использовать AJAX",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y"
        ]
    ]
];
?>