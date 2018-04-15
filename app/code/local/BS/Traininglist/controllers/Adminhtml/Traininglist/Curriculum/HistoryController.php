<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum admin attribute controller
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Adminhtml_Traininglist_Curriculum_HistoryController extends Mage_Adminhtml_Controller_Action
{


    protected function _construct()
    {
        $this->setUsedModuleName('BS_Traininglist');
    }

    /**
     * init action
     *
     * @accees protected
     * @return BS_Traininglist_Adminhtml_Curriculum_AttributeController
     * @author Bui Phong
     */
    protected function _initAction()
    {
        $this->_title(Mage::helper('bs_traininglist')->__('Training Curriculum'))
             ->_title(Mage::helper('bs_traininglist')->__('History'))
             ->_title(Mage::helper('bs_traininglist')->__('Curriculum History'));

        $this->loadLayout()
            ->_setActiveMenu('bs_traininglist/curriculum_history')
            ->_addBreadcrumb(
                Mage::helper('bs_traininglist')->__('Training Curriculum'),
                Mage::helper('bs_traininglist')->__('Training Curriculum')
            )
            ->_addBreadcrumb(
                Mage::helper('bs_traininglist')->__('Manage Training Curriculum History'),
                Mage::helper('bs_traininglist')->__('Manage Training Curriculum History')
            );
        return $this;
    }

    /**
     * default action
     *
     * @accees public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }


    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/curriculum/history');
    }
}
