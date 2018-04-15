<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Progress front contrller
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_KstprogressController extends Mage_Core_Controller_Front_Action
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
        if (Mage::helper('bs_kst/kstprogress')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('bs_kst')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'kstprogresses',
                    array(
                        'label' => Mage::helper('bs_kst')->__('Progresses'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('bs_kst/kstprogress')->getKstprogressesUrl());
        }
        $this->renderLayout();
    }

    /**
     * init Progress
     *
     * @access protected
     * @return BS_KST_Model_Kstprogress
     * @author Bui Phong
     */
    protected function _initKstprogress()
    {
        $kstprogressId   = $this->getRequest()->getParam('id', 0);
        $kstprogress     = Mage::getModel('bs_kst/kstprogress')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($kstprogressId);
        if (!$kstprogress->getId()) {
            return false;
        } elseif (!$kstprogress->getStatus()) {
            return false;
        }
        return $kstprogress;
    }

    /**
     * view progress action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function viewAction()
    {
        $kstprogress = $this->_initKstprogress();
        if (!$kstprogress) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_kstprogress', $kstprogress);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('kst-kstprogress kst-kstprogress' . $kstprogress->getId());
        }
        if (Mage::helper('bs_kst/kstprogress')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('bs_kst')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'kstprogresses',
                    array(
                        'label' => Mage::helper('bs_kst')->__('Progresses'),
                        'link'  => Mage::helper('bs_kst/kstprogress')->getKstprogressesUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'kstprogress',
                    array(
                        'label' => $kstprogress->getAcReg(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $kstprogress->getKstprogressUrl());
        }
        $this->renderLayout();
    }
}
