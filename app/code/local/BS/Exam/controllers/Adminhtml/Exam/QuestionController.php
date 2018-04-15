<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Question admin controller
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Adminhtml_Exam_QuestionController extends BS_Exam_Controller_Adminhtml_Exam
{
    /**
     * init the question
     *
     * @access protected
     * @return BS_Exam_Model_Question
     */
    protected function _initQuestion()
    {
        $questionId  = (int) $this->getRequest()->getParam('id');
        $question    = Mage::getModel('bs_exam/question');
        if ($questionId) {
            $question->load($questionId);
        }
        Mage::register('current_question', $question);
        return $question;
    }

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
        $this->_title(Mage::helper('bs_exam')->__('Exam'))
             ->_title(Mage::helper('bs_exam')->__('Questions'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit question - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $questionId    = $this->getRequest()->getParam('id');
        $question      = $this->_initQuestion();
        if ($questionId && !$question->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_exam')->__('This question no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getQuestionData(true);
        if (!empty($data)) {
            $question->setData($data);
        }
        Mage::register('question_data', $question);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_exam')->__('Exam'))
             ->_title(Mage::helper('bs_exam')->__('Questions'));
        if ($question->getId()) {
            $this->_title($question->getQuestionQuestion());
        } else {
            $this->_title(Mage::helper('bs_exam')->__('Add question'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new question action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save question - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('question')) {
            try {
                $question = $this->_initQuestion();
                $question->addData($data);
                $question->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Question was successfully saved. %s', $add)
                );

                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $question->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setQuestionData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was a problem saving the question.')
                );
                Mage::getSingleton('adminhtml/session')->setQuestionData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_exam')->__('Unable to find question to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete question - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $question = Mage::getModel('bs_exam/question');
                $question->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Question was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error deleting question.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_exam')->__('Could not find question to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete question - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $questionIds = $this->getRequest()->getParam('question');
        if (!is_array($questionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_exam')->__('Please select questions to delete.')
            );
        } else {
            try {
                foreach ($questionIds as $questionId) {
                    $question = Mage::getModel('bs_exam/question');
                    $question->setId($questionId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Total of %d questions were successfully deleted.', count($questionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error deleting questions.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massStatusAction()
    {
        $questionIds = $this->getRequest()->getParam('question');
        if (!is_array($questionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_exam')->__('Please select questions.')
            );
        } else {
            try {
                foreach ($questionIds as $questionId) {
                $question = Mage::getSingleton('bs_exam/question')->load($questionId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d questions were successfully updated.', count($questionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error updating questions.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName   = 'question.csv';
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_question_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName   = 'question.xls';
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_question_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName   = 'question.xml';
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_question_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/question');
    }
}
