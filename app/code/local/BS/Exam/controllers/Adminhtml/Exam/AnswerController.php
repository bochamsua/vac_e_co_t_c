<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Answer admin controller
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Adminhtml_Exam_AnswerController extends BS_Exam_Controller_Adminhtml_Exam
{
    /**
     * init the answer
     *
     * @access protected
     * @return BS_Exam_Model_Answer
     */
    protected function _initAnswer()
    {
        $answerId  = (int) $this->getRequest()->getParam('id');
        $answer    = Mage::getModel('bs_exam/answer');
        if ($answerId) {
            $answer->load($answerId);
        }
        Mage::register('current_answer', $answer);
        return $answer;
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
             ->_title(Mage::helper('bs_exam')->__('Answers'));
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
     * edit answer - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $answerId    = $this->getRequest()->getParam('id');
        $answer      = $this->_initAnswer();
        if ($answerId && !$answer->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_exam')->__('This answer no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getAnswerData(true);
        if (!empty($data)) {
            $answer->setData($data);
        }
        Mage::register('answer_data', $answer);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_exam')->__('Exam'))
             ->_title(Mage::helper('bs_exam')->__('Answers'));
        if ($answer->getId()) {
            $this->_title($answer->getAnswerAnswer());
        } else {
            $this->_title(Mage::helper('bs_exam')->__('Add answer'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new answer action
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
     * save answer - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('answer')) {
            try {
                $answer = $this->_initAnswer();
                $answer->addData($data);
                $answer->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Answer was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $answer->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setAnswerData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was a problem saving the answer.')
                );
                Mage::getSingleton('adminhtml/session')->setAnswerData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_exam')->__('Unable to find answer to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete answer - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $answer = Mage::getModel('bs_exam/answer');
                $answer->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Answer was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error deleting answer.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_exam')->__('Could not find answer to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete answer - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $answerIds = $this->getRequest()->getParam('answer');
        if (!is_array($answerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_exam')->__('Please select answers to delete.')
            );
        } else {
            try {
                foreach ($answerIds as $answerId) {
                    $answer = Mage::getModel('bs_exam/answer');
                    $answer->setId($answerId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Total of %d answers were successfully deleted.', count($answerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error deleting answers.')
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
        $answerIds = $this->getRequest()->getParam('answer');
        if (!is_array($answerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_exam')->__('Please select answers.')
            );
        } else {
            try {
                foreach ($answerIds as $answerId) {
                $answer = Mage::getSingleton('bs_exam/answer')->load($answerId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d answers were successfully updated.', count($answerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error updating answers.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Correct change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massAnswerCorrectAction()
    {
        $answerIds = $this->getRequest()->getParam('answer');
        if (!is_array($answerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_exam')->__('Please select answers.')
            );
        } else {
            try {
                foreach ($answerIds as $answerId) {
                $answer = Mage::getSingleton('bs_exam/answer')->load($answerId)
                    ->setAnswerCorrect($this->getRequest()->getParam('flag_answer_correct'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d answers were successfully updated.', count($answerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error updating answers.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass question change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massQuestionIdAction()
    {
        $answerIds = $this->getRequest()->getParam('answer');
        if (!is_array($answerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_exam')->__('Please select answers.')
            );
        } else {
            try {
                foreach ($answerIds as $answerId) {
                $answer = Mage::getSingleton('bs_exam/answer')->load($answerId)
                    ->setQuestionId($this->getRequest()->getParam('flag_question_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d answers were successfully updated.', count($answerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error updating answers.')
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
        $fileName   = 'answer.csv';
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_answer_grid')
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
        $fileName   = 'answer.xls';
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_answer_grid')
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
        $fileName   = 'answer.xml';
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_answer_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/answer');
    }
}
