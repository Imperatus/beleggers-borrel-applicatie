<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ijay
 * Date: 6/2/13
 * Time: 2:43 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Liber\BeleggersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GlobalSettingsType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $floatStyle = array(
            'precision' => 2,
            'required' => false,
            'label' => 'form.label.global.unitPrice',
            'attr' => array(
                'maxLength' => '5',
                'style'      => 'width:45px;',
            ));

        $builder
            ->add('currency', 'choice', array(
                'choices' => array('EUR' => 'Euro'),
                'label' => 'form.label.global.currency',
            ))
            ->add('unitName', null,  array(
                'required' => false,
                'label' => 'form.label.global.unitName',
            ))
            ->add('unitPrice', 'number', $floatStyle);

    }

    public function getName() {
        return 'globalSettings';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Liber\BeleggersBundle\Entity\GlobalSettings',
        ));
    }
}