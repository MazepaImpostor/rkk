<?php
namespace app\forms;

use std, gui, framework, app;


class MainForm extends AbstractForm
{

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        $chart = new UXChart(); // Создаём новый объект используя Chart API. http://canvasjs.com
        
        global $dataPoints; // Достаём глобальную переменную $dataPoints
        /*$dataPoints = array( 
            array("y"=>45, "indexLabel"=>"Chrome"),
            array("y"=>27, "indexLabel"=>"Firefox"),
            array("y"=>33, "indexLabel"=>"IE"),
            array("y"=>6, "indexLabel"=>"Safari"),
            array("y"=>13, "indexLabel"=>"Edge"),
            array("y"=>3, "indexLabel"=>"Others")
        );*/
        
        $this->diagramPanel->add($chart); // Добавляем диаграмму на панель
        
        $chart->fit(); // Растягиваем диаграмму на всю панель
        
        $chart->loadJsonOptions('{
     data: [
        {
            type: "doughnut",
            indexLabel: "{symbol} - {y}",
            radius: "90%",
            innerRadius: "45%",
            yValueFormatString: "###0 минут",
            showInLegend: true,
            legendText: "{indexLabel}",
            dataPoints: '.json_encode($dataPoints, JSON_NUMERIC_CHECK).'
        }
        ]
   }'); // Настраиваем диаграмму
    }

    /**
     * @event planBtn.action 
     */
    function doPlanBtnAction(UXEvent $e = null)
    {    
        $this->loadForm('PlanForm', true, true);
    }

    /**
     * @event templatesBtn.action 
     */
    function doTemplatesBtnAction(UXEvent $e = null)
    {    
        $this->loadForm('TemplatesForm', true, true);
    }

}
