<?php
/**
 * BS_Tasktraining extension
 *
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin controller
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Adminhtml_Tasktraining_TaskinstructorController extends BS_Tasktraining_Controller_Adminhtml_Tasktraining
{
    /**
     * init the instructor
     *
     * @access protected
     * @return BS_Tasktraining_Model_Taskinstructor
     */
    protected function _initTaskinstructor()
    {
        $taskinstructorId  = (int) $this->getRequest()->getParam('id');
        $taskinstructor    = Mage::getModel('bs_tasktraining/taskinstructor');
        if ($taskinstructorId) {
            $taskinstructor->load($taskinstructorId);
        }
        Mage::register('current_taskinstructor', $taskinstructor);
        return $taskinstructor;
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
        $this->_title(Mage::helper('bs_tasktraining')->__('Task Training Instructor'))
             ->_title(Mage::helper('bs_tasktraining')->__('Instructor'));
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
     * edit instructor - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $taskinstructorId    = $this->getRequest()->getParam('id');
        $taskinstructor      = $this->_initTaskinstructor();
        if ($taskinstructorId && !$taskinstructor->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_tasktraining')->__('This instructor no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getTaskinstructorData(true);
        if (!empty($data)) {
            $taskinstructor->setData($data);
        }
        Mage::register('taskinstructor_data', $taskinstructor);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_tasktraining')->__('Task Training Instructor'))
             ->_title(Mage::helper('bs_tasktraining')->__('Instructor'));
        if ($taskinstructor->getId()) {
            $this->_title($taskinstructor->getName());
        } else {
            $this->_title(Mage::helper('bs_tasktraining')->__('Add instructor'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new instructor action
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
     * save instructor - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('taskinstructor')) {
            $taskinstructor = $this->_initTaskinstructor();



            if ($data['import'] != '') {
                $import = $data['import'];
                $vaecoIds = explode("\r\n", $import);
                $i = 0;
                foreach ($vaecoIds as $vId) {
                    $id = trim($vId);
                    if (strlen($id) == 5) {
                        $id = "VAE" . $id;
                    } elseif (strlen($id) == 4) {
                        $id = "VAE0" . $id;
                    }
                    $id = strtoupper($id);

                    $chk = $this->checkInstructor($id);
                    if (!$chk) {
                        $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                        if ($customer->getId()) {
                            $cus = Mage::getModel('customer/customer')->load($customer->getId());
                            $ti = Mage::getModel('bs_tasktraining/taskinstructor');
                            $ti->setName($cus->getName())->setVaecoId($id)->save();
                            $i++;
                        }

                    }
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_tasktraining')->__('%s Instructors were successfully added. ', $i)
                );

                $this->_redirect('*/*/');
                return;




            } else {
                $vaecoId = $data['vaeco_id'];
                $check = $this->checkInstructor($vaecoId);
                $pass = true;

                if (!$taskinstructor->getId() && $check) {
                    $pass = false;
                }
                if ($pass) {
                    try {
                        $taskinstructor->addData($data);
                        $categories = $this->getRequest()->getPost('category_ids', -1);
                        if ($categories != -1) {
                            $categories = explode(',', $categories);
                            $categories = array_unique($categories);
                            $taskinstructor->setCategoriesData($categories);
                        }
                        $taskinstructor->save();
                        $add = '';
                        if ($this->getRequest()->getParam('popup')) {
                            $add = '<script>window.close()</script>';
                        }
                        Mage::getSingleton('adminhtml/session')->addSuccess(
                            Mage::helper('bs_tasktraining')->__('Instructor was successfully saved. %s', $add)
                        );
                        Mage::getSingleton('adminhtml/session')->setFormData(false);
                        if ($this->getRequest()->getParam('back')) {
                            $this->_redirect('*/*/edit', array('id' => $taskinstructor->getId()));
                            return;
                        }
                        $this->_redirect('*/*/');
                        return;
                    } catch (Mage_Core_Exception $e) {
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                        Mage::getSingleton('adminhtml/session')->setTaskinstructorData($data);
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                        return;
                    } catch (Exception $e) {
                        Mage::logException($e);
                        Mage::getSingleton('adminhtml/session')->addError(
                            Mage::helper('bs_tasktraining')->__('There was a problem saving the instructor.')
                        );
                        Mage::getSingleton('adminhtml/session')->setTaskinstructorData($data);
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                        return;
                    }
                } else {
                    $this->_getSession()->addError(
                        Mage::helper('bs_tasktraining')->__('The instructor already existed!')
                    );
                    $this->_redirect('*/*/');
                    return;
                }
            }


        }

        $this->_redirect('*/*/');
    }

    public function checkInstructor($vaecoId, $id = null){
        $instructor = Mage::getModel('bs_tasktraining/taskinstructor')->getCollection()->addFieldToFilter('vaeco_id', $vaecoId);

        if($id){
            $instructor->addFieldToFilter('entity_id', array('neq'=>$id));
        }
        $ins = $instructor->getFirstItem();
        if($ins->getId()){
            return true;
        }

        return false;
    }

    /**
     * delete instructor - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $taskinstructor = Mage::getModel('bs_tasktraining/taskinstructor');
                $id = $this->getRequest()->getParam('id');
                $taskinstructor->setId($id)->delete();

                //remove from task function table
                $resource = Mage::getSingleton('core/resource');
                $writeConnection = $resource->getConnection('core_write');
                $readConnection = $resource->getConnection('core_read');

                $tableIns = $resource->getTableName('bs_tasktraining/taskfunction');

                $writeConnection->query("DELETE FROM {$tableIns} WHERE instructor_id = {$id}");



                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_tasktraining')->__('Instructor was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error deleting instructor.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_tasktraining')->__('Could not find instructor to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete instructor - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $taskinstructorIds = $this->getRequest()->getParam('taskinstructor');
        if (!is_array($taskinstructorIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor to delete.')
            );
        } else {
            try {
                foreach ($taskinstructorIds as $taskinstructorId) {
                    $taskinstructor = Mage::getModel('bs_tasktraining/taskinstructor');
                    $taskinstructor->setId($taskinstructorId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_tasktraining')->__('Total of %d instructor were successfully deleted.', count($taskinstructorIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error deleting instructor.')
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
        $taskinstructorIds = $this->getRequest()->getParam('taskinstructor');
        if (!is_array($taskinstructorIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor.')
            );
        } else {
            try {
                foreach ($taskinstructorIds as $taskinstructorId) {
                $taskinstructor = Mage::getSingleton('bs_tasktraining/taskinstructor')->load($taskinstructorId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor were successfully updated.', count($taskinstructorIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error updating instructor.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get categories action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function categoriesAction()
    {
        $this->_initTaskinstructor();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * get child categories action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function categoriesJsonAction()
    {
        $this->_initTaskinstructor();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('bs_tasktraining/adminhtml_taskinstructor_edit_tab_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
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
        $fileName   = 'taskinstructor.csv';
        $content    = $this->getLayout()->createBlock('bs_tasktraining/adminhtml_taskinstructor_grid')
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
        $fileName   = 'taskinstructor.xls';
        $content    = $this->getLayout()->createBlock('bs_tasktraining/adminhtml_taskinstructor_grid')
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
        $fileName   = 'taskinstructor.xml';
        $content    = $this->getLayout()->createBlock('bs_tasktraining/adminhtml_taskinstructor_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/bs_tasktraining/taskinstructor');
    }
}
