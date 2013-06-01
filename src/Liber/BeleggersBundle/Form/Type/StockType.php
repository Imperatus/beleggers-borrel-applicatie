<?php
namespace Liber\BeleggersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Liber\BeleggersBundle\Form\Type\StockTypeType as Type;

class StockType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        //  name startingPrice currentPrice maxPrice minPrice startingStock currentStock stockType_id
        $builder->add('name');
        $builder->add('startingPrice', 'number', array('precision' => 2));
        $builder->add('currentPrice', 'number', array('precision' => 2));
        $builder->add('maxPrice', 'number', array('precision' => 2));
        $builder->add('minPrice', 'number', array('precision' => 2));
        $builder->add('startingStock', 'number', array('precision' => 2));
        $builder->add('currentStock', 'number', array('precision' => 2));
        $builder->add('stockType', 'entity', array(
            'class' => 'LiberBeleggersBundle:StockType',
            'property' => 'name',
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