<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Manage admin controller
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Adminhtml_Report_ManageController extends BS_Report_Controller_Adminhtml_Report
{
    /**
     * init the manage
     *
     * @access protected
     * @return BS_Report_Model_Manage
     */
    protected function _initManage()
    {
        $manageId  = (int) $this->getRequest()->getParam('id');
        $manage    = Mage::getModel('bs_report/manage');
        if ($manageId) {
            $manage->load($manageId);
        }
        Mage::register('current_manage', $manage);
        return $manage;
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
        $this->_title(Mage::helper('bs_report')->__('Report'))
             ->_title(Mage::helper('bs_report')->__('Manages'));
        $this->renderLayout();
    }

    public function reportAction()
    {
        $requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('filter'));
        $requestData = $this->_filterDates($requestData, array('from', 'to'));



        $result = Mage::helper('bs_report')->getReportStatistic($requestData, 'manager');


        if(count($result)){

            $templateData = array(
                'duration' => $result['duration']
            );

            $template = Mage::helper('bs_formtemplate')->getFormtemplate('report');
            $contentHtml = array(
                'type' => 'replace',
                'content' => Mage::helper('bs_report')->prepareReport($result['data']),
                'variable' => 'content'
            );


            $res = Mage::helper('bs_traininglist/docx')->generateDocx('EMPLOYEE REPORT MANAGER', $template, $templateData, null, null,null,null,null,null,$contentHtml);

            $this->_getSession()->addSuccess(Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name']));

        }else {
            $this->_getSession()->addNotice(Mage::helper('bs_traininglist')->__('There is no record found!'));
        }




        $this->_redirect('*/*/');
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
     * edit manage - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $manageId    = $this->getRequest()->getParam('id');
        $manage      = $this->_initManage();
        if ($manageId && !$manage->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_report')->__('This manage no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getManageData(true);
        if (!empty($data)) {
            $manage->setData($data);
        }
        Mage::register('manage_data', $manage);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_report')->__('Report'))
             ->_title(Mage::helper('bs_report')->__('Manages'));
        if ($manage->getId()) {
            $this->_title($manage->getManage());
        } else {
            $this->_title(Mage::helper('bs_report')->__('Add manage'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new manage action
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
     * save manage - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('manage')) {
            try {
                $manage = $this->_initManage();
                $manage->addData($data);
                $manage->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_report')->__('Manage was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $manage->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setManageData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was a problem saving the manage.')
                );
                Mage::getSingleton('adminhtml/session')->setManageData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_report')->__('Unable to find manage to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete manage - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $manage = Mage::getModel('bs_report/manage');
                $manage->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_report')->__('Manage was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error deleting manage.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_report')->__('Could not find manage to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete manage - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $manageIds = $this->getRequest()->getParam('manage');
        if (!is_array($manageIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select manages to delete.')
            );
        } else {
            try {
                foreach ($manageIds as $manageId) {
                    $manage = Mage::getModel('bs_report/manage');
                    $manage->setId($manageId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_report')->__('Total of %d manages were successfully deleted.', count($manageIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error deleting manages.')
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
        $manageIds = $this->getRequest()->getParam('manage');
        if (!is_array($manageIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select manages.')
            );
        } else {
            try {
                foreach ($manageIds as $manageId) {
                $manage = Mage::getSingleton('bs_report/manage')->load($manageId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d manages were successfully updated.', count($manageIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating manages.')
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
        $fileName   = 'manage.csv';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_manage_grid')
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
        $fileName   = 'manage.xls';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_manage_grid')
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
        $fileName   = 'manage.xml';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_manage_grid')
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
        $currentUser = Mage::getSingleton('admin/session')->getUser();

        $shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'manage_ids')->getFirstItem();
        $ids = array();
        if($shortcut->getId()){
            $content = $shortcut->getDescription();
            $content = str_replace(" ", "", $content);

            $ids = explode(",", $content);

        }
        $allow = false;
        if(in_array($currentUser->getId(), $ids)){
            $allow = true;
        }
        return $allow && Mage::getSingleton('admin/session')->isAllowed('bs_report/manage');
    }
}
