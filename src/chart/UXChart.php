<?php
namespace chart;

use php\gui\framework\AbstractModule;
use php\gui\UXWebView;
use php\io\ResourceStream;
use php\gui\UXApplication;
use php\lang\Thread;

class UXChart extends UXWebView
{
    const HTML_RES = 'chart/chart.html';
    public $options = [];
    

    public function __construct(){
        parent::__construct();

        $tpl = new ResourceStream(self::HTML_RES)->readFully();
        $this->engine->loadContent($tpl, 'text/html');

        $this->engine->watchState(function($self, $old, $new){
            switch($new){
                case 'FAILED':
                break;

                case 'SUCCEEDED':
                    var_dump('load');
                    $this->updateChart();
                break;
            }             
        });
    }    

    public function loadJsonOptions($data){
        $this->options = json_decode($data, true);
        $this->updateChart();
    }

    public function setOption($key, $value){
        $this->options[$key] = $value;
        $this->updateChart();
    }

    public function updateChart(){
        if($this->engine->state == 'SUCCEEDED'){
            $this->engine->callFunction('setChartOptions', [json_encode($this->options)]);
        }

        return;
        (new Thread(function(){
            UXApplication::runLater(function(){
                while($this->engine->state != 'SUCCEEDED'){
                    //wait(100);
                }

                $this->engine->callFunction('setChartOptions', [json_encode($this->options)]);
            });
        }))->start();

    }

    public function fitToWidth(){
     //   $this->anchorFlags['left'] = $this->anchorFlags['right'] = true;
        $this->leftAnchor = $this->rightAnchor = 0;
    }

    public function fitToHeight(){
       // $this->anchorFlags['top'] = $this->anchorFlags['bottom'] = true;
        $this->topAnchor = $this->bottomAnchor = 0;
    }

    public function fit(){
        $this->fitToWidth();
        $this->fitToHeight();
    }
}