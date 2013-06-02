<?php
namespace Liber\BeleggersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Liber\BeleggersBundle\Form\Type\StockTypeType as Type;


class StockType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        //  name startingPrice currentPrice maxPrice minPrice startingStock currentStock stockType_id
        $floatStyle = array(
            'precision' => 2,
            'label' => false,
            'attr' => array(
                'maxLength' => '5',
                'style'      => 'width:45px;'
        ));

        $numberStyle = array(
            'label' => false,
            'attr' => array(
                'maxLength' => '3',
                'style'      => 'width:25px;'
        ));

        $builder->add('name', null, array('attr' => array('style' => 'width: 75px;')));
        $builder->add('startingPrice', 'number', $floatStyle);
        $builder->add('currentPrice', 'number', $floatStyle);
        $builder->add('maxPrice', 'number',$floatStyle);
        $builder->add('minPrice', 'number', $floatStyle);
        $builder->add('startingStock', 'number', $numberStyle);
        $builder->add('currentStock', 'number', $numberStyle);
        $builder->add('stockType', 'entity', array(
            'class' => 'LiberBeleggersBundle:StockType',
            'property' => 'name',
            'attr' => array(
                'style' => 'width: 125px;',
            ),
        ));
    }

    public function getName() {
        return 'stock';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Liber\BeleggersBundle\Entity\Stock',
        ));
    }
}