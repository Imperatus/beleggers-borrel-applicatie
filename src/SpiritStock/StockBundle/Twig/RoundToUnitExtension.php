<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ijay
 * Date: 6/19/13
 * Time: 8:14 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SpiritStock\StockBundle\Twig;

class RoundToUnitExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('roundToUnit', array($this, 'roundToUnitFilter')),
        );
    }

    public function roundToUnitFilter($price, $unit, $signed = null)
    {
        if(!empty($signed)) {
            if($price > 0) {
                $price = trim(str_replace('+','', $price));
                $char = '+ ';
            } else if ($price < 0) {
                $price = trim(str_replace('-','', $price));
                $char = '- ';
            } else {
                $char = '';
            }
        } else {
            $char = '';
        }

        // Convert to INT to avoid strange rounding errors
        $price = intval($price*100);
        $unit = intval($unit*100);

        $remainder = $price % $unit;

        if($remainder < ($unit / 2)) {
            $price = $price - $remainder;
        } else {
            $price = $price - $remainder + $unit;
        }

        $result = $char.number_format($price/100,2);


        return $result;
    }

    public function getName()
    {
        return 'round_to_unit_extension';
    }
}