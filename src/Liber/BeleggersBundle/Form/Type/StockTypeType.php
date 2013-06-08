<?php
namespace Liber\BeleggersBundle\Form\Type;

use Liber\BeleggersBundle\Form\FieldType\MinutesSliderType;
use Liber\BeleggersBundle\Form\FieldType\MultiplierSliderType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StockTypeType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name',null, array('label' => false))
            ->add('startToMinimum', new MinutesSliderType(), array('label' => false))
            ->add('magicToMaximum', new MultiplierSliderType(), array('label' => false));
     }

    public function getName() {
        return 'stockType';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Liber\BeleggersBundle\Entity\StockType',
        ));
    }
}