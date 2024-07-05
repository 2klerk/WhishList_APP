<h1>Ваши желания</h1>
<?php

    /**
     * Создание ссылки - поделиться
     *
     * @return string
     */
function share_link(): string
{
    $hostName = Yii::$app->request->hostName;
    $port = Yii::$app->request->port;
    return $hostName.":".$port."/api/share_link/".md5(generateUuid());
}

    /**
     * Генерация уникального идентификатора для ссылки-поделиться
     *
     * @return string
     */
function generateUuid()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return sprintf('%s-%s-%s-%s-%s',
            bin2hex(substr($data, 0, 4)),
            bin2hex(substr($data, 4, 2)),
            bin2hex(substr($data, 6, 2)),
            bin2hex(substr($data, 8, 2)),
            bin2hex(substr($data, 10, 6))
        );
    }


echo "Поделиться ".share_link();