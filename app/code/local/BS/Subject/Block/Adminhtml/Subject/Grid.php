<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject admin grid block
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Block_Adminhtml_Subject_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('subjectGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subject_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_subject/subject')
            ->getCollection();
        $collection->getSelect()->joinLeft(array('cu'=>'bs_traininglist_curriculum_varchar'),'curriculum_id = cu.entity_id AND attribute_id = 225','cu.value');

        $collection->addFilterToMap('entity_id','main_table.entity_id');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subject_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_subject')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'curriculum_id',
            array(
                'header'    => Mage::helper('bs_subject')->__('Curriculum'),
                'type'      => 'text',
                'renderer'  => 'bs_subject/adminhtml_helper_column_renderer_curriculum',
                'filter_condition_callback' => array($this, '_curriculumFilter'),

            )
        );

        $this->addColumn(
            'subject_name',
            array(
                'header'    => Mage::helper('bs_subject')->__('Subject Name'),
                'align'     => 'left',
                'index'     => 'subject_name',
            )
        );
        

        $this->addColumn(
            'subject_code',
            array(
                'header' => Mage::helper('bs_subject')->__('Code'),
                'index'  => 'subject_code',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'subject_level',
            array(
                'header' => Mage::helper('bs_subject')->__('Level'),
                'index'  => 'subject_level',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'subject_hour',
            array(
                'header' => Mage::helper('bs_subject')->__('Hour'),
                'index'  => 'subject_hour',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'subject_order',
            array(
                'header' => Mage::helper('bs_subject')->__('Order'),
                'index'  => 'subject_order',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'require_exam',
            array(
                'header'  => Mage::helper('bs_subject')->__('Require exam?'),
                'index'   => 'require_exam',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_subject')->__('Yes'),
                    '0' => Mage::helper('bs_subject')->__('No'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_subject')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_subject')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_subject')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_subject')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_subject')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _curriculumFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "cu.value LIKE ?"
            , "%$value%");


        return $this;
    }


    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subject_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('subject');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/subject/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/subject/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_subject')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_subject')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'replace_title',
                array(
                    'label'      => Mage::helper('bs_subject')->__('Replace Title'),
                    'url'        => $this->getUrl('*/*/massReplaceTitle', array('_current'=>true)),
                    'additional' => array(
                        'replace_title' => array(
                            'name'   => 'replace_title',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_subject')->__('Text | Replace'),
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'replace_content',
                array(
                    'label'      => Mage::helper('bs_subject')->__('Replace Content'),
                    'url'        => $this->getUrl('*/*/massReplaceContent', array('_current'=>true)),
                    'additional' => array(
                        'replace_title' => array(
                            'name'   => 'replace_content',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_subject')->__('Text | Replace'),
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_subject')->__('Change Subject Type'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_subject')->__('Is real subject?'),
                            'values' => array(
                                '1' => Mage::helper('bs_subject')->__('Yes'),
                                '0' => Mage::helper('bs_subject')->__('No'),
                            )
                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'require_exam',
                array(
                    'label'      => Mage::helper('bs_subject')->__('Change Subject Exam'),
                    'url'        => $this->getUrl('*/*/massExam', array('_current'=>true)),
                    'additional' => array(
                        'require_exam' => array(
                            'name'   => 'require_exam',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_subject')->__('Require Exam'),
                            'values' => array(
                                '1' => Mage::helper('bs_subject')->__('Yes'),
                                '0' => Mage::helper('bs_subject')->__('No'),
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
     * @param BS_Subject_Model_Subject
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
     * @return BS_Subject_Block_Adminhtml_Subject_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
