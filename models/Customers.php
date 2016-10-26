<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "customers".
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $phone
 * @property string $time_add
 * @property string $status
 */
class Customers extends \yii\db\ActiveRecord
{

    const STATUS_NEW = 'new';
    const STATUS_REGISTERED = 'registered';
    const STATUS_REFUSED = 'refused';
    const STATUS_UNAVAILABLE = 'unavailable';

    public $date_range;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time_add'], 'required'],
            [['time_add','date_range'], 'safe'],
            [['status'], 'string'],
            [['name', 'surname', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'phone' => 'Phone',
            'time_add' => 'Time Add',
            'status' => 'Status',
        ];
    }

    public function getPeriod()
    {
        return explode(' - ', $this->date_range);
    }

    public function getPeriodStart()
    {
        return isset($this->getPeriod()[0]) ? $this->getPeriod()[0] : null;
    }

    public function getPeriodStop()
    {
        return isset($this->getPeriod()[1]) ? $this->getPeriod()[1] : null;
    }

    public function getData()
    {
        $label = [];
        $percent = [];
        foreach ($this->getAll() as $item) {
            $label[] = $item['time_add'];
            $percent[] = $this->percent($item['reg_count'], $item['all_count']);
        }
        return ['label' => $label, 'percent' => $percent];
    }

    public function getAll()
    {
        $query = Yii::$app->db->createCommand("
            SELECT
              all_customers.time_add as time_add,
              all_customers.count as all_count,
              reg_customers.count as reg_count
            FROM
              (
                SELECT
                  COUNT(customers.id) AS count,
                  DATE_FORMAT(time_add, '%d-%m-%Y') AS time_add
                FROM customers
                GROUP BY DATE_FORMAT(time_add, '%d-%m-%Y')
              ) all_customers,
              (
              SELECT
                COUNT(customers.id) AS count,
                DATE_FORMAT(time_add, '%d-%m-%Y') AS time_add
              FROM customers
              WHERE STATUS = 'registered'
              GROUP BY DATE_FORMAT(time_add, '%d-%m-%Y')
              ) reg_customers
            WHERE all_customers.time_add = reg_customers.time_add
            AND all_customers.time_add > :period_start
            AND all_customers.time_add < :period_stop
            ");

        if ($this->date_range) {
            $query->bindValue(':period_start', $this->getPeriodStart())
                ->bindValue(':period_stop', $this->getPeriodStop());
        } else {
            $query->bindValue(':period_start', date('m-d-Y 00:00:00', strtotime('-9 month')))
                ->bindValue(':period_stop', date('m-d-Y 00:00:00', time()));
        }
        return $query->queryAll();
    }

    /**
     * Считаем процент зарегестрированных
     *
     * @param $reg_count
     * @param $all_count
     * @return float
     */
    public function percent($reg_count, $all_count)
    {
        return round($reg_count * 100 / $all_count, 2);
    }
}
