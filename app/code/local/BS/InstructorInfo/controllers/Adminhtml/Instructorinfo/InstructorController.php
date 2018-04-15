<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule - product controller
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
require_once ("BS/Instructor/controllers/Adminhtml/Instructor/InstructorController.php");
class BS_InstructorInfo_Adminhtml_Instructorinfo_InstructorController extends BS_Instructor_Adminhtml_Instructor_InstructorController
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
        $this->setUsedModuleName('BS_Instructorinfo');
    }



    public function infosAction()
    {
        $this->_initInstructor();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructor.edit.tab.infor');
        $this->renderLayout();
    }

    public function infosGridAction()
    {
        $this->_initInstructor();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructor.edit.tab.infor');
        $this->renderLayout();
    }

    public function otherinfosAction()
    {
        $this->_initInstructor();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructor.edit.tab.otherinfor');
        $this->renderLayout();
    }

    public function otherinfosGridAction()
    {
        $this->_initInstructor();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructor.edit.tab.otherinfor');
        $this->renderLayout();
    }

}
