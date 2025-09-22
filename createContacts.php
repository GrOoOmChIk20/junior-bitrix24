<?php
require_once('crest.php');

class GetContacts
{
    private string $apiUrl;

    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * Получает данные клиентов из внешнего API
     *
     * @return array Массив данных клиентов
     * @throws RuntimeException При ошибке запроса или декодирования
     */
    public function fetchContacts(): array
    {
        $apiResponse = file_get_contents($this->apiUrl);

        if ($apiResponse === false) {
            throw new RuntimeException("Ошибка получения данных из API");
        }

        $contactsData = json_decode($apiResponse, true);

        return $contactsData;
    }
}

class ContactCreator
{
    public static string $valueTypeTel = 'WORK';
    public static string $typeIdTel = 'PHONE';
    public static string $valueTypeEmail = 'WORK';
    public static string $typeIdEmail = 'EMAIL';

    /**
     * Создает контакты в Bitrix24 из массива клиентов
     *
     * @param array $clients Массив данных клиентов
     */
    public function createContacts(array $clients): void
    {
        foreach ($clients as $client) {
            $this->createContact($client);
        }
    }

    /**
     * Создает отдельный контакт
     *
     * @param array $client Данные клиента
     */
    private function createContact(array $client): void
    {
        $fields = [
            'entityTypeId' => 3,
            'fields'       => [
                'name'       => $client['FirstName'],
                'lastName'   => $client['LastName'],
                'secondName' => $client['FatherName'],
                'fm'         => $this->buildContactCommunication($client)
            ]
        ];

        CRest::call('crm.item.add', $fields);
    }

    /**
     * Формирует массив данных (телефон/email)
     *
     * @param array $client Данные клиента
     * @return array Массив данных
     */
    private function buildContactCommunication(array $client): array
    {
        return [
            [
                'valueType' => self::$valueTypeTel,
                'value'     => $client['Phone'],
                'typeId'    => self::$typeIdTel
            ],
            [
                'valueType' => self::$valueTypeEmail,
                'value'     => $client['Email'],
                'typeId'    => self::$typeIdEmail
            ]
        ];
    }
}

// Использование
try {
    $apiUrl = 'https://api.randomdatatools.ru/?count=5&params=LastName,FirstName,FatherName,Phone,Email';

    $dataFetcher = new GetContacts($apiUrl);
    $clients = $dataFetcher->fetchContacts();

    $contactCreator = new ContactCreator();
    $contactCreator->createContacts($clients);

    return;

} catch (RuntimeException $e) {
    echo "Ошибка: " . $e->getMessage();
}