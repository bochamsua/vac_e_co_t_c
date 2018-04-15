<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Member edit form tab
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstmember_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstmember_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('kstmember_');
        $form->setFieldNameSuffix('kstmember');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'kstmember_form',
            array('legend' => Mage::helper('bs_kst')->__('Member'))
        );

        $values = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('status',1)
            ->addAttributeToFilter('sku',array(array('like'=>'%KST%'), array('like'=>'%CRS%')))
            ->addAttributeToFilter('course_report',0);




        $values = $values->toSkuOptionArray();

        $fieldset->addField(
            'course_id',
            'select',
            array(
                'label'     => Mage::helper('bs_kst')->__('Course'),
                'name'      => 'course_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
            )
        );

        $values = Mage::getResourceModel('bs_kst/kstgroup_collection')
            ->toOptionArray();




        $fieldset->addField(
            'kstgroup_id',
            'select',
            array(
                'label'     => Mage::helper('bs_kst')->__('Group'),
                'name'      => 'kstgroup_id',
                'required'  => true,
                'values'    => $values,
            )
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Name'),
                'name'  => 'name',

           )
        );

        $fieldset->addField(
            'vaeco_id',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Vaeco ID'),
                'name'  => 'vaeco_id',
                'note'  => 'Enter VAECO ID then press ENTER'

           )
        );

        $fieldset->addField(
            'username',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Username'),
                'name'  => 'username',

           )
        );

        $fieldset->addField(
            'is_leader',
            'select',
            array(
                'label' => Mage::helper('bs_kst')->__('Leader'),
                'name'  => 'is_leader',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('bs_kst')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('bs_kst')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'import',
            'textarea',
            array(
                'label' => Mage::helper('bs_kst')->__('OR import from this?'),
                'name'  => 'import',
                'note'  => Mage::helper('bs_kst')->__('Put VAECO IDs. One value on each row'),

            )
        );
        /*
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_kst')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_kst')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_kst')->__('Disabled'),
                    ),
                ),
            )
        );*/
        $formValues = Mage::registry('current_kstmember')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getKstmemberData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getKstmemberData());
            Mage::getSingleton('adminhtml/session')->setKstmemberData(null);
        } elseif (Mage::registry('current_kstmember')) {
            $formValues = array_merge($formValues, Mage::registry('current_kstmember')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {
        $flag = 0;
        $currentMember = Mage::registry('current_kstmember');
        if($currentMember->getId()){
            $flag = 1;
        }
        $html .= "<script>
                    var flg = ".$flag.";
                    if($('kstmember_vaeco_id') != undefined){
                        $('kstmember_vaeco_id').observe('keyup', function(){
                            $('kstmember_vaeco_id').value = $('kstmember_vaeco_id').value.toUpperCase();
                        });
                    }

                    $('kstmember_vaeco_id').observe('keypress', function(e){
                        if (e.keyCode == Event.KEY_RETURN) {
                            updateNames($('kstmember_vaeco_id').getValue());
                        }


                    });

                    function updateNames(vaeco_id){
                        new Ajax.Request('".$this->getUrl('*/traininglist_curriculum/getFullName')."', {
                            method : 'post',
                            parameters: {
                                'vaeco_id'   : vaeco_id
                            },
                            onSuccess : function(transport){
                                try{
                                    response = eval('(' + transport.responseText + ')');
                                } catch (e) {
                                    response = {};
                                }
                                if (response.fullname) {

                                    if($('kstmember_name') != undefined){
                                        $('kstmember_name').value = response.fullname;
                                    }

                                    if($('kstmember_vaeco_id') != undefined){
                                        $('kstmember_vaeco_id').value = response.vaecoid;
                                    }



                                    if($('kstmember_username') != undefined){
                                        $('kstmember_username').value = response.username;
                                    }


                                }else {
                                    alert('This VAECO ID is INVALID. Please check your data');
                                }

                            },
                            onFailure : function(transport) {
                                alert('This VAECO ID is INVALID. Please check your data')
                            }
                        });
                    }

                    Event.observe(document, \"dom:loaded\", function(e) {

                        if(flg == 0){
                            updateGroup($('kstmember_course_id').value);
                        }

                    });

                    function updateGroup(course_id){
                        new Ajax.Request('".$this->getUrl('*/kst_kstmember/updateGroup')."', {
                                method : 'post',
                                parameters: {
                                    'course_id'   : course_id
                                },
                                onSuccess : function(transport){
                                    try{
                                        response = eval('(' + transport.responseText + ')');
                                    } catch (e) {
                                        response = {};
                                    }
                                    if (response.group) {

                                        if($('kstmember_kstgroup_id') != undefined){
                                            $('kstmember_kstgroup_id').innerHTML = response.group;
                                        }

                                    }else {
                                        alert('Something went wrong');
                                    }

                                },
                                onFailure : function(transport) {
                                    alert('Something went wrong')
                                }
                            });
                    }
                     Event.observe('kstmember_course_id', 'change', function(evt){
                            updateGroup($('kstmember_course_id').value);
                     });


                </script>";
        return parent::_afterToHtml($html);
    }
}
