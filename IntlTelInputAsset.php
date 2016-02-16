<?php
namespace avikarsha\intltelinput;
use yii\web\AssetBundle;
class IntlTelInputAsset extends AssetBundle {
	public $sourcePath = '@bower/intl-tel-input'; 

    public $css = [
    	"build/css/intlTelInput.css",
    ];

    public $js = [
        "lib/libphonenumber/build/utils.js",
    	"build/js/intlTelInput.js"
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
