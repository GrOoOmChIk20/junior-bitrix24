<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Параметры по умолчанию
$arParams["FILTER_NAME"] = 'arrFilter';
$arParams["AJAX_MODE"] = $arParams["AJAX_MODE"] ?? 'Y';

// Передаем AJAX_MODE в шаблон
$arResult['AJAX_MODE'] = $arParams["AJAX_MODE"];

// Формируем фильтр ВНЕ зависимости от AJAX
$arFilter = $_REQUEST['filter'] ?? [];

// Применяем фильтр по дате
if (!empty($arFilter['DATE_ACTIVE'])) {
    switch ($arFilter['DATE_ACTIVE']) {
        case 'week':
            $arFilter['>=DATE_ACTIVE_FROM'] = date('d.m.Y', strtotime('-1 week'));
            break;
        case 'month':
            $arFilter['>=DATE_ACTIVE_FROM'] = date('d.m.Y', strtotime('-1 month'));
            break;
    }
    unset($arFilter['DATE_ACTIVE']);
}

// Фильтр по названию и стоимости
if (!empty($arFilter['NAME'])) {
    $arFilter['%NAME'] = $arFilter['NAME'];
    unset($arFilter['NAME']);
}
if (!empty($arFilter['COST'])) {
    $arFilter['PROPERTY_COST'] = $arFilter['COST'];
    unset($arFilter['COST']);
}

// Получаем элементы инфоблока
$arSelect = [
    "ID", 
    "NAME", 
    "DATE_ACTIVE_FROM", 
    "PROPERTY_COST", 
    "PROPERTY_FEATURE"
];

$rsElements = CIBlockElement::GetList(
    ["SORT" => "ASC"],
    $arFilter,
    false,
    ["nPageSize" => 10],
    $arSelect
);

$arResult["ITEMS"] = [];
while ($arElement = $rsElements->GetNext()) {
    $arResult["ITEMS"][] = $arElement;
}

// AJAX-ответ
if ($_REQUEST['ajax'] == 'Y') {
    $APPLICATION->RestartBuffer();
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/custom/mynews.list/templates/.default/ajax.php");
    die();
}

// Подключаем шаблон (работает для обычной загрузки)
$this->IncludeComponentTemplate();
?>