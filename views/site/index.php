<?php

/* @var $this yii\web\View */

use kartik\daterange\DateRangePicker;
use kartik\form\ActiveForm;
use yii\bootstrap\Html;

$this->title = 'Статистика пользователей';
?>
<div class="site-index">
    <div class="body-content">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-xs-6">

                <?= DateRangePicker::widget([
                    'model' => $model,
                    'name' => 'date_range',
                    'value' => $model->date_range,
                    'convertFormat' => true,
                    'language'=>'ru',
                    'pluginOptions' => [
                        'locale' => ['format' => 'm-d-Y H:i:s'],
                    ]
                ]); ?>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <?= Html::submitButton('Получить данные', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <canvas id="myBarChart"></canvas>

    </div>
</div>
<script>
    var randomColorFactor = function () {
        return Math.round(Math.random() * 255);
    };
    var randomColor = function () {
        return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.8)';
    };
    var data = {
        labels: <?= json_encode($data['label']) ?>,
        datasets: [
            {
                label: "Конверсия по дням",
                backgroundColor: [
                    <?php foreach ($data['label'] as $time_add) : ?>
                    randomColor(),
                    <?php endforeach; ?>
                ],
                hoverBorderColor: "rgba(255,99,132,1)",
                borderWidth: 1,
                data: <?= json_encode($data['percent']) ?>,
            }
        ]
    };

    var ctx = $("#myBarChart");
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: data,
    });
</script>

