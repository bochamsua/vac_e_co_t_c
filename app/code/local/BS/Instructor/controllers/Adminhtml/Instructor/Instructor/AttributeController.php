<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin attribute controller
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Adminhtml_Instructor_Instructor_AttributeController extends Mage_Adminhtml_Controller_Action
{
    protected $_entityTypeId;

    /**
     * predispatch
     *
     * @accees public
     * @return void
     * @author Bui Phong
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_entityTypeId = Mage::getModel('eav/entity')
            ->setType(BS_Instructor_Model_Instructor::ENTITY)
            ->getTypeId();
    }

    /**
     * init action
     *
     * @accees protected
     * @return BS_Instructor_Adminhtml_Instructor_AttributeController
     * @author Bui Phong
     */
    protected function _initAction()
    {
        $this->_title(Mage::helper('bs_instructor')->__('Instructor'))
             ->_title(Mage::helper('bs_instructor')->__('Attributes'))
             ->_title(Mage::helper('bs_instructor')->__('Manage Attributes'));

        $this->loadLayout()
            ->_setActiveMenu('bs_traininglist/bs_instructor/instructor_attributes')
            ->_addBreadcrumb(
                Mage::helper('bs_instructor')->__('Instructor'),
                Mage::helper('bs_instructor')->__('Instructor')
            )
            ->_addBreadcrumb(
                Mage::helper('bs_instructor')->__('Manage Instructor Attributes'),
                Mage::helper('bs_instructor')->__('Manage Instructor Attributes')
            );
        return $this;
    }

    /**
     * default action
     *
     * @accees public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    /**
     * add attribute action
     *
     * @accees public
     * @return void
     * @author Bui Phong
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * edit attribute action
     *
     * @accees public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('attribute_id');
        $model = Mage::getModel('bs_instructor/resource_eav_attribute')
            ->setEntityTypeId($this->_entityTypeId);
        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructor')->__('This instructor attribute no longer exists')
                );
                $this->_redirect('*/*/');
                return;
            }
            // entity type check
            if ($model->getEntityTypeId() != $this->_entityTypeId) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructor')->__('This instructor attribute cannot be edited.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getAttributeData(true);
        if (! empty($data)) {
            $model->addData($data);
        }
        Mage::register('entity_attribute', $model);
        $this->_initAction();
        $this->_title($id ? $model->getName() : Mage::helper('bs_instructor')->__('New Instructor Attribute'));
        $item = $id ? Mage::helper('bs_instructor')->__('Edit Instructor Attribute')
                    : Mage::helper('bs_instructor')->__('New Instructor Attribute');
        $this->_addBreadcrumb($item, $item);
        $this->renderLayout();
    }

    /**
     * validate attribute action
     *
     * @accees public
     * @return void
     * @author Bui Phong
     */
    public function validateAction()
    {
        $response = new Varien_Object();
        $response->setError(false);

        $attributeCode  = $this->getRequest()->getParam('attribute_code');
        $attributeId    = $this->getRequest()->getParam('attribute_id');
        $attribute      = Mage::getModel('bs_instructor/attribute')
            ->loadByCode($this->_entityTypeId, $attributeCode);
        if ($attribute->getId() && !$attributeId) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructor')->__('Attribute with the same code already exists')
            );
            $this->_initLayoutMessages('adminhtml/session');
            $response->setError(true);
            $response->setMessage($this->getLayout()->getMessagesBlock()->getGroupedHtml());
        }
        $this->getResponse()->setBody($response->toJson());
    }

    /**
     * Filter post data
     *
     * @access protected
     * @param array $data
     * @return array
     * @author Bui Phong
     */
    protected function _filterPostData($data)
    {
        if ($data) {
            $helper = Mage::helper('bs_instructor');
            //labels
            foreach ($data['frontend_label'] as & $value) {
                if ($value) {
                    $value = $helper->stripTags($value);
                }
            }
            //options
            if (!empty($data['option']['value'])) {
                foreach ($data['option']['value'] as &$options) {
                    foreach ($options as &$label) {
                        $label = $helper->stripTags($label);
                    }
                }
            }
            //default value
            if (!empty($data['default_value'])) {
                $data['default_value'] = $helper->stripTags($data['default_value']);
            }
            if (!empty($data['default_value_text'])) {
                $data['default_value_text'] = $helper->stripTags($data['default_value_text']);
            }
            if (!empty($data['default_value_textarea'])) {
                $data['default_value_textarea'] = $helper->stripTags($data['default_value_textarea']);
            }
        }
        return $data;
    }

    /**
     * save attribute action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        if ($data) {
            $isNew = false;
            $session      = Mage::getSingleton('adminhtml/session');
            $redirectBack = $this->getRequest()->getParam('back', false);
            $model        = Mage::getModel('bs_instructor/resource_eav_attribute');
            $helper       = Mage::helper('bs_instructor/instructor');
            $id           = $this->getRequest()->getParam('attribute_id');
            //validate attribute_code
            if (isset($data['attribute_code'])) {
                $validatorAttrCode = new Zend_Validate_Regex(array('pattern' => '/^[a-z_0-9]{1,255}$/'));
                if (!$validatorAttrCode->isValid($data['attribute_code'])) {
                    $session->addError(
                        Mage::helper('bs_instructor')->__(
                            'Attribute code is invalid. Please use only letters (a-z), numbers (0-9) or underscore(_) in this field, first character should be a letter.'
                        )
                    );
                    $this->_redirect('*/*/edit', array('attribute_id' => $id, '_current' => true));
                    return;
                }
            }
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $session->addError(
                        Mage::helper('bs_instructor')->__('This attribute no longer exists')
                    );
                    $this->_redirect('*/*/');
                    return;
                }

                // entity type check
                if ($model->getEntityTypeId() != $this->_entityTypeId) {
                    $session->addError(
                        Mage::helper('bs_instructor')->__('This attribute cannot be updated.')
                    );
                    $session->setAttributeData($data);
                    $this->_redirect('*/*/');
                    return;
                }

                $data['attribute_code']  = $model->getAttributeCode();
                $data['is_user_defined'] = $model->getIsUserDefined();
                $data['frontend_input']  = $model->getFrontendInput();
            } else {
                $data['source_model']  = $helper->getAttributeSourceModelByInputType($data['frontend_input']);
                $data['backend_model'] = $helper->getAttributeBackendModelByInputType($data['frontend_input']);
                $isNew = true;
            }

            if (is_null($model->getIsUserDefined()) || $model->getIsUserDefined() != 0) {
                $data['backend_type'] = $model->getBackendTypeByInput($data['frontend_input']);
            }
            $defaultValueField = $model->getDefaultValueByInput($data['frontend_input']);
            if ($defaultValueField) {
                $data['default_value'] = $this->getRequest()->getParam($defaultValueField);
            }
            //filter
            $data = $this->_filterPostData($data);
            $model->addData($data);
            if (!$id) {
                $model->setEntityTypeId($this->_entityTypeId);
                $model->setIsUserDefined(1);
                $model->setIsVisible(1);
            }
            try {
                $model->save();

                $msg = '';

                if($data['frontend_input'] == 'boolean' && $isNew){
                    $resource = Mage::getSingleton('core/resource');
                    $writeConnection = $resource->getConnection('core_write');
                    $eavTable = $resource->getTableName('eav/attribute');

                    $sql = "UPDATE {$eavTable} SET source_model = 'eav/entity_attribute_source_boolean', frontend_input = 'select' WHERE attribute_code = '{$data['attribute_code']}'";

                    $res = $writeConnection->query($sql);

                    if($res){
                        $msg = ' And updated.';
                    }

                }
                $session->addSuccess(
                    Mage::helper('bs_instructor')->__('The instructor attribute has been saved.'.$msg)
                );
                /**
                 * Clear translation cache because attribute labels are stored in translation
                 */
                Mage::app()->cleanCache(array(Mage_Core_Model_Translate::CACHE_TAG));
                $session->setAttributeData(false);
                if ($redirectBack) {
                    $this->_redirect('*/*/edit', array('attribute_id' => $model->getId(), '_current'=>true));
                } else {
                    $this->_redirect('*/*/', array());
                }
                return;
            } catch (Exception $e) {
                $session->addError($e->getMessage());
                $session->setAttributeData($data);
                $this->_redirect('*/*/edit', array('attribute_id' => $id, '_current' => true));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * delete attribute action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('attribute_id')) {
            $model = Mage::getModel('bs_instructor/resource_eav_attribute');
            // entity type check
            $model->load($id);
            if ($model->getEntityTypeId() != $this->_entityTypeId) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructor')->__('This attribute cannot be deleted.')
                );
                $this->_redirect('*/*/');
                return;
            }
            try {
                $model->delete();

                $resource = Mage::getSingleton('core/resource');
                $writeConnection = $resource->getConnection('core_write');
                $eavTable = $resource->getTableName('bs_instructor/eav_attribute');

                $sql = "DELETE FROM {$eavTable} WHERE attribute_id = {$id}";

                $res = $writeConnection->query($sql);

                $msg = '';
                if($res){
                    $msg = ' And updated.';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructor')->__('The instructor attribute has been deleted.'.$msg)
                );
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('attribute_id' => $this->getRequest()->getParam('attribute_id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_instructor')->__('Unable to find an attribute to delete.')
        );
        $this->_redirect('*/*/');
    }

    public function massPositionAction()
    {
        $attributeIds = (array)$this->getRequest()->getParam('attributes');
        $position = (int)$this->getRequest()->getParam('position');

        try {
            foreach ($attributeIds as $attributeId) {

                $model = Mage::getModel('bs_instructor/resource_eav_attribute');
                $model->load($attributeId);
                $model->setPosition($position);
                $model->save();

            }
            $this->_getSession()->addSuccess(
                Mage::helper('bs_instructor')->__('Total of %d record(s) have been updated.', count($attributeIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_instructor')->__('An error occurred while updating the attribute.')
            );
        }

        $this->_redirect('*/*/');
    }

    /**
     * check access
     *
     * @access protected
     * @return bool
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/instructor_attributes');
    }
}
