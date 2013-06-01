<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ijay
 * Date: 6/1/13
 * Time: 12:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Liber\BeleggersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StockCollectionType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        //  name startingPrice currentPrice maxPrice minPrice startingStock currentStock stockType_id
        $builder->add('stocks', 'collection', array(
            'type' => new StockType(),
            'allow_add' => true,
            'allow_delete' => true,
            'label' => false,
        ));

    }

    public function getName() {
        return 'stockCollection';
    }
}