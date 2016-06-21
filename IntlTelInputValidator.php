<?php
namespace avikarsha\intltelinput;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use yii\helpers\Json;
use yii\validators\Validator;

class IntlTelInputValidator extends Validator
{
    public function init()
    {
        if (!$this->message) {
            $this->message = \Yii::t('yii', 'The format of {attribute} is invalid.');
        }
        parent::init();
    }
    /**
     * @param mixed $value
     * @return array|null
     */
    protected function validateValue($value)
    {
        $valid = false;
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneProto = $phoneUtil->parse($value, null);
            if ($this->region !== null) {
                if (is_array($this->region)) {
                    foreach ($this->region as $region) {
                        if ($phoneUtil->isValidNumberForRegion($phoneProto, $region)) {
                            $valid = true;
                            break;
                        }
                    }
                } else {
                    if ($phoneUtil->isValidNumberForRegion($phoneProto, $this->region)) {
                        $valid = true;
                    }
                }
            } else {
                if ($phoneUtil->isValidNumber($phoneProto)) {
                    $valid = true;
                }
            }
        } catch (NumberParseException $e) {
        }
        return $valid ? null : [$this->message, []];

    }

    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $options = Json::htmlEncode([
            'message' => \Yii::$app->getI18n()->format($this->message, [
                'attribute' => $model->getAttributeLabel($attribute),
            ], \Yii::$app->language),
        ]);
        return <<<JS
        var options = $options, telInput = $(attribute.input);
        console.log("validator", telInput);
        if($.trim(telInput.val())){
            if(!telInput.intlTelInput("isValidNumber")){
                messages.push(options.message);
            }
        }
JS;
    }
}
