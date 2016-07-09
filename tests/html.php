                                <div class="row">
                                    <div class="form-tible -form-special-title col-md-12">
                                        <?php if ($isRead) { ?>
                                        <?= $form->field($appModel, 'is_united_states_citizen', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Are you a United States citizen?<b>*</b>')->radioList(DictApplicant::$americanCitizen, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                        $radio = Html::radio($name, $checked, ['value' => $value, "disabled" => "disabled"]);
                                        $label = Html::label($radio . $label);
                                        $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                        return Html::tag('div', $label, ['class' => 'col-md-6']);
                                        }]
                                        )?>
                                        <?php } else { ?>
                                        <?= $form->field($appModel, 'is_united_states_citizen', ["options" => ["class" => "form-group -form-group-fix-margin"]])->label('Are you a United States citizen?<b>*</b>')->radioList(DictApplicant::$americanCitizen, ['class' => 'row from-radio-wrap', 'item' => function ($index, $label, $name, $checked, $value) {
                                        $radio = Html::radio($name, $checked, ['value' => $value]);
                                        $label = Html::label($radio . $label);
                                        $label = Html::tag('div', $label, ['class' => 'radio -radio-theme -radio-fix-margin']);
                                        return Html::tag('div', $label, ['class' => 'col-md-6']);
                                        }])?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <span><?php $name = 'jin'; ?></span>
