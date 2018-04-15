<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject front contrller
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_KstsubjectController extends Mage_Core_Controller_Front_Action
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
        if (Mage::helper('bs_kst/kstsubject')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('bs_kst')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'kstsubjects',
                    array(
                        'label' => Mage::helper('bs_kst')->__('Subjects'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('bs_kst/kstsubject')->getKstsubjectsUrl());
        }
        $this->renderLayout();
    }

    /**
     * init Subject
     *
     * @access protected
     * @return BS_KST_Model_Kstsubject
     * @author Bui Phong
     */
    protected function _initKstsubject()
    {
        $kstsubjectId   = $this->getRequest()->getParam('id', 0);
        $kstsubject     = Mage::getModel('bs_kst/kstsubject')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($kstsubjectId);
        if (!$kstsubject->getId()) {
            return false;
        } elseif (!$kstsubject->getStatus()) {
            return false;
        }
        return $kstsubject;
    }

    /**
     * view subject action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function viewAction()
    {
        $kstsubject = $this->_initKstsubject();
        if (!$kstsubject) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_kstsubject', $kstsubject);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('kst-kstsubject kst-kstsubject' . $kstsubject->getId());
        }
        if (Mage::helper('bs_kst/kstsubject')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('bs_kst')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'kstsubjects',
                    array(
                        'label' => Mage::helper('bs_kst')->__('Subjects'),
                        'link'  => Mage::helper('bs_kst/kstsubject')->getKstsubjectsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'kstsubject',
                    array(
                        'label' => $kstsubject->getName(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $kstsubject->getKstsubjectUrl());
        }
        $this->renderLayout();
    }
}
