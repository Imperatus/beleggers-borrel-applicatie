<?php
namespace SpiritStock\StockBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SpiritStock\StockBundle\Form\Type\StockTypeType as Type;


class StockType extends AbstractType {
    private $typeChoices;

    public function __construct($typeChoices = null) {
        $this->typeChoices = $typeChoices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $floatStyle = array(
            'precision' => 2,
            'label' => false,
            'attr' => array(
                'maxLength' => '5',
                'style'      => 'width:45px;',
                'placeholder' => '1.00'
        ));

        $numberStyle = array(
            'label' => false,
            'attr' => array(
                'maxLength' => '3',
                'style'      => 'width:25px;',
                'placeholder' => '0'
        ));

        $typeOptions = array(
            'class' => 'SpiritStockStockBundle:StockType',
            'choices' => $this->typeChoices,
            'property' => 'name',
            'attr' => array(
                'style' => 'width: 125px;',
            ),
        );

        $builder->add('name', null, array('attr' => array(
            'style' => 'width: 150px;',
            'placeholder' => 'form.placeholder.stock.name'
        )));

        $floatStyle['attr']['class'] = 'startingPrice';
        $builder->add('startingPrice', 'number', $floatStyle);

        $floatStyle['attr']['class'] = 'currentPrice';
        $builder->add('currentPrice', 'number', $floatStyle);

        unset($floatStyle['attr']['class']);
        $builder->add('maxPrice', 'number',$floatStyle);
        $builder->add('minPrice', 'number', $floatStyle);


        $numberStyle['attr']['class'] = 'startingStock';
        $builder->add('startingStock', 'number', $numberStyle);

        $numberStyle['attr']['class'] = 'currentStock';
        $builder->add('currentStock', 'number', $numberStyle);
        $builder->add('stockType', 'entity', $typeOptions);
    }

    public function getName() {
        return 'stock';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SpiritStock\StockBundle\Entity\Stock',
        ));
    }
}