<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum admin grid block
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Curriculum_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('curriculumGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Curriculum_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_traininglist/curriculum')
            ->getCollection()
            ->addAttributeToSelect('c_name')
            ->addAttributeToSelect('c_code')
            ->addAttributeToSelect('c_rev')
            ->addAttributeToSelect('c_aircraft')
            ->addAttributeToSelect('c_approved_date')
            ->addAttributeToSelect('c_compliance_with')
            ->addAttributeToSelect('c_duration')
            ->addAttributeToSelect('updated_at')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('c_history', 0);


        //$collection->getSelect()->joinLeft(array('tl'=>'bs_traininglist_curriculum_varchar'),'tl.entity_id = e.entity_id AND tl.attribute_id = 287','tl.value');

        //$collection->addFilterToMap('entity_id', 'e.entity_id');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Curriculum_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'c_name',
            array(
                'header'    => Mage::helper('bs_traininglist')->__('Name'),
                'align'     => 'left',
                'index'     => 'c_name',
                'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => '*/*/edit',
            )
        );
        $this->addColumn(
            'c_code',
            array(
                'header'    => Mage::helper('bs_traininglist')->__('Code'),
                'align'     => 'left',
                'index'     => 'c_code',
            )
        );
        $this->addColumn(
            'c_compliance_with',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Compliance'),
                'index'  => 'c_compliance_with',
                'type'  => 'text',
                'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_compliance',
                //'options' => Mage::helper('bs_traininglist')->convertOptions(
                //    Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', 'c_compliance_with')->getSource()->getAllOptions(false)
                //),
                'filter_condition_callback' => array($this, 'filterCompliance'),

            )
        );

        $this->addColumn(
            'c_aircraft',
            array(
                'header' => Mage::helper('bs_traininglist')->__('A/C'),
                'index'  => 'c_aircraft',
                'type'  => 'options',
                'options' => Mage::helper('bs_traininglist')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', 'c_aircraft')->getSource()->getAllOptions(false)
                )

            )
        );



		$this->addColumn(
            'category_list', array(
                'header'	=> Mage::helper('bs_traininglist')->__('Rating'),
                'index'		=> 'category_list',
                'sortable'	=> false,
                'width' => '250px',
                'type'  => 'options',
                'options'	=> Mage::getSingleton('bs_traininglist/curriculum_attribute_source_category')->toOptionArray(),
                'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_category',
                'filter_condition_callback' => array($this, 'filterCallback'),
            )
        );

        $this->addColumn(
            'c_duration',
            array(
                'header'    => Mage::helper('bs_traininglist')->__('Hours'),
                'index'     => 'c_duration',
                'type'      => 'number'
            )
        );

        $this->addColumn(
            'approved_pdf',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Approval Sheet'),
                'type'  => 'text',
                'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_approval',

            )
        );

        $this->addColumn(
            'c_approved_date',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Approved Date'),
                'index'  => 'c_approved_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'c_rev',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Revision'),
                'index'  => 'c_rev',
                'type'  => 'options',
                'options' => Mage::helper('bs_traininglist')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', 'c_rev')->getSource()->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'updated_at',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Updated At'),
                'index'  => 'updated_at',
                'type'=> 'datetime',

            )
        );



//        $this->addColumn(
//            'action',
//            array(
//                'header'  =>  Mage::helper('bs_traininglist')->__('Action'),
//                'width'   => '100',
//                'type'    => 'action',
//                'getter'  => 'getId',
//                'actions' => array(
//                    array(
//                        'caption' => Mage::helper('bs_traininglist')->__('Edit'),
//                        'url'     => array('base'=> '*/*/edit'),
//                        'field'   => 'id'
//                    )
//                ),
//                'filter'    => false,
//                'is_system' => true,
//                'sortable'  => false,
//            )
//        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_traininglist')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_traininglist')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_traininglist')->__('XML'));
        return parent::_prepareColumns();
    }

    public  function filterCallback( $collection, $column) {

        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->joinField('category_id', 'bs_traininglist/curriculum_category', 'category_id', 'curriculum_id = entity_id', '{{table}}.category_id='.$column->getFilter()->getValue(), 'inner');
    }

    protected function filterCompliance($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $values = array($value);

        if(strpos($value, ",")){
            $values = explode(",", $value);
            $values = array_map('trim', $values);

        }

        $result = array();
        foreach ($values as $item) {
            if(strtolower($item) == 'mtoe'){
                $result[197] = 1;
            }elseif(strtolower($item) == 'amotp'){
                $result[198] = 1;
            }elseif(strtolower($item) == 'rstp'){
                $result[199] = 1;
            }else {
                $result[200] = 1;
            }
        }
        $result = array_keys($result);



        $this->getCollection()->addFieldToFilter('c_compliance_with', array('in' => $result));
    }


    public function getCategoryOptions() {
        $option_array=array();
        $rootCategoryId = Mage::app()->getStore()->getRootCategoryId();
        $category_collection=Mage::getModel('catalog/category')->getCollection()->addNameToResult()->addAttributeToSort('position', 'asc');

        foreach ($category_collection as $category)
            if ($category->getId() >1 && $category->getName()!='Root')
                $option_array[$category->getId()]=$category->getName();

        return $option_array;
    }
    /**
     * get the selected store
     *
     * @access protected
     * @return Mage_Core_Model_Store
     * @author Bui Phong
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Curriculum_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('curriculum');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/curriculum/curriculum/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/curriculum/curriculum/delete");

        if($isAllowedDelete){
//            $this->getMassactionBlock()->addItem(
//                'delete',
//                array(
//                    'label'=> Mage::helper('bs_traininglist')->__('Delete'),
//                    'url'  => $this->getUrl('*/*/massDelete'),
//                    'confirm'  => Mage::helper('bs_traininglist')->__('Are you sure?')
//                )
//            );
        }

        $this->getMassactionBlock()->addItem(
            'getinfo',
            array(
                'label'      => Mage::helper('bs_traininglist')->__('Get Name & Code'),
                'url'        => $this->getUrl('*/*/massGetInfo', array('_current'=>true)),

            )
        );


        $this->getMassactionBlock()->addItem(
            'generate',
            array(
                'label'      => Mage::helper('bs_traininglist')->__('Generate Files'),
                'url'        => $this->getUrl('*/*/massGenerate', array('_current'=>true)),
                'additional' => array(
                        'compress' => array(
                            'name'   => 'compress',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('Compress?'),
                            'values' => array(
                                '1' => Mage::helper('bs_traininglist')->__('Yes'),
                                '0' => Mage::helper('bs_traininglist')->__('No'),
                            )
                        )
                    )

            )
        );

        $this->getMassactionBlock()->addItem(
            'generate_fifteen',
            array(
                'label'      => Mage::helper('bs_traininglist')->__('Generate 8015'),
                'url'        => $this->getUrl('*/*/massGenerateFifteen', array('_current'=>true)),
                'additional' => array(
                    'compress' => array(
                        'name'   => 'compress',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_traininglist')->__('Compress?'),
                        'values' => array(
                            '1' => Mage::helper('bs_traininglist')->__('Yes'),
                            '0' => Mage::helper('bs_traininglist')->__('No'),
                        )
                    )
                )

            )
        );

        $this->getMassactionBlock()->addItem(
            'generate_sixteen',
            array(
                'label'      => Mage::helper('bs_traininglist')->__('Generate 8016'),
                'url'        => $this->getUrl('*/*/massGenerateSixteen', array('_current'=>true)),
                'additional' => array(
                    'compress' => array(
                        'name'   => 'compress',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_traininglist')->__('Compress?'),
                        'values' => array(
                            '1' => Mage::helper('bs_traininglist')->__('Yes'),
                            '0' => Mage::helper('bs_traininglist')->__('No'),
                        )
                    )
                )

            )
        );

        $this->getMassactionBlock()->addItem(
            'generate_twenty',
            array(
                'label'      => Mage::helper('bs_traininglist')->__('Generate 8020'),
                'url'        => $this->getUrl('*/*/massGenerateTwenty', array('_current'=>true)),
                'additional' => array(
                    'compress' => array(
                        'name'   => 'compress',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_traininglist')->__('Compress?'),
                        'values' => array(
                            '1' => Mage::helper('bs_traininglist')->__('Yes'),
                            '0' => Mage::helper('bs_traininglist')->__('No'),
                        )
                    )
                )

            )
        );
        $this->getMassactionBlock()->addItem(
            'generate_thirty',
            array(
                'label'      => Mage::helper('bs_traininglist')->__('Generate 8030'),
                'url'        => $this->getUrl('*/*/massGenerateThirty', array('_current'=>true)),
                'additional' => array(
                    'compress' => array(
                        'name'   => 'compress',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_traininglist')->__('Compress?'),
                        'values' => array(
                            '1' => Mage::helper('bs_traininglist')->__('Yes'),
                            '0' => Mage::helper('bs_traininglist')->__('No'),
                        )
                    )
                )


            )
        );


        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'replace_title',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Replace Title'),
                    'url'        => $this->getUrl('*/*/massReplacetitle', array('_current'=>true)),
                    'additional' => array(
                        'replace_title' => array(
                            'name'   => 'replace_title',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('Text | Replace'),
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'replace_code',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Replace Code'),
                    'url'        => $this->getUrl('*/*/massReplacecode', array('_current'=>true)),
                    'additional' => array(
                        'replace_code' => array(
                            'name'   => 'replace_code',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('Text | Replace'),
                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'title_lowercase',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Make Title lowercase'),
                    'url'        => $this->getUrl('*/*/massMakeLowercase', array('_current'=>true)),

                )
            );



            $this->getMassactionBlock()->addItem(
                'applicable',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Change A/C'),
                    'url'        => $this->getUrl('*/*/massReplaceac', array('_current'=>true)),
                    'additional' => array(
                        'applicable' => array(
                            'name'   => 'applicable',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('A/C'),
                            'values' => Mage::helper('bs_traininglist')->convertOptions(
                                Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', 'c_aircraft')->getSource()->getAllOptions(false)
                            )
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'purpose',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Change Purpose'),
                    'url'        => $this->getUrl('*/*/massReplacepurpose', array('_current'=>true)),
                    'additional' => array(
                        'purpose' => array(
                            'name'   => 'purpose',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('Purpose'),
                            'values' => Mage::helper('bs_traininglist')->convertOptions(
                                Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', 'c_purpose')->getSource()->getAllOptions(false)
                            )
                        )
                    )
                )
            );


            $this->getMassactionBlock()->addItem(
                'c_new_staff',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Change New staff'),
                    'url'        => $this->getUrl('*/*/massUpdateNewstaff', array('_current'=>true)),
                    'additional' => array(
                        'c_new_staff' => array(
                            'name'   => 'c_new_staff',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('For New staff?'),
                            'values' => array(
                                '1' => Mage::helper('bs_traininglist')->__('Yes'),
                                '0' => Mage::helper('bs_traininglist')->__('No'),
                            )
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'c_mandatory',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Change Mandatory'),
                    'url'        => $this->getUrl('*/*/massUpdateMandatory', array('_current'=>true)),
                    'additional' => array(
                        'c_mandatory' => array(
                            'name'   => 'c_mandatory',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('Mandatory?'),
                            'values' => array(
                                '1' => Mage::helper('bs_traininglist')->__('Yes'),
                                '0' => Mage::helper('bs_traininglist')->__('No'),
                            )
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'c_recurrent',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Change Recurrent'),
                    'url'        => $this->getUrl('*/*/massUpdateRecurrent', array('_current'=>true)),
                    'additional' => array(
                        'c_recurrent' => array(
                            'name'   => 'c_recurrent',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('Recurrent?'),
                            'values' => array(
                                '1' => Mage::helper('bs_traininglist')->__('Yes'),
                                '0' => Mage::helper('bs_traininglist')->__('No'),
                            )
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'job_specific',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Change Job Specific'),
                    'url'        => $this->getUrl('*/*/massUpdateJobspecific', array('_current'=>true)),
                    'additional' => array(
                        'job_specific' => array(
                            'name'   => 'job_specific',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('Job Specific'),
                            'values' => array(
                                '1' => Mage::helper('bs_traininglist')->__('Yes'),
                                '0' => Mage::helper('bs_traininglist')->__('No'),
                            )
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'compliance',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Change Compliance'),
                    'url'        => $this->getUrl('*/*/massReplacecompliance', array('_current'=>true)),
                    'additional' => array(
                        'compliance' => array(
                            'name'   => 'compliance',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('197-MTOE,198-AMOTP,199-RSTP,200-Others'),
                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'docwise',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Update Docwise Custom'),
                    'url'        => $this->getUrl('*/*/massUpdateDocwise', array('_current'=>true)),
                    'additional' => array(
                        'docwise' => array(
                            'name'   => 'docwise',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('Text'),
                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'update_position',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Update Subject Position/Shortcode'),
                    'url'        => $this->getUrl('*/*/massUpdateposition', array('_current'=>true)),
                    'additional' => array(
                        'update_position' => array(
                            'name'   => 'update_position',
                            'type'   => 'text',
                            'label'  => Mage::helper('bs_traininglist')->__('Regex: txt1|rep1,txt2|rep2'),
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'revision',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Change Revision'),
                    'url'        => $this->getUrl('*/*/massUpdateRevision', array('_current'=>true)),
                    'additional' => array(
                        'revision' => array(
                            'name'   => 'revision',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('Revision'),
                            'values' => Mage::helper('bs_traininglist')->convertOptions(
                                Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', 'c_rev')->getSource()->getAllOptions(false)
                            )
                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'c_approved_date',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Update Approved Date'),
                    'url'        => $this->getUrl('*/*/massApprovedDate', array('_current'=>true)),
                    'additional' => array(
                        'c_approved_date' => array(
                            'name'   => 'c_approved_date',
                            'type'   => 'date',
                            'class'  => 'required-entry',
                            'image' => $this->getSkinUrl('images/grid-cal.gif'),
                            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                            'label'  => Mage::helper('bs_traininglist')->__('Date'),

                        )
                    )
                )
            );


            $this->getMassactionBlock()->addItem(
                'c_manual_date',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Update Manual Date'),
                    'url'        => $this->getUrl('*/*/massManualDate', array('_current'=>true)),
                    'additional' => array(
                        'c_manual_date' => array(
                            'name'   => 'c_manual_date',
                            'type'   => 'date',
                            'class'  => 'required-entry',
                            'image' => $this->getSkinUrl('images/grid-cal.gif'),
                            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                            'label'  => Mage::helper('bs_traininglist')->__('Date'),

                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'c_manual_rev',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Update Manual Revision'),
                    'url'        => $this->getUrl('*/*/massManualRevision', array('_current'=>true)),
                    'additional' => array(
                        'c_manual_rev' => array(
                            'name'   => 'c_manual_rev',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('Revision'),
                            'values' => Mage::helper('bs_traininglist')->convertOptions(
                                Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', 'c_manual_rev')->getSource()->getAllOptions(false)
                            )
                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_traininglist')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traininglist')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_traininglist')->__('Enabled'),
                                '0' => Mage::helper('bs_traininglist')->__('Disabled'),
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
     * @param BS_Traininglist_Model_Curriculum
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


}
