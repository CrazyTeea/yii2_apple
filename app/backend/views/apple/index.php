<?php

use backend\models\Apple;

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Alert;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\grid\ActionColumn;


use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var Apple $model */

$this->title = 'Главная яблок';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['id' => 'main-grid-pjax-container']); ?>

    <?= Yii::$app->session->getFlash('success') ? Alert::widget([
            'options' => [
                    'class' => 'alert-success',
            ],
            'body' => Yii::$app->session->getFlash('success'),
    ]) : ''; ?>
    <?=  Yii::$app->session->getFlash('error') ? Alert::widget([
            'options' => [
                    'class' => 'alert-danger',
            ],
            'body' => Yii::$app->session->getFlash('error'),
    ]) : ''; ?>

    <div class="row">
        <div class="col-6"></div>
        <div class="col-6">
            <?php $form = ActiveForm::begin([
                    'action' => ['add-apples'],
                    'options' => ['data-pjax' => true,],
                    'method' => 'post',

            ]); ?>
            <div class="row">
                <div class="col ">
                    <?= $form->field($model, 'amount') ?>
                </div>
                <div class="col align-content-center">
                    <?= Html::submitButton('Добавить яблок', ['class' => 'btn btn-success']) ?>
                </div>
            </div>

            <?php ActiveForm::end() ?>
        </div>


    </div>

    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'color',
                    'status',
                    'eaten',
                    'size',
                    [
                            'class' => ActionColumn::class,
                            'template' => '{fall} {eat} {delete}',
                            'urlCreator' => function ($action, Apple $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            },
                            'buttons' => [
                                    'fall' => function ($url, $model, $key) {
                                        return Html::a('<i class="bi bi-arrow-90deg-down"></i>', $url, [
                                                'data'=>['pjax'=>true,'method'=>'post',]
                                        ]);
                                    },
                                    'eat' => function ($url, $model, $key) {
                                        return Html::a('<i class="bi bi-fork-knife"></i>', '#', [
                                                'title' => Yii::t('app', 'Update'),
                                                'data-bs-toggle' => 'modal',
                                                'data-bs-target' => '#modal-eat-pjax-container', // Target the modal ID
                                                'data-url' => Url::to(['eat-form', 'id' => $model->id]), // The action that returns the form
                                        ]);
                                    }
                            ]
                    ],
            ],
    ]); ?>

    <?php Pjax::end(); ?>

    <?php Modal::begin([
            'title' => '<h4>Откусить яблоко</h4>',
            'id' => 'modal-eat-pjax-container',
    ])?>

    <?php Pjax::begin([
            'id' => 'modal-pjax-form',
        // This makes sure links *inside* the modal form container use Pjax
            'enablePushState' => false,
    ]); ?>
    <div id="modalContent"></div>

    <?php Pjax::end(); ?>

    <?php Modal::end();?>

    <?php $this->registerJs(<<<JS
    $('#modal-eat-pjax-container').on('show.bs.modal', function (event) {
        console.log('ejjejrj')
        var button = $(event.relatedTarget); // Кнопка, вызвавшая модал
        var url = button.data('url');        // URL из data-url атрибута
        var modal = $(this);
        
        // Очищаем предыдущее содержимое и загружаем новое через GET-запрос
        modal.find('#modalContent').html('Загрузка...');
        $.get(url, function (data) {
            modal.find('#modalContent').html(data);
        });
    });

    // B. Обработка завершения Pjax-запроса внутри модального окна
    $(document).on('pjax:end', function(event) {
        // Проверяем, что событие произошло в контейнере формы модала
        if (event.target.id == 'modal-pjax-form') {
            // В идеале здесь нужно проверить ответ сервера на успех/ошибку.
            // Простейший способ: если форма была отправлена, мы считаем, что все ок.

            // 1. Закрываем модальное окно
            $('#modal-eat-pjax-container').modal('hide');
            
            // 2. Обновляем основной Pjax-контейнер с таблицей
            $.pjax.reload({container: '#main-grid-pjax-container'});
        }
    });
JS);
    ?>

</div>
