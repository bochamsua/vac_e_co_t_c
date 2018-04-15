<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject front contrller
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_SubjectController extends Mage_Core_Controller_Front_Action
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
        if (Mage::helper('bs_bank/subject')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('bs_bank')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'subjects',
                    array(
                        'label' => Mage::helper('bs_bank')->__('Subject'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('bs_bank/subject')->getSubjectsUrl());
        }
        $this->renderLayout();
    }

    /**
     * init Subject
     *
     * @access protected
     * @return BS_Bank_Model_Subject
     * @author Bui Phong
     */
    protected function _initSubject()
    {
        $subjectId   = $this->getRequest()->getParam('id', 0);
        $subject     = Mage::getModel('bs_bank/subject')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($subjectId);
        if (!$subject->getId()) {
            return false;
        } elseif (!$subject->getStatus()) {
            return false;
        }
        return $subject;
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
        $subject = $this->_initSubject();
        if (!$subject) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_subject', $subject);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('bank-subject bank-subject' . $subject->getId());
        }
        if (Mage::helper('bs_bank/subject')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('bs_bank')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'subjects',
                    array(
                        'label' => Mage::helper('bs_bank')->__('Subject'),
                        'link'  => Mage::helper('bs_bank/subject')->getSubjectsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'subject',
                    array(
                        'label' => $subject->getSubjectName(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $subject->getSubjectUrl());
        }
        $this->renderLayout();
    }
}
