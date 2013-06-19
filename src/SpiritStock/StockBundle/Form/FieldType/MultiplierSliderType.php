<?php
namespace SpiritStock\StockBundle\Form\FieldType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MultiplierSliderType extends AbstractType
{
    public function getParent() {
        return 'number';
    }

    public function getName() {
        return 'multiplier_slider';
    }
}