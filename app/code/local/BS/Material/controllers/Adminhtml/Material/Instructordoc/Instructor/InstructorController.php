<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document - instructor controller
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
require_once ("BS/Instructor/controllers/Adminhtml/Instructor/InstructorController.php");
class BS_Material_Adminhtml_Material_Instructordoc_Instructor_InstructorController extends BS_Instructor_Adminhtml_Instructor_InstructorController
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
        $this->setUsedModuleName('BS_Material');
    }

    /**
     * instructor docs in the bs_instructor page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function instructordocsAction()
    {
        $this->_initInstructor();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructor.edit.tab.instructordoc')
            ->setInstructorInstructordocs($this->getRequest()->getPost('instructor_instructordocs', null));
        $this->renderLayout();
    }

    /**
     * instructor docs grid in the bs_instructor page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function instructordocsGridAction()
    {
        $this->_initInstructor();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructor.edit.tab.instructordoc')
            ->setInstructorInstructordocs($this->getRequest()->getPost('instructor_instructordocs', null));
        $this->renderLayout();
    }
}
