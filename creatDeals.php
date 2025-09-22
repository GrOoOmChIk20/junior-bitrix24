<?php
require_once('crest.php');

class GetContacts
{
    /**
     * Получает список клиентов из Bitrix24
     *
     * @return array Массив контактов
     */
    public function getClients(): array
    {
        $result = CRest::call(
            'crm.item.list',
            [
                'entityTypeId' => 3,
                'select'       => [
                    "id",
                ],
            ]
        );

        return $result['result']['items'] ?? [];
    }
}

class DealCreator
{
    /**
     * Создает указанное количество сделок со случайными клиентами
     *
     * @param int $count Количество сделок для создания
     * @param GetContacts $contacts Контыкты клиентов
     */
    public function createRandomDeals(int $count, GetContacts $contacts): void
    {
        $clients = $contacts->getClients();

        if (count($clients) < 1) {
            throw new RuntimeException("Нет доступных клиентов для создания сделок");
        }

        for ($i = 0; $i < $count; $i++) {
            $randPos = rand(0, 4); // Случайный выбор из 5 клиентов
            $client = $clients[$randPos];

            $this->createDeal($client);
        }
    }

    /**
     * Создает одну сделку для указанного клиента
     *
     * @param array $client Данные клиента
     */
    private function createDeal(array $client): void
    {
        $fields = [
            'entityTypeId' => 2,
            'fields'       => [
                'title'      => "Новая сделка для клиента с id " . $client['id'],
                'typeId'     => "Sale",
                'categoryId' => 0,
                'contactId'  => $client['id'],
                'currencyId' => "RUB",
            ],
        ];

        CRest::call('crm.item.add', $fields);
    }
}

// Использование
try {
    $contacts = new GetContacts();

    // Создаем 15 сделок
    (new DealCreator())->createRandomDeals(15, $contacts);

    return;
} catch (RuntimeException $e) {
    echo "Ошибка: " . $e->getMessage();
}