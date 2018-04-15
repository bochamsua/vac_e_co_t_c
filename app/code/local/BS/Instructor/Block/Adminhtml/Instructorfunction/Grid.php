<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Function admin grid block
 *
 * @category    BS
 * @package     BS_Instructor
 * @author Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructorfunction_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('instructorfunctionGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructorfunction_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_instructor/instructorfunction')
            ->getCollection();

        $collection->getSelect()->joinLeft(array('cat'=>'catalog_category_entity_varchar'),'category_id = cat.entity_id AND cat.attribute_id = 41','cat.value');
        $collection->getSelect()->joinLeft(array('ins'=>'bs_instructor_instructor_varchar'),'instructor_id = ins.entity_id AND ins.attribute_id = 270','ins.value');

        $collection->getSelect()->distinct();
        $collection->addFilterToMap('entity_id','main_table.entity_id');

        //$str = $collection->getSelect()->__toString();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructorfunction_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_instructor')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'category_id',
            array(
                'header'    => Mage::helper('bs_instructor')->__('Category'),
                'type'      => 'text',
                'renderer'  => 'bs_instructor/adminhtml_helper_column_renderer_cat',
                'filter_condition_callback' => array($this, '_catFilter'),

            )
        );
        $this->addColumn(
            'instructor_id',
            array(
                'header'    => Mage::helper('bs_instructor')->__('Instructor'),
                'type'      => 'text',
                'renderer'  => 'bs_instructor/adminhtml_helper_column_renderer_ins',
                'filter_condition_callback' => array($this, '_instructorFilter'),

            )
        );


        $this->addColumn(
            'approved_course',
            array(
                'header'    => Mage::helper('bs_instructor')->__('Approved Course'),
                'align'     => 'left',
                'index'     => 'approved_course',
            )
        );
        $this->addColumn(
            'approved_function',
            array(
                'header' => Mage::helper('bs_instructor')->__('Approved Function'),
                'index'  => 'approved_function',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'approved_doc',
            array(
                'header' => Mage::helper('bs_instructor')->__('Approved Doc'),
                'index'  => 'approved_doc',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'approved_date',
            array(
                'header' => Mage::helper('bs_instructor')->__('Approved Date'),
                'type'  => 'date',
                'index'  => 'approved_date',

            )
        );
        $this->addColumn(
            'expire_date',
            array(
                'header' => Mage::helper('bs_instructor')->__('Expire Date'),
                'type'  => 'date',
                'index'  => 'expire_date',

            )
        );

        $this->addColumn(
            'is_ti',
            array(
                'header'  => Mage::helper('bs_instructor')->__('T/I'),
                'index'   => 'is_ti',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_instructor')->__('Yes'),
                    '0' => Mage::helper('bs_instructor')->__('No'),
                )
            )
        );
        $this->addColumn(
            'is_te',
            array(
                'header'  => Mage::helper('bs_instructor')->__('T/E'),
                'index'   => 'is_te',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_instructor')->__('Yes'),
                    '0' => Mage::helper('bs_instructor')->__('No'),
                )
            )
        );
        $this->addColumn(
            'is_pi',
            array(
                'header'  => Mage::helper('bs_instructor')->__('P/I'),
                'index'   => 'is_pi',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_instructor')->__('Yes'),
                    '0' => Mage::helper('bs_instructor')->__('No'),
                )
            )
        );
        $this->addColumn(
            'is_pe',
            array(
                'header'  => Mage::helper('bs_instructor')->__('P/E'),
                'index'   => 'is_pe',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_instructor')->__('Yes'),
                    '0' => Mage::helper('bs_instructor')->__('No'),
                )
            )
        );

        $this->addColumn(
            'note',
            array(
                'header' => Mage::helper('bs_instructor')->__('Note'),
                'index'  => 'note',
                'type'=> 'text',

            )
        );


        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_instructor')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_instructor')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_instructor')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_instructor')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_instructor')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructorfunction_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('instructorfunction');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/instructorfunction/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/instructorfunction/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_instructor')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_instructor')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'approved_doc',
                array(
                    'label'      => Mage::helper('bs_instructor')->__('Update Approved Doc'),
                    'url'        => $this->getUrl('*/*/massApprovedDoc', array('_current'=>true)),
                    'additional' => array(
                        'approved_doc' => array(
                            'name'   => 'approved_doc',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_instructor')->__('Doc'),

                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'approved_date',
                array(
                    'label'      => Mage::helper('bs_instructor')->__('Update Approved Date'),
                    'url'        => $this->getUrl('*/*/massApprovedDate', array('_current'=>true)),
                    'additional' => array(
                        'approved_date' => array(
                            'name'   => 'approved_date',
                            'type'   => 'date',
                            'class'  => 'required-entry',
                            'image' => $this->getSkinUrl('images/grid-cal.gif'),
                            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                            'label'  => Mage::helper('bs_instructor')->__('Date'),

                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'expire_date',
                array(
                    'label'      => Mage::helper('bs_instructor')->__('Update Expire Date'),
                    'url'        => $this->getUrl('*/*/massExpireDate', array('_current'=>true)),
                    'additional' => array(
                        'expire_date' => array(
                            'name'   => 'expire_date',
                            'type'   => 'date',
                            'class'  => 'required-entry',
                            'image' => $this->getSkinUrl('images/grid-cal.gif'),
                            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                            'label'  => Mage::helper('bs_instructor')->__('Date'),

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
     * @param BS_Instructor_Model_Instructorfunction
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
     * @return BS_Instructor_Block_Adminhtml_Instructorfunction_Grid
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
            "ins.value LIKE ?"
            , "%$value%");


        return $this;
    }
}
