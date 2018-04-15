<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject admin controller
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Adminhtml_Kst_KstsubjectController extends BS_KST_Controller_Adminhtml_KST
{
    /**
     * init the subject
     *
     * @access protected
     * @return BS_KST_Model_Kstsubject
     */
    protected function _initKstsubject()
    {
        $kstsubjectId  = (int) $this->getRequest()->getParam('id');
        $kstsubject    = Mage::getModel('bs_kst/kstsubject');
        if ($kstsubjectId) {
            $kstsubject->load($kstsubjectId);
        }
        Mage::register('current_kstsubject', $kstsubject);
        return $kstsubject;
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
             ->_title(Mage::helper('bs_kst')->__('Subjects'));
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
     * edit subject - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $kstsubjectId    = $this->getRequest()->getParam('id');
        $kstsubject      = $this->_initKstsubject();
        if ($kstsubjectId && !$kstsubject->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_kst')->__('This subject no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getKstsubjectData(true);
        if (!empty($data)) {
            $kstsubject->setData($data);
        }
        Mage::register('kstsubject_data', $kstsubject);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_kst')->__('KST'))
             ->_title(Mage::helper('bs_kst')->__('Subjects'));
        if ($kstsubject->getId()) {
            $this->_title($kstsubject->getName());
        } else {
            $this->_title(Mage::helper('bs_kst')->__('Add subject'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new subject action
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
     * save subject - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('kstsubject')) {
            try {
                $data = $this->_filterDates($data, array('subject_date'));

                if(isset($data['import']) && $data['import'] != ''){
                    $curriculumId = $data['curriculum_id'];

                    $import = $data['import'];

                    $clear = $data['clearall'];
                    if($clear){
                        $subjects = Mage::getModel('bs_kst/kstsubject')->getCollection()->addFieldToFilter('curriculum_id', $curriculumId);
                        if($subjects->count()){
                            foreach ($subjects as $subject) {
                                $id = $subject->getId();
                                $subject->delete();

                                $items = Mage::getModel('bs_kst/kstitem')->getCollection()->addFieldToFilter('kstsubject_id', $id);
                                if($items->count()){
                                    $items->walk('delete');
                                }
                            }
                        }

                        //make sure we delete all items that don't have subjects too
                        $items = Mage::getModel('bs_kst/kstitem')->getCollection()->addFieldToFilter('curriculum_id', $curriculumId);
                        if($items->count()){
                            $items->walk('delete');
                        }

                    }

                    $import = explode("\r\n", $import);
                    $i = 0;
                    $result = array();
                    foreach ($import as $line) {

                        $check = $this->checkSubject($line);
                        if($check){//subject found

                            $result[$i]['subject'] = $check;
                            $i++;

                        }else {
                            if (strpos($line, "==")) {
                                $item = explode("==", $line);
                            } else {
                                $item = explode("\t", $line);
                            }

                            $item = array_map('trim', $item);
                            $count = count($item);
                            $name = null;
                            $ref = null;
                            $taskCode = null;
                            $taskCat = null;
                            $applicable = null;

                            if($count == 5){
                                $name = $item[0];
                                $ref = $item[1];
                                $taskCode = $item[2];
                                $taskCat = $item[3];
                                $applicable = $item[4];

                            }elseif($count == 9){
                                $name = $item[0];
                                $ref = $item[1];
                                $taskCode = $item[2];
                                $taskCat = $item[3];
                                $applicable = $item[8];
                            }

                            if($name){
                                $result[$i-1]['items'][] = array(
                                    'name'  => $name,
                                    'ref'   => strtoupper($ref),
                                    'code'  => strtoupper($taskCode),
                                    'cat'   => strtoupper($taskCat),
                                    'applicable'    => $applicable
                                );
                            }else {
                                if($ref != ''){
                                    $countItem = count($result[$i-1]['items']) - 1;
                                    $result[$i-1]['items'][$countItem]['ref'] = $result[$i-1]['items'][$countItem]['ref'].'; '.$ref;
                                }
                            }




                        }





                    }

                    if(count($result)){

                        $i=10;
                        foreach ($result as $item) {
                            $subId = null;
                            if(isset($item['subject'])){
                                //insert Subject first
                                $kSubject = Mage::getModel('bs_kst/kstsubject');
                                $kSubject->setCurriculumId($curriculumId);
                                $kSubject->setName($item['subject']);
                                $kSubject->setPosition($i);
                                $kSubject->save();
                                $subId = $kSubject->getId();
                                $i += 10;


                            }

                            if(isset($item['items'])){
                                $j=10;
                                foreach ($item['items'] as $row) {
                                    $kItem = Mage::getModel('bs_kst/kstitem');
                                    $kItem->setKstsubjectId($subId)
                                        ->setCurriculumId($curriculumId)
                                        ->setName($row['name'])
                                        ->setRef($row['ref'])
                                        ->setTaskcode($row['code'])
                                        ->setTaskcat($row['cat'])
                                        ->setApplicableFor($row['applicable'])
                                        ->setPosition($j)
                                        ;

                                    $kItem->save();

                                    $j += 10;
                                }
                            }
                        }
                    }
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('bs_kst')->__('Subjects and Items have been imported.')
                    );


                }else {
                    $kstsubject = $this->_initKstsubject();
                    $kstsubject->addData($data);
                    $kstsubject->save();
                    $add = '';
                    if($this->getRequest()->getParam('popup')){
                        $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                    }
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('bs_kst')->__('Subject was successfully saved. %s', $add)
                    );
                    Mage::getSingleton('adminhtml/session')->setFormData(false);

                    if ($this->getRequest()->getParam('back')) {
                        $this->_redirect('*/*/edit', array('id' => $kstsubject->getId()));
                        return;
                    }
                }


                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setKstsubjectData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was a problem saving the subject.')
                );
                Mage::getSingleton('adminhtml/session')->setKstsubjectData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Unable to find subject to save.')
        );
        $this->_redirect('*/*/');
    }
    public function checkSubject($row){
        if(strpos($row,"==")){
            $row = explode("==", $row);
        }else {
            $row = explode("\t", $row);
        }

        if(count($row) == 1){
            return $row[0];
        }elseif(count($row) > 1){
            $check = trim($row[1]);
            if($check == ''){
                return $row[0];
            }
        }

        return false;
    }

    /**
     * delete subject - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $kstsubject = Mage::getModel('bs_kst/kstsubject');
                $kstsubject->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Subject was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting subject.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Could not find subject to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete subject - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $kstsubjectIds = $this->getRequest()->getParam('kstsubject');
        if (!is_array($kstsubjectIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select subjects to delete.')
            );
        } else {
            try {
                foreach ($kstsubjectIds as $kstsubjectId) {
                    $kstsubject = Mage::getModel('bs_kst/kstsubject');
                    $kstsubject->setId($kstsubjectId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Total of %d subjects were successfully deleted.', count($kstsubjectIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting subjects.')
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
        $kstsubjectIds = $this->getRequest()->getParam('kstsubject');
        if (!is_array($kstsubjectIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select subjects.')
            );
        } else {
            try {
                foreach ($kstsubjectIds as $kstsubjectId) {
                $kstsubject = Mage::getSingleton('bs_kst/kstsubject')->load($kstsubjectId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d subjects were successfully updated.', count($kstsubjectIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating subjects.')
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
        $fileName   = 'kstsubject.csv';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstsubject_grid')
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
        $fileName   = 'kstsubject.xls';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstsubject_grid')
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
        $fileName   = 'kstsubject.xml';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstsubject_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_kst/kstsubject');
    }
}
