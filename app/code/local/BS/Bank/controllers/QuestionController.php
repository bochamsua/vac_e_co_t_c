<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Question front contrller
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_QuestionController extends Mage_Core_Controller_Front_Action
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
        if (Mage::helper('bs_bank/question')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('bs_bank')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'questions',
                    array(
                        'label' => Mage::helper('bs_bank')->__('Questions'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('bs_bank/question')->getQuestionsUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('bs_bank/question/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('bs_bank/question/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('bs_bank/question/meta_description'));
        }
        $this->renderLayout();
    }

    /**
     * init Question
     *
     * @access protected
     * @return BS_Bank_Model_Question
     * @author Bui Phong
     */
    protected function _initQuestion()
    {
        $questionId   = $this->getRequest()->getParam('id', 0);
        $question     = Mage::getModel('bs_bank/question')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($questionId);
        if (!$question->getId()) {
            return false;
        } elseif (!$question->getStatus()) {
            return false;
        }
        return $question;
    }

    /**
     * view question action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function viewAction()
    {
        $question = $this->_initQuestion();
        if (!$question) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_question', $question);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('bank-question bank-question' . $question->getId());
        }
        if (Mage::helper('bs_bank/question')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('bs_bank')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'questions',
                    array(
                        'label' => Mage::helper('bs_bank')->__('Questions'),
                        'link'  => Mage::helper('bs_bank/question')->getQuestionsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'question',
                    array(
                        'label' => $question->getQuestionQuestion(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $question->getQuestionUrl());
        }
        if ($headBlock) {
            if ($question->getMetaTitle()) {
                $headBlock->setTitle($question->getMetaTitle());
            } else {
                $headBlock->setTitle($question->getQuestionQuestion());
            }
            $headBlock->setKeywords($question->getMetaKeywords());
            $headBlock->setDescription($question->getMetaDescription());
        }
        $this->renderLayout();
    }
}
