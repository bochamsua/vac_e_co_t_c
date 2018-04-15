<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Member admin controller
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Adminhtml_Kst_KstmemberController extends BS_KST_Controller_Adminhtml_KST
{
    /**
     * init the member
     *
     * @access protected
     * @return BS_KST_Model_Kstmember
     */
    protected function _initKstmember()
    {
        $kstmemberId  = (int) $this->getRequest()->getParam('id');
        $kstmember    = Mage::getModel('bs_kst/kstmember');
        if ($kstmemberId) {
            $kstmember->load($kstmemberId);
        }
        Mage::register('current_kstmember', $kstmember);
        return $kstmember;
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
        $this->_title(Mage::helper('bs_kst')->__('KST'))
             ->_title(Mage::helper('bs_kst')->__('Members'));
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
     * edit member - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $kstmemberId    = $this->getRequest()->getParam('id');
        $kstmember      = $this->_initKstmember();
        if ($kstmemberId && !$kstmember->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_kst')->__('This member no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getKstmemberData(true);
        if (!empty($data)) {
            $kstmember->setData($data);
        }
        Mage::register('kstmember_data', $kstmember);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_kst')->__('KST'))
             ->_title(Mage::helper('bs_kst')->__('Members'));
        if ($kstmember->getId()) {
            $this->_title($kstmember->getName());
        } else {
            $this->_title(Mage::helper('bs_kst')->__('Add member'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new member action
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
     * save member - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('kstmember')) {
            try {

                if($data['import'] != ''){
                    $import = $data['import'];
                    $import = explode("\r\n", $import);

                    foreach ($import as $id) {
                        $id = trim($id);
                        if(strlen($id) == 5){
                            $id = "VAE".$id;
                        }elseif (strlen($id) == 4){
                            $id = "VAE0".$id;
                        }
                        $id = strtoupper($id);

                        if($id != ''){
                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                            if($customer->getId()){
                                $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                $name = $cus->getName();
                                $username = $cus->getUsername();

                                $model = Mage::getModel('bs_kst/kstmember');
                                $model->setCourseId($data['course_id']);
                                $model->setKstgroupId($data['kstgroup_id']);
                                $model->setName($name);
                                $model->setVaecoId($id);
                                $model->setUsername($username);

                                $model->save();


                            }
                        }


                    }

                }else {
                    $kstmember = $this->_initKstmember();
                    $kstmember->addData($data);
                    $kstmember->save();
                }

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Member was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $kstmember->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setKstmemberData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was a problem saving the member.')
                );
                Mage::getSingleton('adminhtml/session')->setKstmemberData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Unable to find member to save.')
        );
        $this->_redirect('*/*/');
    }

    public function updateGroupAction(){
        $result = array();
        $courseId = $this->getRequest()->getPost('course_id');
        $result['group'] = '<option value="">There is no group found</option>';
        if($courseId){

            $groups = Mage::getModel('bs_kst/kstgroup')->getCollection()->addFieldToFilter('course_id', $courseId);


            if($groups->count()){
                $text = '';
                foreach ($groups as $g) {

                    $text  .= '<option value="'.$g->getId().'">'.$g->getName().'</option>';
                }
                $result['group'] = $text;
            }
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * delete member - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $kstmember = Mage::getModel('bs_kst/kstmember');
                $kstmember->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Member was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting member.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Could not find member to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete member - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $kstmemberIds = $this->getRequest()->getParam('kstmember');
        if (!is_array($kstmemberIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select members to delete.')
            );
        } else {
            try {
                foreach ($kstmemberIds as $kstmemberId) {
                    $kstmember = Mage::getModel('bs_kst/kstmember');
                    $kstmember->setId($kstmemberId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Total of %d members were successfully deleted.', count($kstmemberIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting members.')
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
        $kstmemberIds = $this->getRequest()->getParam('kstmember');
        if (!is_array($kstmemberIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select members.')
            );
        } else {
            try {
                foreach ($kstmemberIds as $kstmemberId) {
                $kstmember = Mage::getSingleton('bs_kst/kstmember')->load($kstmemberId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d members were successfully updated.', count($kstmemberIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating members.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Leader change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massIsLeaderAction()
    {
        $kstmemberIds = $this->getRequest()->getParam('kstmember');
        if (!is_array($kstmemberIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select members.')
            );
        } else {
            try {
                foreach ($kstmemberIds as $kstmemberId) {
                $kstmember = Mage::getSingleton('bs_kst/kstmember')->load($kstmemberId)
                    ->setIsLeader($this->getRequest()->getParam('flag_is_leader'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d members were successfully updated.', count($kstmemberIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating members.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass group change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massKstgroupIdAction()
    {
        $kstmemberIds = $this->getRequest()->getParam('kstmember');
        if (!is_array($kstmemberIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select members.')
            );
        } else {
            try {
                foreach ($kstmemberIds as $kstmemberId) {
                $kstmember = Mage::getSingleton('bs_kst/kstmember')->load($kstmemberId)
                    ->setKstgroupId($this->getRequest()->getParam('flag_kstgroup_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d members were successfully updated.', count($kstmemberIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating members.')
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
        $fileName   = 'kstmember.csv';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstmember_grid')
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
        $fileName   = 'kstmember.xls';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstmember_grid')
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
        $fileName   = 'kstmember.xml';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstmember_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_kst/kstmember');
    }


}
