<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<form id="news-filter" action="<?= $APPLICATION->GetCurPage() ?>" method="get">
    <?php if ($arResult['AJAX_MODE'] == 'Y'): ?>
    <input type="hidden" name="ajax" value="Y">
    <?php endif; ?>
    
    <!-- Поиск по названию -->
    <input type="text" name="filter[NAME]" placeholder="Поиск по названию" 
           value="<?= $_REQUEST['filter']['NAME'] ?? '' ?>">
    
    <!-- Фильтр по стоимости -->
    <input type="number" name="filter[COST]" placeholder="Стоимость" 
           value="<?= $_REQUEST['filter']['COST'] ?? '' ?>">
    
    <!-- Фильтр по дате -->
    <select name="filter[DATE_ACTIVE]">
        <option value="">За все время</option>
        <option value="week" <?= ($_REQUEST['filter']['DATE_ACTIVE'] ?? '') == 'week' ? 'selected' : '' ?>>За неделю</option>
        <option value="month" <?= ($_REQUEST['filter']['DATE_ACTIVE'] ?? '') == 'month' ? 'selected' : '' ?>>За месяц</option>
    </select>
    
    <button type="submit">Найти</button>
</form>

<div id="news-list">
    <?php foreach ($arResult["ITEMS"] as $arItem): ?>
        <div class="news-item">
            <h3><?= $arItem['NAME'] ?></h3>
            <p>Дата: <?= $arItem['DATE_ACTIVE_FROM'] ?></p>
            <p>Стоимость: <?= $arItem['PROPERTY_COST'] ?></p>
            <p>Особенность: <?= $arItem['PROPERTY_FEATURE_VALUE'] ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?php if ($arResult['AJAX_MODE'] == 'Y'): ?>
<script>
document.getElementById('news-filter').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const params = new URLSearchParams(formData).toString();
    
    fetch(window.location.href + '?' + params)
        .then(response => response.text())
        .then(html => {
            document.getElementById('news-list').innerHTML = html;
        });
});
</script>
<?php endif; ?>