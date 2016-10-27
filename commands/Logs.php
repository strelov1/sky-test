<?php

namespace app\commands;

class Logs
{
    public $file = 'users.log';

    /**
     * Добавлям в файл сообщение
     *
     * @param $message
     * @return int
     */
    public function add($message)
    {
        $logs = $this->getLog();
        $logs[] = $message;
        return $this->putLog($this->sortLog($logs));
    }

    /**
     * Очистка лога
     * @return int
     */
    public function purgeLog()
    {
        return file_put_contents($this->getFile(), '');
    }

    /**
     * Получаем лог в виде массива
     *
     * @return array
     */
    public function getLog()
    {
        $logsFile = file_get_contents($this->getFile());
        $logsArray = explode("\n", $logsFile);
        //Ожчищаем от пустых строк
        return array_filter($logsArray, function($item) {
            return $item ? $item : '';
        });
    }

    /**
     * Провеяем наличия файла если нет создаем его
     *
     * @return bool|string
     * @throws \Exception
     */
    private function getFile()
    {
        $file = \Yii::getAlias('@runtime/' . $this->file);
        if (!file_exists($file)) {
            try {
                file_put_contents($file, '');
            } catch (\Exception $e) {
                throw new \Exception('Не удалось создать файл');
            }
        }
        return $file;
    }

    /**
     * Сортировка массива логов
     * @param $array
     * @return mixed
     */
    private function sortLog($array)
    {
        asort($array);
        return $array;
    }

    /**
     * Кладем готовую пачку сообщений в лог
     * @param $array
     * @return int
     */
    private function putLog($array)
    {
        return file_put_contents($this->getFile(), implode("\n", $array));
    }

}
