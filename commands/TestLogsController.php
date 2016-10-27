<?php

namespace app\commands;

use yii\console\Controller;

class TestLogsController extends Controller
{

    public function actionIndex()
    {

        $logger = new Logs();

        $testMessages = [
            '1234567890 2013-03-08 12:26:09',
            '0987654321 2013-03-09 09:23:17',
            '1234567890 2014-01-01 00:00:34',
            '0087645544 2015-02-03 17:45:01',
            '0087645544 2015-01-03 11:05:06',
        ];

        $assetMessages = [
            '0087645544 2015-01-03 11:05:06',
            '0087645544 2015-02-03 17:45:01',
            '0987654321 2013-03-09 09:23:17',
            '1234567890 2013-03-08 12:26:09',
            '1234567890 2014-01-01 00:00:34',
        ];

        foreach ($testMessages as $message) {
            $logger->add($message);
        }

        $result = ($logger->getLog() === $assetMessages) ? 'Тест пройден' : 'Тест провален';
        $this->stdout($result . PHP_EOL);
        // Очистка лога - для тестирования
        $logger->purgeLog();
    }
}
