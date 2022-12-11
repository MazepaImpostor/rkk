<?php
namespace app\forms;

use std, gui, framework, app;


class TemplatesForm extends AbstractForm
{

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        $this->cfg->load(); // Обновляем ini-файл
        
        $currentTemplate = $this->cfg->get("selectedTemplate");
        
        switch ($currentTemplate) { // Загружаем названия секций ini-файла из выбранного шаблона
            case 1:
                $this->use1->color = '#5e5a8f';
                $this->use2->color = '#4e4a76';
                $this->use3->color = '#4e4a76';
                $this->use4->color = '#4e4a76';
                break;
            case 2:
                $this->use1->color = '#4e4a76';
                $this->use2->color = '#5e5a8f';
                $this->use3->color = '#4e4a76';
                $this->use4->color = '#4e4a76';
                break;
            case 3:
                $this->use1->color = '#4e4a76';
                $this->use2->color = '#4e4a76';
                $this->use3->color = '#5e5a8f';
                $this->use4->color = '#4e4a76';
                break;
            case 4:
                $this->use1->color = '#4e4a76';
                $this->use2->color = '#4e4a76';
                $this->use3->color = '#4e4a76';
                $this->use4->color = '#5e5a8f';
                break;
        }
        
        if ($this->cfg->get('templateName1') != '') { $this->use1->text = $this->cfg->get('templateName1'); } else { $this->use1->text = "Шаблон 1"; } // Проверяем, задано ли имя шаблона, если да то ставим его на кнопку, иначе ставим "Шаблон #"
        if ($this->cfg->get('templateName2') != '') { $this->use2->text = $this->cfg->get('templateName2'); } else { $this->use2->text = "Шаблон 2"; }
        if ($this->cfg->get('templateName3') != '') { $this->use3->text = $this->cfg->get('templateName3'); } else { $this->use3->text = "Шаблон 3"; }
        if ($this->cfg->get('templateName4') != '') { $this->use4->text = $this->cfg->get('templateName4'); } else { $this->use4->text = "Шаблон 4"; }
    }

    /**
     * @event planBtn.action 
     */
    function doPlanBtnAction(UXEvent $e = null)
    {    
        $this->loadForm('PlanForm', true, true);
    }

    /**
     * @event statisticBtn.action 
     */
    function doStatisticBtnAction(UXEvent $e = null)
    {    
        $this->loadForm('MainForm', true, true);
    }

    /**
     * @event use1.action 
     */
    function doUse1Action(UXEvent $e = null)
    {    
        $this->cfg->set("selectedTemplate", 1);
        $this->doShow(); // Обновляем текст на кнопках
        $this->form('PlanForm')->doShow(); // Обновляем массив для диаграммы
    }

    /**
     * @event use2.action 
     */
    function doUse2Action(UXEvent $e = null)
    {    
        $this->cfg->set("selectedTemplate", 2);
        $this->doShow();
        $this->form('PlanForm')->doShow();
    }

    /**
     * @event use3.action 
     */
    function doUse3Action(UXEvent $e = null)
    {    
        $this->cfg->set("selectedTemplate", 3);
        $this->doShow();
        $this->form('PlanForm')->doShow();
    }

    /**
     * @event use4.action 
     */
    function doUse4Action(UXEvent $e = null)
    {    
        $this->cfg->set("selectedTemplate", 4);
        $this->doShow();
        $this->form('PlanForm')->doShow();
    }

    /**
     * @event rem1.action 
     */
    function doRem1Action(UXEvent $e = null)
    {    
        if (UXDialog::confirm('Удалить шаблон?')) { // Спрашиваем подтверждение, а затем удаляем ini файл с задачами и удаляем заданное имя шаблона
            foreach ($this->ini1->sections() as $section) { $this->ini1->removeSection($section); }
            $this->cfg->remove('templateName1');
        }
    }

    /**
     * @event rem2.action 
     */
    function doRem2Action(UXEvent $e = null)
    {    
        if (UXDialog::confirm('Удалить шаблон?')) {
            foreach ($this->ini2->sections() as $section) { $this->ini2->removeSection($section); }
            $this->cfg->remove('templateName2');
        }
    }

    /**
     * @event rem3.action 
     */
    function doRem3Action(UXEvent $e = null)
    {    
        if (UXDialog::confirm('Удалить шаблон?')) {
            foreach ($this->ini3->sections() as $section) { $this->ini3->removeSection($section); }
            $this->cfg->remove('templateName3');
        }
    }

    /**
     * @event rem4.action 
     */
    function doRem4Action(UXEvent $e = null)
    {    
        if (UXDialog::confirm('Удалить шаблон?')) {
            foreach ($this->ini4->sections() as $section) { $this->ini4->removeSection($section); }
            $this->cfg->remove('templateName4');
        }
    }

    /**
     * @event edit1.action 
     */
    function doEdit1Action(UXEvent $e = null)
    {    
        global $renamingTemplate; // Задаём глобальную переменную, обозначающую номер переименовываемого шаблона
        $renamingTemplate = 1;
        app()->showFormAndWait('TemplateRename'); // Показываем окошко переименовывания и ждём его закрытия
        $this->doShow(); // Обновляем названия
    }

    /**
     * @event edit2.action 
     */
    function doEdit2Action(UXEvent $e = null)
    {    
        global $renamingTemplate;
        $renamingTemplate = 2;
        app()->showFormAndWait('TemplateRename');
        $this->doShow();
    }

    /**
     * @event edit3.action 
     */
    function doEdit3Action(UXEvent $e = null)
    {    
        global $renamingTemplate;
        $renamingTemplate = 3;
        app()->showFormAndWait('TemplateRename');
        $this->doShow();
    }

    /**
     * @event edit4.action 
     */
    function doEdit4Action(UXEvent $e = null)
    {    
        global $renamingTemplate;
        $renamingTemplate = 4;
        app()->showFormAndWait('TemplateRename');
        $this->doShow();
    }

}
