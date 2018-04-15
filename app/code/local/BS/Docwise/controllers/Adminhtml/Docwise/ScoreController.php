<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Score admin controller
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Adminhtml_Docwise_ScoreController extends BS_Docwise_Controller_Adminhtml_Docwise
{
    /**
     * init the score
     *
     * @access protected
     * @return BS_Docwise_Model_Score
     */
    protected function _initScore()
    {
        $scoreId  = (int) $this->getRequest()->getParam('id');
        $score    = Mage::getModel('bs_docwise/score');
        if ($scoreId) {
            $score->load($scoreId);
        }
        Mage::register('current_score', $score);
        return $score;
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
        $this->_title(Mage::helper('bs_docwise')->__('Docwise'))
             ->_title(Mage::helper('bs_docwise')->__('Scores'));
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
     * edit score - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $scoreId    = $this->getRequest()->getParam('id');
        $score      = $this->_initScore();
        if ($scoreId && !$score->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_docwise')->__('This score no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getScoreData(true);
        if (!empty($data)) {
            $score->setData($data);
        }
        Mage::register('score_data', $score);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_docwise')->__('Docwise'))
             ->_title(Mage::helper('bs_docwise')->__('Scores'));
        if ($score->getId()) {
            $this->_title($score->getTraineeName());
        } else {
            $this->_title(Mage::helper('bs_docwise')->__('Add score'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new score action
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
     * save score - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('score')) {
            try {
                $data = $this->_filterDates($data, array('exam_date' ,'expire_date'));
                $score = $this->_initScore();

                $trainee = Mage::getModel('bs_docwise/trainee')->load($data['trainee_id']);
                $data['trainee_name'] = $trainee->getTraineeName();
                $data['vaeco_id']   = $trainee->getVaecoId();



                $score->addData($data);
                $score->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Score was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $score->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setScoreData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was a problem saving the score.')
                );
                Mage::getSingleton('adminhtml/session')->setScoreData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Unable to find score to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete score - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $score = Mage::getModel('bs_docwise/score');
                $score->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Score was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting score.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Could not find score to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete score - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $scoreIds = $this->getRequest()->getParam('score');
        if (!is_array($scoreIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select scores to delete.')
            );
        } else {
            try {
                foreach ($scoreIds as $scoreId) {
                    $score = Mage::getModel('bs_docwise/score');
                    $score->setId($scoreId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Total of %d scores were successfully deleted.', count($scoreIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting scores.')
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
        $scoreIds = $this->getRequest()->getParam('score');
        if (!is_array($scoreIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select scores.')
            );
        } else {
            try {
                foreach ($scoreIds as $scoreId) {
                $score = Mage::getSingleton('bs_docwise/score')->load($scoreId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d scores were successfully updated.', count($scoreIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating scores.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass exam change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massExamIdAction()
    {
        $scoreIds = $this->getRequest()->getParam('score');
        if (!is_array($scoreIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select scores.')
            );
        } else {
            try {
                foreach ($scoreIds as $scoreId) {
                    $score = Mage::getSingleton('bs_docwise/score')->load($scoreId)
                        ->setExamId($this->getRequest()->getParam('flag_exam_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d scores were successfully updated.', count($scoreIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating scores.')
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
        $fileName   = 'score.csv';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_score_grid')
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
        $fileName   = 'score.xls';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_score_grid')
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
        $fileName   = 'score.xml';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_score_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/bs_docwise/score');
    }

    public function getTraineeIdAction()
    {


        $result = array();
        $vaecoId = $this->getRequest()->getPost('vaeco_id');
        if (strlen($vaecoId) == 4) {
            $vaecoId = 'VAE0' . $vaecoId;
        } elseif (strlen($vaecoId) == 5) {
            $vaecoId = 'VAE' . $vaecoId;
        }

        $customer = Mage::getModel('bs_docwise/trainee')->getCollection()->addFieldToFilter('vaeco_id', $vaecoId)->getFirstItem();

        if ($customer->getId()) {
            $result['id'] = $customer->getId();
            $result['info'] = $customer->getTraineeName().' - '.$vaecoId;

        }
        $result['info'] = $customer->getTraineeName().' - '.$vaecoId;


        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}
