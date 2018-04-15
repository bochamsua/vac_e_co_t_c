<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Item front contrller
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_KstitemController extends Mage_Core_Controller_Front_Action
{

    /**
      * default action
      *
      * @access public
      * @return void
      * @author Bui Phong
      */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('bs_kst/kstitem')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('bs_kst')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'kstitems',
                    array(
                        'label' => Mage::helper('bs_kst')->__('Items'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('bs_kst/kstitem')->getKstitemsUrl());
        }
        $this->renderLayout();
    }

    /**
     * init Item
     *
     * @access protected
     * @return BS_KST_Model_Kstitem
     * @author Bui Phong
     */
    protected function _initKstitem()
    {
        $kstitemId   = $this->getRequest()->getParam('id', 0);
        $kstitem     = Mage::getModel('bs_kst/kstitem')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($kstitemId);
        if (!$kstitem->getId()) {
            return false;
        } elseif (!$kstitem->getStatus()) {
            return false;
        }
        return $kstitem;
    }

    /**
     * view item action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function viewAction()
    {
        $kstitem = $this->_initKstitem();
        if (!$kstitem) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_kstitem', $kstitem);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('kst-kstitem kst-kstitem' . $kstitem->getId());
        }
        if (Mage::helper('bs_kst/kstitem')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('bs_kst')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'kstitems',
                    array(
                        'label' => Mage::helper('bs_kst')->__('Items'),
                        'link'  => Mage::helper('bs_kst/kstitem')->getKstitemsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'kstitem',
                    array(
                        'label' => $kstitem->getName(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $kstitem->getKstitemUrl());
        }
        $this->renderLayout();
    }
}
