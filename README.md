Документация по настройке и использованию скриптов интеграции с Bitrix24 и компонента новостей.

## О проекте
Набор скриптов для:
- Создания контактов в Bitrix24 CRM createContacts.php
- Создания сделок в Bitrix24 CRM creatDeals.php
- Получения данных о контактах с привязкой сделок returnContactDeals .php
- Вывода новостей с фильтрацией (AJAX/обычная загрузка)

## Установка

### 1. Для выполнения работы скриптов нужно в settings указать входящий webhook.
```php
define('C_REST_WEB_HOOK_URL','ВАШ_WEB_HOOK');//url on creat Webhook
```

### 2. Размещение компонента
```bash
# Переместите папку custom в директорию компонентов
mv custom /path/to/bitrix/components/
```

## Выполнение

### 1. Запуск скриптов
```bash
php createContacts.php
```
```bash
php creatDeals.php
```
```bash
php returnContactDeals.php
```

### 2. Выполнение компонента. Создать страницу и в режиме php кода прописать
```php
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новос");
// Вызов компонента
$APPLICATION->IncludeComponent(
	"custom:mynews.list", 
	".default", 
	array(
		"AJAX_MODE" => "Y",
		"COMPONENT_TEMPLATE" => ".default",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"FILTER_NAME" => "arrFilter"
	),
	false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
```
