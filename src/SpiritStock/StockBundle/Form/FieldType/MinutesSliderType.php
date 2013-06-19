<?php
namespace SpiritStock\StockBundle\Form\FieldType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MinutesSliderType extends AbstractType
{
    public function getParent() {
        return 'text';
    }

    public function getName() {
        return 'minutes_slider';
    }
}