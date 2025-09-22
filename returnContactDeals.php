<?php
require_once('crest.php');

class ContactInfoRetriever
{
    /**
     * Получает контакты с информацией о сделках
     *
     * @return array Массив контактов с данными о сделках
     */
    public function getContactsWithDeals(): array
    {
        // 1. Получаем список контактов
        $contacts = $this->fetchContacts();

        // 2. Дополняем каждый контакт информацией о сделках
        foreach ($contacts as &$contact) {
            $this->enrichContactWithDeals($contact);
        }

        return $contacts;
    }

    /**
     * Получает базовые данные контактов
     *
     * @return array Массив контактов
     */
    private function fetchContacts(): array
    {
        $result = CRest::call(
            'crm.item.list',
            [
                'entityTypeId' => 3,
                'select'       => [
                    "id",
                    "name",
                    "lastName",
                    "secondName",
                    "phoneWork",
                    "emailWork",
                ],
            ]
        );

        return $result['result']['items'] ?? [];
    }

    /**
     * Дополняет контакт информацией о сделках
     *
     * @param array &$contact Контакт для дополнения
     */
    private function enrichContactWithDeals(array &$contact): void
    {
        $deals = CRest::call(
            'crm.item.list',
            [
                'entityTypeId' => 2,
                'filter'       => [
                    'CONTACT_ID' => $contact['id']
                ],
                'select'       => [
                    'ID'
                ]
            ]
        );

        $contact['deal_count'] = count($deals['result']['items']);
        $contact['deal_ids'] = array_column($deals['result']['items'], 'id');
    }
}

// Использование
try {
    $contactRetriever = new ContactInfoRetriever();
    $contacts = $contactRetriever->getContactsWithDeals();

    // Вывод в формате JSON
    echo json_encode($contacts);

} catch (RuntimeException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}