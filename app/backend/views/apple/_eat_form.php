<?php use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<div id="modalContent">
    <?php
    $form = ActiveForm::begin([
            'options' => ['data-pjax' => true],
            'action' => ['apple/eat-apple', 'id' => $model->id],
            'method' => 'post',
    ]); ?>

    <?= $form->field($formModel, 'amount')->textInput(['maxlength' => true])->label('Новое значение') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>