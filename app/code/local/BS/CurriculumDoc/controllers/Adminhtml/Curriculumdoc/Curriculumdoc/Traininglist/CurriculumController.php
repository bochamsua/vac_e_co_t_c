<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Curriculum Document - curriculum controller
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
require_once("BS/Traininglist/controllers/Adminhtml/Traininglist/CurriculumController.php");
class BS_CurriculumDoc_Adminhtml_Curriculumdoc_Curriculumdoc_Traininglist_CurriculumController extends BS_Traininglist_Adminhtml_Traininglist_CurriculumController
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
        $this->setUsedModuleName('BS_CurriculumDoc');
    }

    /**
     * curriculum docs in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumdocsAction()
    {
        $this->_initCurriculum();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculum.edit.tab.curriculumdoc')
            ->setCurriculumCurriculumdocs($this->getRequest()->getPost('curriculum_curriculumdocs', null));
        $this->renderLayout();
    }

    /**
     * curriculum docs grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumdocsGridAction()
    {
        $this->_initCurriculum();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculum.edit.tab.curriculumdoc')
            ->setCurriculumCurriculumdocs($this->getRequest()->getPost('curriculum_curriculumdocs', null));
        $this->renderLayout();
    }
}
