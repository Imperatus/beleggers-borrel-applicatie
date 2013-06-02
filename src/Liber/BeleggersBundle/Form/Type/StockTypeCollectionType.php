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

class StockTypeCollectionType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        //  name startingPrice currentPrice maxPrice minPrice startingStock currentStock stockType_id
        $builder->add('stockTypes', 'collection', array(
            'type' => new StockTypeType(),
            'allow_add' => true,
            'allow_delete' => true,
            'label' => false,
        ));

    }

    public function getName() {
        return 'stockTypeCollection';
    }
}