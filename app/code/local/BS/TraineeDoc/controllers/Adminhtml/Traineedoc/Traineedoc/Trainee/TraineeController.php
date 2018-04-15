<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document - trainee controller
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
require_once ("BS/Trainee/controllers/Adminhtml/Trainee/TraineeController.php");
class BS_TraineeDoc_Adminhtml_Traineedoc_Traineedoc_Trainee_TraineeController extends BS_Trainee_Adminhtml_Trainee_TraineeController
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
        $this->setUsedModuleName('BS_TraineeDoc');
    }

    /**
     * trainee document in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function traineedocsAction()
    {
        $this->_initTrainee();
        $this->loadLayout();
        $this->getLayout()->getBlock('trainee.edit.tab.traineedoc')
            ->setTraineeTraineedocs($this->getRequest()->getPost('trainee_traineedocs', null));
        $this->renderLayout();
    }

    /**
     * trainee document grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function traineedocsGridAction()
    {
        $this->_initTrainee();
        $this->loadLayout();
        $this->getLayout()->getBlock('trainee.edit.tab.traineedoc')
            ->setTraineeTraineedocs($this->getRequest()->getPost('trainee_traineedocs', null));
        $this->renderLayout();
    }
}
