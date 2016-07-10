<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\Typeahead;
use common\widgets\AppFormStatusBar;
use common\models\AppApplicant;
use common\models\AppFormStatus;
use common\models\Application;
use common\models\State;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Application */

$this->title = Yii::t('app', 'Application Form');
if (Yii::$app->user->getIdentity()->id_user_role == 4) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Application'), 'url' => ['/loan/application']];
    $this->params['breadcrumbs'][] = $this->title;

} else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Applications'), 'url' => ['/application/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', Application::findOne($this->context->actionParams['id_application'])->application_name), 'url' => ['/application/view', 'id' => $this->context->actionParams['id_application']]];
    $this->params['breadcrumbs'][] = $this->title;
}

$isRead = $this->context->AUTH == Dict::AUTH_READ;
$js = <<< SCRIPT
$('#ajax-submit').click(function(){
    $.post('',$('form').serialize(),function(){
        $("#test_up").slideUp("slow");
    });
});
$('#sync').click(function(){
    $('#appapplicant-co-address_street').val($('#appapplicant-primary-address_street').val());
});
SCRIPT;
$this->registerJs($js);
?>
<section class="content-wrap">
    <div class="container">
        <div class="row pt50">
            <main class="col-md-9">
                <div class="form-nav-tab">
                    <div class="form-nav">
                        <?= $this->render('_header_tab', [
                            'id_application' => $this->context->actionParams['id_application'],
                        ]); ?>
                    </div>
                </div>
                <?php $form = ActiveForm::Begin([
                    "fieldConfig" => [
                        "inputOptions" => ["disabled" => $isRead],
                    ]]); ?>
                <div class="content-form-box">
                    <div class="form-body">
                        <div class="form-header-tp">
                            <h4 id="app_licant" style="cursor:pointer">
                                <i class="caret-on"></i>APPLICANT
                            </h4>
                        </div>
                        <div class="bs-example bs-example-tabs" id="test_up">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form->field($appModel, 'first_name', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label("First Name<b>*</b>", ["for" => "disabledTextInput"]); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($appModel, 'mid_name', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label("MI", ["for" => "disabledTextInput"]); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($appModel, 'last_name', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label("Last Name<b>*</b>", ["for" => "disabledTextInput"]); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $form->field($appModel, 'address_street', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label('Street Address (PO Box is not acceptable)<b>*</b>') ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form->field($appModel, 'address_city', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label('City<b>*</b>') ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($appModel, 'address_state', ['options' => ['class' => '-form-group-theme form-group']])->label('State<b>*</b>')->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(State::find()->all(), 'abbreviation', 'abbreviation'),
                                            'disabled' => $isRead,
                                            'options' => ['placeholder' => 'Select a state ...', 'class' => 'form-control -form-control-theme'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                            ],
                                        ]) ?>
                                    </div>
                                    <div class="col-md-4">

                                        <?= $form->field($appModel, 'address_zip_code', ['options' => ['class' => '-form-group-theme form-group']])->widget(MaskedInput::className(), ['mask' => '99999', 'options' => ["class" => 'form-control -form-control-theme', 'disabled' => $isRead]])->label('Zip Code<b>*</b>'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 -prompt-margin-bottom-32">
                                        <div class="prompt">
                                            <i class="glyphicon ico-info"></i>
                                            <p>Supernova Lending may contact you at any phone number you provide. When
                                                you give us your cell phone number, we have your consent to contact you
                                                at that number about your BriteLine Line of Credit. It may include
                                                contact from companies working on our behalf to service your Line of
                                                Credit. Message and data rates may apply. You may contact us at any time
                                                to change these preferences.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form->field($appModel, 'cell_phone', ['options' => ['class' => '-form-group-theme form-group']])->widget(MaskedInput::className(), ['mask' => '999-999-9999', 'options' => ["class" => 'form-control -form-control-theme', 'disabled' => $isRead]])->label('Cell Phone<b>*</b>') ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($appModel, 'work_phone', ['options' => ['class' => '-form-group-theme form-group']])->widget(MaskedInput::className(), ['mask' => '999-999-9999', 'options' => ["class" => 'form-control -form-control-theme', 'disabled' => $isRead]])->label('Work Phone') ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($appModel, 'home_phone', ['options' => ['class' => '-form-group-theme form-group']])->widget(MaskedInput::className(), ['mask' => '999-999-9999', 'options' => ["class" => 'form-control -form-control-theme', 'disabled' => $isRead]])->label('Home Phone') ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $form->field($appModel, 'email_address', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label('Email Address<b>*</b>') ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($appModel, 'social_security_number', ['options' => ['class' => '-form-group-theme form-group']])->widget(MaskedInput::className(), ['mask' => '999-99-9999', 'options' => ["class" => 'form-control -form-control-theme', 'disabled' => $isRead]])->label('Social Security Number<b>*</b>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($appModel, 'date_of_birth', ['options' => ['class' => '-form-group-theme form-group']])->label('Date of Birth<b>*</b>')->widget(DatePicker::className(), [
                                            'type' => DatePicker::TYPE_INPUT,
                                            'disabled' => $isRead,
                                            'options' => ['value' => $appModel->date_of_birth ? date('m/d/Y', $appModel->date_of_birth) : '', 'class' => 'form-control -form-control-theme', 'autocomplete' => 'off', 'placeholder' => 'mm/dd/yyyy'],
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                                'defaultViewDate' => [
                                                    'year' => 1960,
                                                ],
                                            ],
                                        ]) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-tible -form-special-title col-md-12">
                                        <?php if ($isRead) { ?>
                                            <?= $form->field($appModel, 'is_united_states_citizen', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Are you a United States citizen?<b>*</b>')->radioList(DictApplicant::$americanCitizen, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value, "disabled" => "disabled"]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-6']);
                                            }]) ?>
                                        <?php } else { ?>
                                            <?= $form->field($appModel, 'is_united_states_citizen', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Are you a United States citizen?<b>*</b>')->radioList(DictApplicant::$americanCitizen, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-6']);
                                            }]) ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-tible -form-special-title col-md-12">
                                        <?php if ($isRead) { ?>
                                            <?= $form->field($appModel, 'identification_type', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Please select an identification type:<b>*</b>')->radioList(DictApplicant::$identificationType, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value, "disabled" => "disabled"]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return $label = Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]); ?>
                                        <?php } else { ?>
                                            <?= $form->field($appModel, 'identification_type', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Please select an identification type:<b>*</b>')->radioList(DictApplicant::$identificationType, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return $label = Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]); ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form->field($appModel, 'identification_number', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label("License Number") ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($appModel, 'identification_state', ['options' => ['class' => '-form-group-theme form-group']])->label('Issued State')->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(State::find()->all(), 'abbreviation', 'abbreviation'),
                                            'disabled' => $isRead,
                                            'options' => ['placeholder' => 'Select a state ...', 'class' => 'form-control -form-control-theme'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                            ],
                                        ]) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($appModel, 'identification_expiration_date', ['options' => ['class' => '-form-group-theme form-group']])->label("Expiration Date")->widget(DatePicker::className(), [
                                            'type' => DatePicker::TYPE_INPUT,
                                            'disabled' => $isRead,
                                            'options' => [
                                                'value' => $appModel->identification_expiration_date ? date('m/d/Y', $appModel->identification_expiration_date) : '', 'class' => 'form-control -form-control-theme', 'autocomplete' => 'off', 'placeholder' => 'mm/dd/yyyy'
                                            ],
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                            ],
                                        ]) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-tible -form-special-title col-md-12">
                                        <?php if ($isRead) { ?>
                                            <?= $form->field($appModel, 'marital_status', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Please select your marital status:<b>*</b>')->radioList(DictApplicant::$maritalStatus, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value, "disabled" => "disabled"]);
                                                $label = Html::label($radio . $label, null, ['style' => 'margin-bottom:0px']);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]) ?>
                                        <?php } else { ?>
                                            <?= $form->field($appModel, 'marital_status', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Please select your marital status:<b>*</b>')->radioList(DictApplicant::$maritalStatus, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value]);
                                                $label = Html::label($radio . $label, null, ['style' => 'margin-bottom:0px']);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]) ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($appModel, 'occupation', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label('Occupation<b>*</b>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($appModel, 'employer', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label('Employer<b>*</b>') ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-tible -form-special-title col-md-12">
                                        <?php if ($isRead) { ?>
                                            <?= $form->field($appModel, 'total_annual_income', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Total Annual Income <i class="glyphicon ico-01" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Alimony, child support, or separate maintenance income need not be provided if you do not wish to have it considered as a basis for repaying this obligation." data-original-title="" title=""></i><b>*</b>')->radioList(DictApplicant::$totalAnnualIncome, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value, "disabled" => "disabled"]);
                                                $label = Html::label($radio . $label, null, ['style' => 'margin-bottom:0px']);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]) ?>
                                        <?php } else { ?>
                                            <?= $form->field($appModel, 'total_annual_income', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Total Annual Income <i class="glyphicon ico-01" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Alimony, child support, or separate maintenance income need not be provided if you do not wish to have it considered as a basis for repaying this obligation." data-original-title="" title=""></i><b>*</b>')->radioList(DictApplicant::$totalAnnualIncome, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value]);
                                                $label = Html::label($radio . $label, null, ['style' => 'margin-bottom:0px']);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]) ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php if ($coAppModel): ?>
                                <?php if (!$isRead): ?>
                                    <div class="row">
                                        <a id="ajax-submit" class="btn btn-primary -btn-primary-theme"
                                           style="margin-left:10px">SAVE</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- CO-APPLICANT -->

                    </div>
                </div>


                <?php if ($coAppModel): ?>

                <!--<div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <p>Better check yourself, you're not looking too good.</p>
                    <br>
                    <div class="btn btn-default -btn-default-theme">Copy Infomation</div>&nbsp;&nbsp;<div data-dismiss='alert' aria-lable='Close' class="btn btn-default -btn-default-theme" style="border: none">Cancel</div>
                </div>-->

                <div class="content-form-box">
                    <div class="form-body">
                        <div class="form-header-tp">
                            <h4>Co-applicant</h4>
                        </div>
                        <div class="bs-example bs-example-tabs">
                            <div class="tab-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'first_name', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label("First Name<b>*</b>", ["for" => "disabledTextInput"]); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'mid_name', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label("MI", ["for" => "disabledTextInput"]); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'last_name', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label("Last Name<b>*</b>", ["for" => "disabledTextInput"]); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $form->field($coAppModel, 'address_street', [
                                            'options' => ['class' => '-form-group-theme form-group'],
                                        ])->label('Street Address (PO Box is not acceptable)<b>*</b>')->widget(Typeahead::classname(), [
                                            'options' => ['class' => 'form-control -form-control-theme', 'disabled' => $isRead],
                                            'pluginOptions' => ['hint' => false, 'highlight' => true],
                                            'dataset' => [
                                                [
                                                    'async' => false,
                                                    'source' => new JsExpression('function(query, sync, async){ var value = document.getElementById("appapplicant-primary-address_street").value; if(value.indexOf(query) !== -1){ sync([value]); } else { sync([]); } }'),
                                                    'local' => [null],
                                                    'limit' => 1,
                                                ]
                                            ]
                                        ]) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'address_city', ['options' => ['class' => '-form-group-theme form-group']])->label('City<b>*</b>')->widget(Typeahead::classname(), [
                                            'options' => ['class' => 'form-control -form-control-theme', 'disabled' => $isRead],
                                            'pluginOptions' => ['hint' => false, 'highlight' => true],
                                            'dataset' => [
                                                [
                                                    'async' => false,
                                                    'source' => new JsExpression('function(query, sync, async){ var value = document.getElementById("appapplicant-primary-address_city").value; if(value.indexOf(query) !== -1){ sync([value]); } else { sync([]); } }'),
                                                    'local' => [null],
                                                    'limit' => 1,
                                                ]
                                            ]
                                        ]) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'address_state', ['options' => ['class' => '-form-group-theme form-group']])->label('State<b>*</b>')->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(State::find()->all(), 'abbreviation', 'abbreviation'),
                                            'disabled' => $isRead,
                                            'options' => ['placeholder' => 'Select a state ...', 'class' => 'form-control -form-control-theme'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                            ],
                                        ]) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'address_zip_code', ['options' => ['class' => '-form-group-theme form-group']])->widget(MaskedInput::className(), ['mask' => '99999', 'options' => ["class" => 'form-control -form-control-theme', 'disabled' => $isRead]])->label('Zip Code<b>*</b>')->widget(Typeahead::classname(), [
                                            'options' => ['class' => 'form-control -form-control-theme', 'disabled' => $isRead],
                                            'pluginOptions' => ['hint' => false, 'highlight' => true],
                                            'dataset' => [
                                                [
                                                    'async' => false,
                                                    'source' => new JsExpression('function(query, sync, async){ var value = document.getElementById("appapplicant-primary-address_zip_code").value; if(value.indexOf(query.replace(/[_]+/g,"")) !== -1){ sync([value]); } else { sync([]); }
                                            }'),
                                                    'local' => [null],
                                                    'limit' => 1,
                                                ]
                                            ]
                                        ]) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 -prompt-margin-bottom-32">
                                        <div class="prompt">
                                            <i class="glyphicon ico-info"></i>
                                            <p>Supernova Lending may contact you at any phone number you provide. When
                                                you give us your cell phone number, we have your consent to contact you
                                                at that number about your BriteLine Line of Credit. It may include
                                                contact from companies working on our behalf to service your Line of
                                                Credit. Message and data rates may apply. You may contact us at any time
                                                to change these preferences.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'cell_phone', ['options' => ['class' => '-form-group-theme form-group']])->widget(MaskedInput::className(), ['mask' => '999-999-9999', 'options' => ["class" => 'form-control -form-control-theme', 'disabled' => $isRead]])->label('Cell Phone<b>*</b>') ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'work_phone', ['options' => ['class' => '-form-group-theme form-group']])->widget(MaskedInput::className(), ['mask' => '999-999-9999', 'options' => ["class" => 'form-control -form-control-theme', 'disabled' => $isRead]])->label('Work Phone') ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'home_phone', ['options' => ['class' => '-form-group-theme form-group']])->widget(MaskedInput::className(), ['mask' => '999-999-9999', 'options' => ["class" => 'form-control -form-control-theme', 'disabled' => $isRead]])->label('Home Phone') ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $form->field($coAppModel, 'email_address', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label('Email Address<b>*</b>') ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($coAppModel, 'social_security_number', ['options' => ['class' => '-form-group-theme form-group']])->widget(MaskedInput::className(), ['mask' => '999-99-9999', 'options' => ["class" => 'form-control -form-control-theme', 'disabled' => $isRead]])->label('Social Security Number<b>*</b>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($coAppModel, 'date_of_birth', ['options' => ['class' => '-form-group-theme form-group']])->label('Date of Birth<b>*</b>')->widget(DatePicker::className(), [
                                            'type' => DatePicker::TYPE_INPUT,
                                            'disabled' => $isRead,
                                            'options' => ['value' => $coAppModel->date_of_birth ? date('m/d/Y', $coAppModel->date_of_birth) : '', 'class' => 'form-control -form-control-theme', 'autocomplete' => 'off', 'placeholder' => 'mm/dd/yyyy'],
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                                'defaultViewDate' => [
                                                    'year' => 1960,
                                                ],
                                            ],
                                        ]) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-tible -form-special-title col-md-12">
                                        <?php if ($isRead) { ?>
                                            <?= $form->field($coAppModel, 'is_united_states_citizen', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Are you a United States citizen?<b>*</b>')->radioList(DictApplicant::$americanCitizen, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value, "disabled" => "disabled"]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-6']);
                                            }]) ?>
                                        <?php } else { ?>
                                            <?= $form->field($coAppModel, 'is_united_states_citizen', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Are you a United States citizen?<b>*</b>')->radioList(DictApplicant::$americanCitizen, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-6']);
                                            }]) ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-tible -form-special-title col-md-12">
                                        <?php if ($isRead) { ?>
                                            <?= $form->field($coAppModel, 'identification_type', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Please select an identification type:<b>*</b>')->radioList(DictApplicant::$identificationType, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value, "disabled" => "disabled"]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]) ?>
                                        <?php } else { ?>
                                            <?= $form->field($coAppModel, 'identification_type', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Please select an identification type:<b>*</b>')->radioList(DictApplicant::$identificationType, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]) ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'identification_number', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label("License Number") ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'identification_state', ['options' => ['class' => '-form-group-theme form-group']])->label('Issued State')->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(State::find()->all(), 'abbreviation', 'abbreviation'),
                                            'disabled' => $isRead,
                                            'options' => ['placeholder' => 'Select a state ...', 'class' => 'form-control -form-control-theme'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                            ],
                                        ]) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($coAppModel, 'identification_expiration_date', ['options' => ['class' => '-form-group-theme form-group']])->label("Expiration Date")->widget(DatePicker::className(), [
                                            'type' => DatePicker::TYPE_INPUT,
                                            'disabled' => $isRead,
                                            'options' => [
                                                'value' => $coAppModel->identification_expiration_date ? date('m/d/Y', $coAppModel->identification_expiration_date) : '', 'class' => 'form-control -form-control-theme', 'autocomplete' => 'off', 'placeholder' => 'mm/dd/yyyy'
                                            ],
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                            ],
                                        ]) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-tible -form-special-title col-md-12">
                                        <?php if ($isRead) { ?>
                                            <?= $form->field($coAppModel, 'marital_status', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Please select your marital status:<b>*</b>')->radioList(DictApplicant::$maritalStatus, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value, "disabled" => "disabled"]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]) ?>
                                        <?php } else { ?>
                                            <?= $form->field($coAppModel, 'marital_status', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Please select your marital status:<b>*</b>')->radioList(DictApplicant::$maritalStatus, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]) ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($coAppModel, 'occupation', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label('Occupation<b>*</b>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($coAppModel, 'employer', ['options' => ['class' => '-form-group-theme form-group']])->textInput(['class' => 'form-control -form-control-theme'])->label('Employer<b>*</b>') ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-tible -form-special-title col-md-12">
                                        <?php if ($isRead) { ?>
                                            <?= $form->field($coAppModel, 'total_annual_income', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Total Annual Income <i class="glyphicon ico-01" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Alimony, child support, or separate maintenance income need not be provided if you do not wish to have it considered as a basis for repaying this obligation." data-original-title="" title=""></i><b>*</b>')->radioList(DictApplicant::$totalAnnualIncome, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value, "disabled" => "disabled"]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]) ?>
                                        <?php } else { ?>
                                            <?= $form->field($coAppModel, 'total_annual_income', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Total Annual Income <i class="glyphicon ico-01" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Alimony, child support, or separate maintenance income need not be provided if you do not wish to have it considered as a basis for repaying this obligation." data-original-title="" title=""></i><b>*</b>')->radioList(DictApplicant::$totalAnnualIncome, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                                $radio = Html::radio($name, $checked, ['value' => $value]);
                                                $label = Html::label($radio . $label);
                                                $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                                return Html::tag('div', $label, ['class' => 'col-md-4']);
                                            }]) ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
                    <div class="form-footer form-button-box-shadow">
                        <?php if ($isRead): ?>
                            <a class="btn btn-primary -btn-primary-theme"
                               href="<?= Url::to(['financial-info', 'id_application' => $this->context->actionParams['id_application']]) ?>">NEXT<i
                                    class="glyphicon ico-left-arrow"></i></a>
                        <?php else: ?>
                            <button type="submit" id="save_and_next" class="btn btn-primary -btn-primary-theme">SAVE AND
                                NEXT<i class="glyphicon ico-left-arrow"></i></button>
                        <?php endif; ?>
                    </div>
                </div>

                <?php ActiveForm::end() ?>
            </main>
            <aside class="col-md-3 sidebar">
                <?= AppFormStatusBar::widget(['id_application' => $this->context->actionParams['id_application']]); ?>
                <div class="mt23">
                    <?= $this->render('../help/_help', []); ?>
                </div>
            </aside>
        </div>
    </div>
</section>
<?php $this->beginBlock('js') ?>
<script>
    $(function () {
        $('label[for="appapplicant-primary-identification_number"]').after("<b>*</b>");
        $('label[for="appapplicant-primary-identification_state"]').after("<b>*</b>");
        $('label[for="appapplicant-primary-identification_expiration_date"]').after("<b>*</b>");
        <?php if($coAppModel):?>
        $('label[for="appapplicant-co-identification_number"]').after("<b>*</b>");
        $('label[for="appapplicant-co-identification_state"]').after("<b>*</b>");
        $('label[for="appapplicant-co-identification_expiration_date"]').after("<b>*</b>");
        <?php endif;?>
        $(':radio').each(function (index) {

            if ($(this).attr('name') == 'AppApplicant-primary[identification_type]' && $(this).prop('checked') == true) {

                if ($(this).val() == 1) {
                    $('label[for="appapplicant-primary-identification_number"]').text("License Number");
                    $('label[for="appapplicant-primary-identification_state"]').text('Issued State');
                    $('.field-appapplicant-primary-identification_state').parent().parent().show();
                    $('label[for="appapplicant-primary-identification_expiration_date"]').text('Expiration Date');
                }
                if ($(this).val() == 2) {
                    $('label[for="appapplicant-primary-identification_number"]').text('Passport ID');
                    $('.field-appapplicant-primary-identification_state').parent().hide();
                    $('label[for="appapplicant-primary-identification_expiration_date"]').text('Expiration Date');
                }
                if ($(this).val() == 3) {
                    $('label[for="appapplicant-primary-identification_number"]').text('ID Number');
                    $('label[for="appapplicant-primary-identification_state"]').text('Issued State');
                    $('.field-appapplicant-primary-identification_state').parent().parent().show();
                    $('label[for="appapplicant-primary-identification_expiration_date"]').text('Expiration Date');
                }
            }
            if ($(this).attr('name') == 'AppApplicant-co[identification_type]' && $(this).prop('checked') == true) {
                if ($(this).val() == 1) {
                    $('label[for="appapplicant-co-identification_number"]').text("License Number");
                    $('label[for="appapplicant-co-identification_state"]').text('Issued State');
                    $('.field-appapplicant-co-identification_state').parent().parent().show();
                    $('label[for="appapplicant-co-identification_expiration_date"]').text('Expiration Date');
                }
                if ($(this).val() == 2) {
                    $('label[for="appapplicant-co-identification_number"]').text('Passport ID');
                    $('.field-appapplicant-co-identification_state').parent().hide();
                    $('label[for="appapplicant-co-identification_expiration_date"]').text('Expiration Date');
                }
                if ($(this).val() == 3) {
                    $('label[for="appapplicant-co-identification_number"]').text('ID Number');
                    $('label[for="appapplicant-co-identification_state"]').text('Issued State');
                    $('.field-appapplicant-co-identification_state').parent().parent().show();
                    $('label[for="appapplicant-co-identification_expiration_date"]').text('Expiration Date');
                }
            }
        });
        $(':radio').click(function () {
            if ($(this).attr('name') == 'AppApplicant-primary[identification_type]') {

                if ($(this).val() == 1) {
                    $('label[for="appapplicant-primary-identification_number"]').text("License Number");
                    $('label[for="appapplicant-primary-identification_state"]').text('Issued State');
                    $('.field-appapplicant-primary-identification_state').parent().show();
                    $('label[for="appapplicant-primary-identification_expiration_date"]').text('Expiration Date');
                }
                if ($(this).val() == 2) {
                    $('label[for="appapplicant-primary-identification_number"]').text('Passport ID');
                    $('.field-appapplicant-primary-identification_state').parent().hide();
                    $('label[for="appapplicant-primary-identification_expiration_date"]').text('Expiration Date');
                    $('#appapplicant-primary-identification_state').val('');
                    $('#appapplicant-primary-identification_state').trigger('change');
                }
                if ($(this).val() == 3) {
                    $('label[for="appapplicant-primary-identification_number"]').text('ID Number');
                    $('label[for="appapplicant-primary-identification_state"]').text('Issued State');
                    $('.field-appapplicant-primary-identification_state').parent().show();
                    $('label[for="appapplicant-primary-identification_expiration_date"]').text('Expiration Date');
                }
            }
            if ($(this).attr('name') == 'AppApplicant-co[identification_type]') {
                if ($(this).val() == 1) {
                    $('label[for="appapplicant-co-identification_number"]').text("License Number");
                    $('label[for="appapplicant-co-identification_state"]').text('Issued State');
                    $('.field-appapplicant-co-identification_state').parent().show();
                    $('label[for="appapplicant-co-identification_expiration_date"]').text('Expiration Date');
                }
                if ($(this).val() == 2) {
                    $('label[for="appapplicant-co-identification_number"]').text('Passport ID');
                    $('.field-appapplicant-co-identification_state').parent().hide();
                    $('label[for="appapplicant-co-identification_expiration_date"]').text('Expiration Date');
                    $('#appapplicant-primary-identification_state').val('');
                    $('#appapplicant-primary-identification_state').trigger('change');
                }
                if ($(this).val() == 3) {
                    $('label[for="appapplicant-co-identification_number"]').text('ID Number');
                    $('label[for="appapplicant-co-identification_state"]').text('Issued State');
                    $('.field-appapplicant-co-identification_state').parent().show();
                    $('label[for="appapplicant-co-identification_expiration_date"]').text('Expiration Date');
                }
            }

        });


    });


    $("#p_up").click(function () {
        $("#test_up").slideUp("slow");
    })

    $("#app_licant").click(function () {
        if ($("#test_up").css("display") == "none") {
            $("#test_up").slideDown("slow");
            $("#app_licant i").removeClass("caret-to");
            $("#app_licant i").addClass("caret-on");
        } else {
            $("#test_up").slideUp("slow");
            $("#app_licant i").removeClass("caret-on");
            $("#app_licant i").addClass("caret-to");
        }
    })
</script>
<?php $this->endBlock() ?>
