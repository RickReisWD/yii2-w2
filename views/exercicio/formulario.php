<?php
use \yii\bootstrap5\ActiveForm;
use \yii\helpers\Html;
?>

<h2>Formulário de cadastro</h2>
<hr>

<?php $form = ActiveForm::begin([
    'id' => 'formulario',
    'fieldConfig' => [
    'template' => "{label}\n{input}\n{error}",
    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
    'inputOptions' => ['class' => 'col-lg-3 form-control'],
    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
    ],
  ]); ?>
<?= $form->field($model, 'nome')->textInput(['autofocus' => true]) ?>
<?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
<?= $form->field($model, 'idade')->textInput(['autofocus' => true]) ?>
<div class="form-group">
    <?= Html::submitButton('Enviar dados', ['class'=>'btn btn-primary'])?>
</div>
<?php ActiveForm::end()?>


