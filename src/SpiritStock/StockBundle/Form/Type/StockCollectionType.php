<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ijay
 * Date: 6/1/13
 * Time: 12:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SpiritStock\StockBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StockCollectionType extends AbstractType {
    private $typeChoices;

    public function __construct($typeChoices = null) {
        $this->typeChoices = $typeChoices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        //  name startingPrice currentPrice maxPrice minPrice startingStock currentStock stockType_id
        $builder->add('stocks', 'collection', array(
            'type' => new StockType($this->typeChoices),
            'allow_add' => true,
            'allow_delete' => true,
            'label' => false,
        ));

    }

    public function getName() {
        return 'stockCollection';
    }
}