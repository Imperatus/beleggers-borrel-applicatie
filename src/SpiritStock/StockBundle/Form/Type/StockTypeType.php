<?php
namespace SpiritStock\StockBundle\Form\Type;

use SpiritStock\StockBundle\Form\FieldType\MinutesSliderType;
use SpiritStock\StockBundle\Form\FieldType\MultiplierSliderType;
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
            'data_class' => 'SpiritStock\StockBundle\Entity\StockType',
        ));
    }
}