<?php
/**
 * BS_SubjectCopy extension
 * 
 * @category       BS
 * @package        BS_SubjectCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Copy admin grid block
 *
 * @category    BS
 * @package     BS_SubjectCopy
 * @author Bui Phong
 */
class BS_SubjectCopy_Block_Adminhtml_Subjectcopy_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('subjectcopyGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_SubjectCopy_Block_Adminhtml_Subjectcopy_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_subjectcopy/subjectcopy')
            ->getCollection();
        $collection->getSelect()->joinLeft(array('cu'=>'bs_traininglist_curriculum_varchar'),'c_from = cu.entity_id AND cu.attribute_id = 189','cu.value');
        $collection->getSelect()->joinLeft(array('cus'=>'bs_traininglist_curriculum_varchar'),'c_to = cus.entity_id AND cus.attribute_id = 189','cus.value');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_SubjectCopy_Block_Adminhtml_Subjectcopy_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_subjectcopy')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );

        $this->addColumn(
            'c_from',
            array(
                'header'    => Mage::helper('bs_subjectcopy')->__('Copy from Curriculum'),
                'type'      => 'text',
                'renderer'  => 'bs_subjectcopy/adminhtml_helper_column_renderer_cfrom',
                'filter_condition_callback' => array($this, '_cfromFilter'),

            )
        );


        

        $this->addColumn(
            'c_to',
            array(
                'header'    => Mage::helper('bs_subjectcopy')->__('Copy to Curriculum'),
                'type'      => 'text',
                'renderer'  => 'bs_subjectcopy/adminhtml_helper_column_renderer_cto',
                'filter_condition_callback' => array($this, '_ctoFilter'),

            )
        );
        $this->addColumn(
            'include_sub',
            array(
                'header' => Mage::helper('bs_subjectcopy')->__('Include Subcontent'),
                'index'  => 'include_sub',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('bs_subjectcopy')->__('Yes'),
                    '0' => Mage::helper('bs_subjectcopy')->__('No'),
                )

            )
        );
        $this->addColumn(
            'replace_all',
            array(
                'header' => Mage::helper('bs_subjectcopy')->__('Replace All Existing Subjects'),
                'index'  => 'replace_all',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('bs_subjectcopy')->__('Yes'),
                    '0' => Mage::helper('bs_subjectcopy')->__('No'),
                )

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_subjectcopy')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_subjectcopy')->__('Enabled'),
                    '0' => Mage::helper('bs_subjectcopy')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_subjectcopy')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_subjectcopy')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_subjectcopy')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_subjectcopy')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_subjectcopy')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _cfromFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "cu.value LIKE ?"
            , "%$value%");


        return $this;
    }

    protected function _ctoFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "cus.value LIKE ?"
            , "%$value%");


        return $this;
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_SubjectCopy_Block_Adminhtml_Subjectcopy_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('subjectcopy');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/subject/subjectcopy/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/subject/subjectcopy/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_subjectcopy')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_subjectcopy')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_subjectcopy')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_subjectcopy')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_subjectcopy')->__('Enabled'),
                                '0' => Mage::helper('bs_subjectcopy')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'include_sub',
            array(
                'label'      => Mage::helper('bs_subjectcopy')->__('Change Include Subcontent'),
                'url'        => $this->getUrl('*/*/massIncludeSub', array('_current'=>true)),
                'additional' => array(
                    'flag_include_sub' => array(
                        'name'   => 'flag_include_sub',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_subjectcopy')->__('Include Subcontent'),
                        'values' => array(
                                '1' => Mage::helper('bs_subjectcopy')->__('Yes'),
                                '0' => Mage::helper('bs_subjectcopy')->__('No'),
                            )

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'replace_all',
            array(
                'label'      => Mage::helper('bs_subjectcopy')->__('Change Replace All Existing Subjects'),
                'url'        => $this->getUrl('*/*/massReplaceAll', array('_current'=>true)),
                'additional' => array(
                    'flag_replace_all' => array(
                        'name'   => 'flag_replace_all',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_subjectcopy')->__('Replace All Existing Subjects'),
                        'values' => array(
                                '1' => Mage::helper('bs_subjectcopy')->__('Yes'),
                                '0' => Mage::helper('bs_subjectcopy')->__('No'),
                            )

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
     * @param BS_SubjectCopy_Model_Subjectcopy
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
     * @return BS_SubjectCopy_Block_Adminhtml_Subjectcopy_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
