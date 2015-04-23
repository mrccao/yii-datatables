<?php

class DataTables extends CApplicationComponent
{

    protected $_assetsUrl;

    public $extensions = array(
        'AutoFill' => false,
        'ColReorder' => false,
        'ColVis' => false,
        'FixedColumns' => false,
        'FixedHeader' => false,
        'KeyTable' => false,
        'Responsive' => false,
        'Scroller' => false,
        'TableTools' => false
    );

    public function init()
    {
        $this->_assetsUrl = Yii::app()->assetManager->publish(__DIR__ . '/assets');
        if (Yii::getPathOfAlias('datatables') === false) {
            Yii::setPathOfAlias('datatables', realpath(dirname(__FILE__)));
        }
        $package = array(
            'baseUrl' => $this->_assetsUrl,
            'js' => array(
                YII_DEBUG ? "media/js/jquery.dataTables.js" : "media/js/jquery.dataTables.min.js"
            ),
            'css' => array(
                YII_DEBUG ? "media/css/jquery.dataTables.css" : "media/css/jquery.dataTables.min.css",
            ),
            'depends' => array(
                'jquery'
            )
        );
        if(is_array($this->extensions)) {
            foreach($this->extensions as $name => $value) {
                if($value) {
                    $min = YII_DEBUG ? '.min' : '';
                    array_push($package['js'], 'extensions/' . $name . '/js/dataTables.' . $name . $min . '.js');
                    array_push($package['css'], 'extensions/' . $name . '/css/dataTables.' . $name . $min . '.js');
                }
            }
        }
        Yii::app()->clientScript->addPackage('datatables', $package);
        Yii::app()->clientScript->addPackage('datatables.jqueryUI', array(
            'baseUrl' => $this->_assetsUrl,
            'js' => array(
                YII_DEBUG ? "plugins/integration/jqueryui/dataTables.jqueryui.js" : "media/js/dataTables.jqueryui.min.js"
            ),
            'css' => array(
                "plugins/integration/jqueryui/dataTables.jqueryui.css",
            ),
        ));
        Yii::app()->clientScript->addPackage('datatables.foundation', array(
            'baseUrl' => $this->_assetsUrl,
            'js' => array(
                YII_DEBUG ? "plugins/integration/foundation/dataTables.foundation.js" : "media/js/dataTables.foundation.min.js"
            ),
            'css' => array(
                "plugins/integration/foundation/dataTables.foundation.css",
            ),
        ));
    }
}