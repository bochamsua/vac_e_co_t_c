<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author Bui Phong
     */
    protected function _canAddTab($docwise)
    {
        if ($docwise->getId()) {
            return true;
        }

        return false;
    }

    /**
     * add the exam tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Docwise_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addDocwisementExamBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $docwisement = Mage::registry('current_docwisement');
        if ($block instanceof BS_Docwise_Block_Adminhtml_Docwisement_Edit_Tabs && $this->_canAddTab($docwisement)) {
            $block->addTab(
                'exams',
                array(
                    'label' => Mage::helper('bs_docwise')->__('Exams'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/docwise_exam_docwisement/exams',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save exam - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Docwise_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveDocwisementExamData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('exams', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $docwisement = Mage::registry('current_docwisement');
            $examDocwisement = Mage::getResourceSingleton('bs_docwise/exam_docwisement')
                ->saveDocwisementRelation($docwisement, $post);
        }
        return $this;
    }}
