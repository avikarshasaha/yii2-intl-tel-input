<?php
namespace avikarsha\intltelinput;
use yii\validators\Validator;
use yii\helpers\Html;
use yii\helpers\Json;


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
        $value = $model->$attribute;

        if (!is_string($value)) {
            $this->addError($model, $attribute, $this->message);

            return;
        }
    }
    
    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view) {
        $options = Json::htmlEncode([
            'message' => \Yii::$app->getI18n()->format($this->message, [
                'attribute' => $model->getAttributeLabel($attribute)
            ], \Yii::$app->language)
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