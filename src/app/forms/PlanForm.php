<?php
namespace app\forms;

use std, gui, framework, app;


class PlanForm extends AbstractForm
{

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        $this->table->items->clear(); // Очищаем таблицу, чтобы заново её заполнить
        
        $this->cfg->load();
        $currentTemplate = $this->cfg->get("selectedTemplate"); // Загружаем номер выбранного шаблона из ini-файла
        
        $this->ini1->load();
        $this->ini2->load();
        $this->ini3->load();
        $this->ini4->load(); // Обновляем ini-файлы
        
        global $file; // Делаем переменную $file глобальной
        global $tasks; // Делаем переменную $tasks глобальной
        
        switch ($currentTemplate) { // Загружаем названия секций ini-файла из выбранного шаблона и выбираем, откуда нам брать данные для таблицы. Так же добавляем в заголовок окна название выбранного шаблона
            case 1:
                $tasks = $this->ini1->sections();
                $file = $this->ini1;
                if ($this->cfg->get('templateName1') != '') { $this->form('PlanForm')->title = "RKKPlanner - ".$this->cfg->get('templateName1');
                } else { $this->form('PlanForm')->title = "RKKPlanner - Шаблон 1"; } // Добавляем к заголовку окна название используемового шаблона
                break;
            case 2:
                $tasks = $this->ini2->sections();
                $file = $this->ini2;
                if ($this->cfg->get('templateName2') != '') { $this->form('PlanForm')->title = "RKKPlanner - ".$this->cfg->get('templateName2');
                } else { $this->form('PlanForm')->title = "RKKPlanner - Шаблон 2"; }
                break;
            case 3:
                $tasks = $this->ini3->sections();
                $file = $this->ini3;
                if ($this->cfg->get('templateName3') != '') { $this->form('PlanForm')->title = "RKKPlanner - ".$this->cfg->get('templateName3');
                } else { $this->form('PlanForm')->title = "RKKPlanner - Шаблон 3"; }
                break;
            case 4:
                $tasks = $this->ini4->sections();
                $file = $this->ini4;
                if ($this->cfg->get('templateName4') != '') { $this->form('PlanForm')->title = "RKKPlanner - ".$this->cfg->get('templateName4');
                } else { $this->form('PlanForm')->title = "RKKPlanner - Шаблон 4"; }
                break;
        }
        
        /////// Здесь мы загружаем название задания из ini-файла, форматируем время, вычисляем продолжительность и вносим всё в таблицу, а так же готовим статистику для диаграммы ///////

        global $dataPoints; // Создаём глобальню переменную $dataPoints
        $dataPoints = []; // Теперь это пустой массив, в него мы будем добавлять данные для диаграмы
        
        $i = 0;
        while ($i < count($tasks)) { // Цикл повторится столько раз, сколько секций в ini-файле
        
            if (explode(',', $file->get("time", $tasks[$i]))[1] < 10) {$fillMins = '0'; } else { $fillMins = ''; } // Добавляем ноль, если минут меньше 10 (во времени начала)
            
            $thisTask = explode(',', $file->get("time", $tasks[$i]));
            $nextTask = explode(',', $file->get("time", $tasks[$i+1])); // Разделяем время из ini-файла на часы и минуты по запятой
            
            if ($i+1 != count($tasks)) { // Узнаём, последний ли это пункт в списке
                $durHours = $nextTask[0] - $thisTask[0];
                $durMins = $nextTask[1] - $thisTask[1]; // Если нет, то вычитаем из времени последующего пункта текущее (тем самым получаем длительность)
            } else {
                $durHours = 24 - $thisTask[0];
                $durMins = 0 - $thisTask[1];  // Если да, то вычитаем из 24 часов 0 минут текущее время (тем самым получаем длительность)
            }
            if ($durMins < 0) { $durHours -= 1; $durMins = 60 + $durMins; } // Если оказалось отрицательное кол-во минут, то отнимаем 1 час и ставим кол-во минут по формуле (60 - кол-во минуты)
            
            if ($durMins < 10 and $durHours > 0) { $fillDurMins = '0'; } else { $fillDurMins = ''; } // Добавляем ноль, если минут меньше 10 и часов больше 0 ( в длительности)
            
            if ($durHours < 1) { $durHours = ''; $letterH = ''; } else { $letterH = ' ч. ';}
            $formattedDuration = $durHours.$letterH.$fillDurMins.$durMins. ' мин.'; // Форматируем результат для записи в столбик
            
            $this->table->items->add([
                'time' => explode(',', $file->get("time", $tasks[$i]))[0].' : '.$fillMins.explode(',', $file->get("time", $tasks[$i]))[1], 'name' => $file->get("task", $tasks[$i]), 'value' => $formattedDuration
            ]); // Добавляем элемент в таблицу из ini файла
            
            
            array_push($dataPoints, ["y"=>$durMins+$durHours*60, "indexLabel"=>$file->get("task", $tasks[$i])]); // Заполняем массив $dataPoints
            
            
            $i++; // Прибавляем к i единицу
        }
        
        $file->save(); // Сохраняем расписание в ini
    }

    /**
     * @event templatesBtn.action 
     */
    function doTemplatesBtnAction(UXEvent $e = null)
    {    
        $this->loadForm('TemplatesForm', true, true); // Перемещамся на вкладку "Шаблоны"
    }

    /**
     * @event statisticBtn.action 
     */
    function doStatisticBtnAction(UXEvent $e = null)
    {    
        $this->loadForm('MainForm', true, true); // Перемещамся на вкладку "Расписание"
    }

    /**
     * @event addButton.action 
     */
    function doAddButtonAction(UXEvent $e = null)
    {    
        app()->showFormAndWait('NewTask'); // Открываем окошко создания задания и прекращаем выполнение кода, пока оно не закроется
        $this->doShow(); // Вызываем функцию обновления таблицы
    }

    /**
     * @event remButton.action 
     */
    function doRemButtonAction(UXEvent $e = null)
    {    
        global $file; // Достаём глобальную переменную $file
        global $tasks; // Достаём глобальную переменную $tasks
        
        $focusedItem = $this->table->focusedIndex;
        
        if (UXDialog::confirm('Удалить задачу '.$file->get("task", $tasks[$focusedItem]).'?')) { // Спрашиваем подтверждение, а затем удаляем задачу
            $file->removeSection($tasks[$focusedItem]);
        }
        
        $this->doShow();  // Вызываем функцию обновления таблицы
    }


}
