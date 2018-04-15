<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Function admin grid block
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Block_Adminhtml_Taskfunction_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('taskfunctionGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskfunction_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_tasktraining/taskfunction')
            ->getCollection();
        $collection->getSelect()->joinLeft(array('cat'=>'catalog_category_entity_varchar'),'category_id = cat.entity_id AND cat.attribute_id = 41','cat.value');
        $collection->getSelect()->joinLeft(array('ins'=>'bs_tasktraining_taskinstructor'),'instructor_id = ins.entity_id',array('name', 'vaeco_id'));

        $str = $collection->getSelect()->__toString();

        $collection->addFilterToMap('entity_id','main_table.entity_id');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskfunction_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_tasktraining')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );

        $this->addColumn(
            'category_id',
            array(
                'header'    => Mage::helper('bs_tasktraining')->__('Category'),
                'type'      => 'text',
                'renderer'  => 'bs_tasktraining/adminhtml_helper_column_renderer_cat',
                'filter_condition_callback' => array($this, '_catFilter'),

            )
        );
        $this->addColumn(
            'instructor_id',
            array(
                'header'    => Mage::helper('bs_tasktraining')->__('Instructor'),
                'type'      => 'text',
                'renderer'  => 'bs_tasktraining/adminhtml_helper_column_renderer_instructor',
                'filter_condition_callback' => array($this, '_instructorFilter'),

            )
        );
        $this->addColumn(
            'vaeco_id',
            array(
                'header'    => Mage::helper('bs_tasktraining')->__('VAECO ID'),
                'index'     => 'vaeco_id',
            )
        );
        $this->addColumn(
            'approved_course',
            array(
                'header'    => Mage::helper('bs_tasktraining')->__('Approved Course'),
                'align'     => 'left',
                'index'     => 'approved_course',
            )
        );
        $this->addColumn(
            'approved_function',
            array(
                'header' => Mage::helper('bs_tasktraining')->__('Approved Function'),
                'index'  => 'approved_function',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'approved_doc',
            array(
                'header' => Mage::helper('bs_tasktraining')->__('Approved Doc'),
                'index'  => 'approved_doc',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'approved_date',
            array(
                'header' => Mage::helper('bs_tasktraining')->__('Approved Date'),
                'type'  => 'date',
                'index'  => 'approved_date',

            )
        );
        $this->addColumn(
            'expire_date',
            array(
                'header' => Mage::helper('bs_tasktraining')->__('Expire Date'),
                'type'  => 'date',
                'index'  => 'expire_date',

            )
        );

        /*$this->addColumn(
            'is_ti',
            array(
                'header'  => Mage::helper('bs_tasktraining')->__('T/I'),
                'index'   => 'is_ti',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_tasktraining')->__('Yes'),
                    '0' => Mage::helper('bs_tasktraining')->__('No'),
                )
            )
        );
        $this->addColumn(
            'is_te',
            array(
                'header'  => Mage::helper('bs_tasktraining')->__('T/E'),
                'index'   => 'is_te',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_tasktraining')->__('Yes'),
                    '0' => Mage::helper('bs_tasktraining')->__('No'),
                )
            )
        );
        $this->addColumn(
            'is_pi',
            array(
                'header'  => Mage::helper('bs_tasktraining')->__('P/I'),
                'index'   => 'is_pi',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_tasktraining')->__('Yes'),
                    '0' => Mage::helper('bs_tasktraining')->__('No'),
                )
            )
        );
        $this->addColumn(
            'is_pe',
            array(
                'header'  => Mage::helper('bs_tasktraining')->__('P/E'),
                'index'   => 'is_pe',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_tasktraining')->__('Yes'),
                    '0' => Mage::helper('bs_tasktraining')->__('No'),
                )
            )
        );*/


        $this->addColumn(
            'note',
            array(
                'header' => Mage::helper('bs_tasktraining')->__('Note'),
                'index'  => 'note',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'is_new',
            array(
                'header'  => Mage::helper('bs_tasktraining')->__('Status'),
                'index'   => 'is_new',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_tasktraining')->__('NEW'),
                    '0' => Mage::helper('bs_tasktraining')->__('OLD'),
                )
            )
        );
        $this->addColumn(
            'update_function',
            array(
                'header'  => Mage::helper('bs_tasktraining')->__('Updated Function'),
                'index'   => 'update_function',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_tasktraining')->__('YES'),
                    '0' => Mage::helper('bs_tasktraining')->__('NO'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_tasktraining')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_tasktraining')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_tasktraining')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_tasktraining')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_tasktraining')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskfunction_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('taskfunction');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_tasktraining/taskfunction/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_tasktraining/taskfunction/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_tasktraining')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_tasktraining')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'approved_doc',
                array(
                    'label'      => Mage::helper('bs_tasktraining')->__('Update Approved Doc'),
                    'url'        => $this->getUrl('*/*/massApprovedDoc', array('_current'=>true)),
                    'additional' => array(
                        'approved_doc' => array(
                            'name'   => 'approved_doc',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_tasktraining')->__('Doc'),

                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'approved_date',
                array(
                    'label'      => Mage::helper('bs_tasktraining')->__('Update Approved Date'),
                    'url'        => $this->getUrl('*/*/massApprovedDate', array('_current'=>true)),
                    'additional' => array(
                        'approved_date' => array(
                            'name'   => 'approved_date',
                            'type'   => 'date',
                            'class'  => 'required-entry',
                            'image' => $this->getSkinUrl('images/grid-cal.gif'),
                            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                            'label'  => Mage::helper('bs_tasktraining')->__('Date'),

                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'expire_date',
                array(
                    'label'      => Mage::helper('bs_tasktraining')->__('Update Expire Date'),
                    'url'        => $this->getUrl('*/*/massExpireDate', array('_current'=>true)),
                    'additional' => array(
                        'expire_date' => array(
                            'name'   => 'expire_date',
                            'type'   => 'date',
                            'class'  => 'required-entry',
                            'image' => $this->getSkinUrl('images/grid-cal.gif'),
                            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                            'label'  => Mage::helper('bs_tasktraining')->__('Date'),

                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'is_new',
                array(
                    'label'      => Mage::helper('bs_tasktraining')->__('Change to OLD'),
                    'url'        => $this->getUrl('*/*/massChangeOld', array('_current'=>true)),
                    'additional' => array(
                        'is_new' => array(
                            'name'   => 'is_new',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_tasktraining')->__('Status'),
                            'values' => array(
                                '0' => Mage::helper('bs_tasktraining')->__('Old'),
                                //'0' => Mage::helper('bs_tasktraining')->__('Disabled'),
                            )
                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'update_function',
                array(
                    'label'      => Mage::helper('bs_tasktraining')->__('Update Function'),
                    'url'        => $this->getUrl('*/*/massUpdateFunction', array('_current'=>true)),
                    'additional' => array(
                        'update_function' => array(
                            'name'   => 'update_function',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_tasktraining')->__('Function'),

                        )
                    )
                )
            );





        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_Tasktraining_Model_Taskfunction
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskfunction_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _catFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "cat.value LIKE ?"
            , "%$value%");


        return $this;
    }

    /**
     * subject callback filter
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */

    protected function _instructorFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "name LIKE ?"
            , "%$value%");


        return $this;
    }
}
