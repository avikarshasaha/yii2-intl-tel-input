<?php
namespace avikarsha\intltelinput;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use yii\base\InvalidConfigException;
use Yii;

class IntlTelInput extends InputWidget 
{
    /**
     * @var array the options for the Bootstrap Multiselect JS plugin.
     * Please refer to the Bootstrap Multiselect plugin Web page for possible options.
     * @see http://davidstutz.github.io/bootstrap-multiselect/#options
     */
    public $clientOptions = [];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeInput('tel', $this->model, $this->attribute, $this->options);
        } else {
            echo Html::input('tel', $this->name, $this->value, $this->options);
        }
        $this->registerPlugin();
    }

    /**
     * Registers MultiSelect Bootstrap plugin and the related events
     */
    protected function registerPlugin()
    {
        $view = $this->getView();

        IntlTelInputAsset::register($view);

        $id = $this->options['id'];

        $jsOptions = $this->clientOptions !== false && !empty($this->clientOptions)
            ? Json::encode($this->clientOptions)
            : '';

        $js = "jQuery('#$id').intlTelInput($jsOptions);";
        $value = isset($this->options['value']) ? $this->options['value'] : Html::getAttributeValue($this->model, $this->attribute);
        $isd =  isset($this->options['isd']) ? $this->options['isd'] : '';
        if($value && $isd) {
            $js .= "jQuery('#$id').intlTelInput('setNumber','+ $isd $value');";
        }
        
        $view->registerJs($js, \yii\web\View::POS_LOAD);
    }
}
