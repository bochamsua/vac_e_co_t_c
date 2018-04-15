<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject - curriculum controller
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
require_once ("BS/Traininglist/controllers/Adminhtml/Traininglist/CurriculumController.php");
class BS_Subject_Adminhtml_Subject_Subject_Traininglist_CurriculumController extends BS_Traininglist_Adminhtml_Traininglist_CurriculumController
{
    /**
     * construct
     *
     * @access protected
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('BS_Subject');
    }

    /**
     * subjects in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function subjectsAction()
    {
        $this->_initCurriculum();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculum.edit.tab.subject');
        $this->renderLayout();
    }

    /**
     * subjects grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function subjectsGridAction()
    {
        $this->_initCurriculum();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculum.edit.tab.subject');
        $this->renderLayout();
    }
}
