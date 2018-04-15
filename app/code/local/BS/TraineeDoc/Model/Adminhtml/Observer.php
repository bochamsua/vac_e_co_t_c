<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param BS_Trainee_Model_Trainee $trainee
     * @return bool
     * @author Bui Phong
     */
    protected function _canAddTab($trainee)
    {
        if ($trainee->getId()) {
            return true;
        }
        if (!$trainee->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    /**
     * add the trainee document tab to trainees
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_TraineeDoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addTraineeTraineedocBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $trainee = Mage::registry('current_trainee');
        if ($block instanceof BS_Trainee_Block_Trainee_Trainee_Edit_Tabs && $this->_canAddTab($trainee)) {
            $block->addTab(
                'traineedocs',
                array(
                    'label' => Mage::helper('bs_traineedoc')->__('Trainee Document'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/traineedoc_traineedoc_trainee_trainee/traineedocs',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save trainee document - trainee relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_TraineeDoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveTraineeTraineedocData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('traineedocs', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $trainee = Mage::registry('current_trainee');
            $traineedocTrainee = Mage::getResourceSingleton('bs_traineedoc/traineedoc_trainee')
                ->saveTraineeRelation($trainee, $post);
        }
        return $this;
    }}
