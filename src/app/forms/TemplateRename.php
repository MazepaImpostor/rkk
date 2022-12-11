<?php
namespace app\forms;

use std, gui, framework, app;


class TemplateRename extends AbstractForm
{



    /**
     * @event renameBtn.action 
     */
    function doRenameBtnAction(UXEvent $e = null)
    {    
        global $renamingTemplate;
        
        $this->cfg->set('templateName'.strval($renamingTemplate), $this->nameEdit->text); // Устанавливаем новое имя для шаблона в ini-файл
        
        app()->hideForm('TemplateRename');
    }

    /**
     * @event cancelBtn.action 
     */
    function doCancelBtnAction(UXEvent $e = null)
    {    
        app()->hideForm('TemplateRename');
    }

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        $this->nameEdit->text = "";
    }



}
