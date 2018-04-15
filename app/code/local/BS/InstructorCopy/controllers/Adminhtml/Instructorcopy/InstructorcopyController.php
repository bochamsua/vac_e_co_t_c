<?php
/**
 * BS_InstructorCopy extension
 * 
 * @category       BS
 * @package        BS_InstructorCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Copy admin controller
 *
 * @category    BS
 * @package     BS_InstructorCopy
 * @author Bui Phong
 */
class BS_InstructorCopy_Adminhtml_Instructorcopy_InstructorcopyController extends BS_InstructorCopy_Controller_Adminhtml_InstructorCopy
{
    /**
     * init the instructor copy
     *
     * @access protected
     * @return BS_InstructorCopy_Model_Instructorcopy
     */
    protected function _initInstructorcopy()
    {
        $instructorcopyId  = (int) $this->getRequest()->getParam('id');
        $instructorcopy    = Mage::getModel('bs_instructorcopy/instructorcopy');
        if ($instructorcopyId) {
            $instructorcopy->load($instructorcopyId);
        }
        Mage::register('current_instructorcopy', $instructorcopy);
        return $instructorcopy;
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
        $this->_title(Mage::helper('bs_instructorcopy')->__('Instructor Copy'))
             ->_title(Mage::helper('bs_instructorcopy')->__('Instructor Copies'));
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
     * edit instructor copy - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $instructorcopyId    = $this->getRequest()->getParam('id');
        $instructorcopy      = $this->_initInstructorcopy();
        if ($instructorcopyId && !$instructorcopy->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_instructorcopy')->__('This instructor copy no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getInstructorcopyData(true);
        if (!empty($data)) {
            $instructorcopy->setData($data);
        }
        Mage::register('instructorcopy_data', $instructorcopy);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_instructorcopy')->__('Instructor Copy'))
             ->_title(Mage::helper('bs_instructorcopy')->__('Instructor Copies'));
        if ($instructorcopy->getId()) {
            $this->_title($instructorcopy->getCFrom());
        } else {
            $this->_title(Mage::helper('bs_instructorcopy')->__('Add instructor copy'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new instructor copy action
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
     * save instructor copy - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('instructorcopy')) {
            try {
                $cuFromId = $data['c_from'];
                $cuToIds = $data['c_to'];
                $clear = $data['clearall'];

                $backToId = null;

                if(count($cuToIds)){
                    foreach ($cuToIds as $cuToId) {
                        if($cuToId == $cuFromId){
                            continue;
                        }
                        $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($cuToId);


                        $instructorIds = array();
                        $i=1;

                        if(!$clear){
                            $currentInstructors = Mage::getModel('bs_instructor/instructor')->getCollection()->addCurriculumFilter($cuToId);
                            if($currentInstructors->count()){
                                foreach ($currentInstructors as $tn) {
                                    $instructorIds[$tn->getId()] = array('position'=>$i);

                                    $i++;
                                }

                            }
                        }

                        $fromIns = Mage::getModel('bs_instructor/instructor')->getCollection()->addCurriculumFilter($cuFromId);
                        if($fromIns->count()){
                            foreach ($fromIns as $tn) {
                                $instructorIds[$tn->getId()] = array('position'=>$i);

                                $i++;
                            }

                        }


                        $curriculumInstructor= Mage::getResourceSingleton('bs_instructor/instructor_curriculum')->saveCurriculumRelation($curriculum, $instructorIds);

                        $backToId = $cuToId;
                    }

                }

                $add = '';


                if(count($cuToIds) > 1){
                    $url = $this->getUrl('*/instructorcopy_instructorcopy/new');
                }else {
                    $url = $this->getUrl('*/traininglist_curriculum/edit', array('id'=>$backToId));
                }



                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.window.location.href = \''.$url.'\';window.close()</script>';//trainee_gridJsObject.doFilter()
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructorcopy')->__('The instructors were successfully copied. Now you can continue copying. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
                $this->_redirect('*/instructorcopy_instructorcopy/new');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setInstructorcopyData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorcopy')->__('There was a problem saving the instructor copy.')
                );
                Mage::getSingleton('adminhtml/session')->setInstructorcopyData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_instructorcopy')->__('Unable to find instructor copy to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete instructor copy - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $instructorcopy = Mage::getModel('bs_instructorcopy/instructorcopy');
                $instructorcopy->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructorcopy')->__('Instructor Copy was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorcopy')->__('There was an error deleting instructor copy.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_instructorcopy')->__('Could not find instructor copy to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete instructor copy - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $instructorcopyIds = $this->getRequest()->getParam('instructorcopy');
        if (!is_array($instructorcopyIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructorcopy')->__('Please select instructor copies to delete.')
            );
        } else {
            try {
                foreach ($instructorcopyIds as $instructorcopyId) {
                    $instructorcopy = Mage::getModel('bs_instructorcopy/instructorcopy');
                    $instructorcopy->setId($instructorcopyId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructorcopy')->__('Total of %d instructor copies were successfully deleted.', count($instructorcopyIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorcopy')->__('There was an error deleting instructor copies.')
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
        $instructorcopyIds = $this->getRequest()->getParam('instructorcopy');
        if (!is_array($instructorcopyIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructorcopy')->__('Please select instructor copies.')
            );
        } else {
            try {
                foreach ($instructorcopyIds as $instructorcopyId) {
                $instructorcopy = Mage::getSingleton('bs_instructorcopy/instructorcopy')->load($instructorcopyId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor copies were successfully updated.', count($instructorcopyIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorcopy')->__('There was an error updating instructor copies.')
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
        $fileName   = 'instructorcopy.csv';
        $content    = $this->getLayout()->createBlock('bs_instructorcopy/adminhtml_instructorcopy_grid')
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
        $fileName   = 'instructorcopy.xls';
        $content    = $this->getLayout()->createBlock('bs_instructorcopy/adminhtml_instructorcopy_grid')
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
        $fileName   = 'instructorcopy.xml';
        $content    = $this->getLayout()->createBlock('bs_instructorcopy/adminhtml_instructorcopy_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/bs_instructor/instructorcopy');
    }
}
