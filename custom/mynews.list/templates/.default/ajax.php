<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php foreach ($arResult["ITEMS"] as $arItem): ?>
    <div class="news-item">
        <h3><?= $arItem['NAME'] ?></h3>
        <p>Дата: <?= $arItem['DATE_ACTIVE_FROM'] ?></p>
        <p>Стоимость: <?= $arItem['PROPERTY_COST'] ?></p>
        <p>Особенность: <?= $arItem['PROPERTY_FEATURE_VALUE'] ?></p>
    </div>
<?php endforeach; ?>