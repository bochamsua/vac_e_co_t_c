<?php
/**
 * BS_TraineeCert extension
 * 
 * @category       BS
 * @package        BS_TraineeCert
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Certificate admin controller
 *
 * @category    BS
 * @package     BS_TraineeCert
 * @author Bui Phong
 */
class BS_TraineeCert_Adminhtml_Traineecert_TraineecertController extends BS_TraineeCert_Controller_Adminhtml_TraineeCert
{
    /**
     * init the trainee certificate
     *
     * @access protected
     * @return BS_TraineeCert_Model_Traineecert
     */
    protected function _initTraineecert()
    {
        $traineecertId  = (int) $this->getRequest()->getParam('id');
        $traineecert    = Mage::getModel('bs_traineecert/traineecert');
        if ($traineecertId) {
            $traineecert->load($traineecertId);
        }
        Mage::register('current_traineecert', $traineecert);
        return $traineecert;
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
        $this->_title(Mage::helper('bs_traineecert')->__('Trainee Certificate'))
             ->_title(Mage::helper('bs_traineecert')->__('Trainee Certificates'));
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
     * edit trainee certificate - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $traineecertId    = $this->getRequest()->getParam('id');
        $traineecert      = $this->_initTraineecert();
        if ($traineecertId && !$traineecert->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_traineecert')->__('This trainee certificate no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getTraineecertData(true);
        if (!empty($data)) {
            $traineecert->setData($data);
        }
        Mage::register('traineecert_data', $traineecert);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_traineecert')->__('Trainee Certificate'))
             ->_title(Mage::helper('bs_traineecert')->__('Trainee Certificates'));
        if ($traineecert->getId()) {
            $this->_title($traineecert->getCertNo());
        } else {
            $this->_title(Mage::helper('bs_traineecert')->__('Add trainee certificate'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new trainee certificate action
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
     * save trainee certificate - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('traineecert')) {
            try {
                $data = $this->_filterDates($data, array('issue_date'));
                $traineecert = $this->_initTraineecert();
                $traineecert->addData($data);
                $traineecert->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_traineecert')->__('Trainee Certificate was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $traineecert->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setTraineecertData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traineecert')->__('There was a problem saving the trainee certificate.')
                );
                Mage::getSingleton('adminhtml/session')->setTraineecertData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_traineecert')->__('Unable to find trainee certificate to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete trainee certificate - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $traineecert = Mage::getModel('bs_traineecert/traineecert');
                $traineecert->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_traineecert')->__('Trainee Certificate was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traineecert')->__('There was an error deleting trainee certificate.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_traineecert')->__('Could not find trainee certificate to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete trainee certificate - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $traineecertIds = $this->getRequest()->getParam('traineecert');
        if (!is_array($traineecertIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traineecert')->__('Please select trainee certificates to delete.')
            );
        } else {
            try {
                foreach ($traineecertIds as $traineecertId) {
                    $traineecert = Mage::getModel('bs_traineecert/traineecert');
                    $traineecert->setId($traineecertId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_traineecert')->__('Total of %d trainee certificates were successfully deleted.', count($traineecertIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traineecert')->__('There was an error deleting trainee certificates.')
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
        $traineecertIds = $this->getRequest()->getParam('traineecert');
        if (!is_array($traineecertIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traineecert')->__('Please select trainee certificates.')
            );
        } else {
            try {
                foreach ($traineecertIds as $traineecertId) {
                $traineecert = Mage::getSingleton('bs_traineecert/traineecert')->load($traineecertId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d trainee certificates were successfully updated.', count($traineecertIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traineecert')->__('There was an error updating trainee certificates.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massIssueDateAction()
    {
        $traineecertIds = $this->getRequest()->getParam('traineecert');
        if (!is_array($traineecertIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traineecert')->__('Please select trainee certificates.')
            );
        } else {
            $date = $this->getRequest()->getParam('issue_date');
            $dates = array('input_date'=>$date);

            $dates = $this->_filterDates($dates,array('input_date'));

            $date = $dates['input_date'];

            try {
                foreach ($traineecertIds as $traineecertId) {
                    $traineecert = Mage::getSingleton('bs_traineecert/traineecert')->load($traineecertId)
                        ->setIssueDate($date)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d trainee certificates were successfully updated.', count($traineecertIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traineecert')->__('There was an error updating trainee certificates.')
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
        $fileName   = 'traineecert.csv';
        $content    = $this->getLayout()->createBlock('bs_traineecert/adminhtml_traineecert_grid')
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
        $fileName   = 'traineecert.xls';
        $content    = $this->getLayout()->createBlock('bs_traineecert/adminhtml_traineecert_grid')
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
        $fileName   = 'traineecert.xml';
        $content    = $this->getLayout()->createBlock('bs_traineecert/adminhtml_traineecert_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/traineecert');
    }
}
