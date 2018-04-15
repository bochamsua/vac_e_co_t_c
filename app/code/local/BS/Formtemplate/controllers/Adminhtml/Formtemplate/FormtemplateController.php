<?php
/**
 * BS_Formtemplate extension
 * 
 * @category       BS
 * @package        BS_Formtemplate
 * @copyright      Copyright (c) 2015
 */
/**
 * Form Template admin controller
 *
 * @category    BS
 * @package     BS_Formtemplate
 * @author Bui Phong
 */
class BS_Formtemplate_Adminhtml_Formtemplate_FormtemplateController extends BS_Formtemplate_Controller_Adminhtml_Formtemplate
{
    /**
     * init the form template
     *
     * @access protected
     * @return BS_Formtemplate_Model_Formtemplate
     */
    protected function _initFormtemplate()
    {
        $formtemplateId  = (int) $this->getRequest()->getParam('id');
        $formtemplate    = Mage::getModel('bs_formtemplate/formtemplate');
        if ($formtemplateId) {
            $formtemplate->load($formtemplateId);
        }
        Mage::register('current_formtemplate', $formtemplate);
        return $formtemplate;
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
        $this->_title(Mage::helper('bs_formtemplate')->__('FORMS Template'))
             ->_title(Mage::helper('bs_formtemplate')->__('Form Templates'));
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
     * edit form template - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $formtemplateId    = $this->getRequest()->getParam('id');
        $formtemplate      = $this->_initFormtemplate();
        if ($formtemplateId && !$formtemplate->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_formtemplate')->__('This form template no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getFormtemplateData(true);
        if (!empty($data)) {
            $formtemplate->setData($data);
        }
        Mage::register('formtemplate_data', $formtemplate);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_formtemplate')->__('FORMS Template'))
             ->_title(Mage::helper('bs_formtemplate')->__('Form Templates'));
        if ($formtemplate->getId()) {
            $this->_title($formtemplate->getTemplateName());
        } else {
            $this->_title(Mage::helper('bs_formtemplate')->__('Add form template'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new form template action
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
     * save form template - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('formtemplate')) {
            try {
                $data = $this->_filterDates($data, array('template_date'));
                $formtemplate = $this->_initFormtemplate();
                $formtemplate->addData($data);
                $templateFileName = $this->_uploadAndGetName(
                    'template_file',
                    Mage::helper('bs_formtemplate/formtemplate')->getFileBaseDir(),
                    $data
                );
                $formtemplate->setData('template_file', $templateFileName);
                $formtemplate->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_formtemplate')->__('Form Template was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $formtemplate->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['template_file']['value'])) {
                    $data['template_file'] = $data['template_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormtemplateData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['template_file']['value'])) {
                    $data['template_file'] = $data['template_file']['value'];
                }

                $error = '';
                if(strpos($e->getMessage(), "Duplicate")){
                    $error = 'There was already a template for this form. Please edit it instead.';
                }

                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_formtemplate')->__('There was a problem saving the form template. %s', $error)
                );
                Mage::getSingleton('adminhtml/session')->setFormtemplateData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_formtemplate')->__('Unable to find form template to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete form template - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $formtemplate = Mage::getModel('bs_formtemplate/formtemplate');
                $formtemplate->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_formtemplate')->__('Form Template was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_formtemplate')->__('There was an error deleting form template.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_formtemplate')->__('Could not find form template to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete form template - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $formtemplateIds = $this->getRequest()->getParam('formtemplate');
        if (!is_array($formtemplateIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_formtemplate')->__('Please select form templates to delete.')
            );
        } else {
            try {
                foreach ($formtemplateIds as $formtemplateId) {
                    $formtemplate = Mage::getModel('bs_formtemplate/formtemplate');
                    $formtemplate->setId($formtemplateId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_formtemplate')->__('Total of %d form templates were successfully deleted.', count($formtemplateIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_formtemplate')->__('There was an error deleting form templates.')
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
        $formtemplateIds = $this->getRequest()->getParam('formtemplate');
        if (!is_array($formtemplateIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_formtemplate')->__('Please select form templates.')
            );
        } else {
            try {
                foreach ($formtemplateIds as $formtemplateId) {
                $formtemplate = Mage::getSingleton('bs_formtemplate/formtemplate')->load($formtemplateId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d form templates were successfully updated.', count($formtemplateIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_formtemplate')->__('There was an error updating form templates.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Revision change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massTemplateRevisionAction()
    {
        $formtemplateIds = $this->getRequest()->getParam('formtemplate');
        if (!is_array($formtemplateIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_formtemplate')->__('Please select form templates.')
            );
        } else {
            try {
                foreach ($formtemplateIds as $formtemplateId) {
                $formtemplate = Mage::getSingleton('bs_formtemplate/formtemplate')->load($formtemplateId)
                    ->setTemplateRevision($this->getRequest()->getParam('flag_template_revision'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d form templates were successfully updated.', count($formtemplateIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_formtemplate')->__('There was an error updating form templates.')
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
        $fileName   = 'formtemplate.csv';
        $content    = $this->getLayout()->createBlock('bs_formtemplate/adminhtml_formtemplate_grid')
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
        $fileName   = 'formtemplate.xls';
        $content    = $this->getLayout()->createBlock('bs_formtemplate/adminhtml_formtemplate_grid')
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
        $fileName   = 'formtemplate.xml';
        $content    = $this->getLayout()->createBlock('bs_formtemplate/adminhtml_formtemplate_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('system/formtemplate');
    }
}
