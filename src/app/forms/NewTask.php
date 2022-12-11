<?php
namespace app\forms;

use std, gui, framework, app;


class NewTask extends AbstractForm
{



    /**
     * @event addTaskBtn.action 
     */
    function doAddTaskBtnAction(UXEvent $e = null)
    {    
        $sectionName = time();
        $this->cfg->load(); // Обновляем конфиг
        $currentTemplate = $this->cfg->get("selectedTemplate"); // Загружаем номер выбранного шаблона из ini-файла
        
        $name = $this->nameEdit->text;
        $time = strval($this->hField->value).",".strval($this->mField->value);
        
        $this->ini1->load();
        $this->ini2->load();
        $this->ini3->load();
        $this->ini4->load(); // Обновляем ini-файлы
        
        switch ($currentTemplate) { // Создаём задачу в выбранном шаблоне
            case 1:
                $this->ini1->set("task", $name, $sectionName);
                $this->ini1->set("time", $time, $sectionName);
                break;
            case 2:
                $this->ini2->set("task", $name, $sectionName);
                $this->ini2->set("time", $time, $sectionName);
                break;
            case 3:
                $this->ini3->set("task", $name, $sectionName);
                $this->ini3->set("time", $time, $sectionName);
                break;
            case 4:
                $this->ini4->set("task", $name, $sectionName);
                $this->ini4->set("time", $time, $sectionName);
                break;
        }
        
        app()->hideForm('NewTask');
    }

    /**
     * @event cancelBtn.action 
     */
    function doCancelBtnAction(UXEvent $e = null)
    {    
        app()->hideForm('NewTask');
    }

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        $this->nameEdit->text = "";
        $this->hField->value = 0;
        $this->mField->value = 0;
        
        global $file; // Достаём глобальную переменную $file
        global $tasks; // Достаём глобальную переменную $tasks
        
        global $lastHours; // Делаем переменную $lastHours глобальной, она понадобится для проверки при изменении кол-ва часов
        
        $lastHours = intval(explode(',', $file->get('time', end($tasks)))[0]); // Достаём кол-во часов из последнего задания в ini-файле
        $lastMins = intval(explode(',', $file->get('time', end($tasks)))[1]); // Достаём кол-во минут из последнего задания в ini-файле
        
        if ($lastMins < 59) { // Если минут меньше 59, то мы просто прибавим к минимальному кол-ву минут 1
            $this->hField->min = $lastHours;
            $this->mField->min = $lastMins + 1;
        } else { // Иначе, мы установим минимальное кол-во минут на 0, а к минимальному кол-ву часов прибавим 1
            $this->hField->min = $lastHours + 1;
            $this->mField->min = 0;
        }
        if (count($tasks) == 0) {$this->mField->min = 0; $this->mField->enabled = false; $this->hField->enabled = false; } // А если это окажется первым заданием, которое мы создаём, нельзя будет изменить время (всегда 0:00)
        else { $this->mField->enabled = true; $this->hField->enabled = true; } // Иначе, можно
    }

    /**
     * @event hField.click 
     */
    function doHFieldClick(UXMouseEvent $e = null)
    {    
        global $lastHours; // Достаём глобальную переменную $lastHours
        
        if ($this->hField->value > $lastHours) {$this->mField->min = 0;} // Если часов больше, чем в предыдущем задании, мы установим минимальное кол-во минут на 1
        else { $this->mField->min = 1; } // Иначе на 1
    }




}
