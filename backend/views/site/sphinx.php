<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sphinx Search';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-sphinx">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please input keywords to search:</p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'sphinx-form']); ?>
            <?= $form->field($model, 'username') ?>
            <div class="form-group">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'sphinx-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
