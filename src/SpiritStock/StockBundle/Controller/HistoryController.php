<?php
namespace SpiritStock\StockBundle\Controller;


use SpiritStock\StockBundle\Entity\OrderHistory;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\Validator\Constraints\Collection;
use Zend\Json\Expr;

/**
 * TODO - Work in progress! Current state of code is abominable as this was literally a last minute hack... :'/
 *        (not critical for the working/maintainability of the app though)
 *
 * Class HistoryController
 *
 * @package SpiritStock\StockBundle\Controller
 */
class HistoryController extends LocaleController
{
    /**
     * Renders page containing graph
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testGraphAction() {
        $user    = $this->get('security.context')->getToken()->getUser();
        $em      = $this->getDoctrine()->getManager();

        $history = $em->getRepository('SpiritStockStockBundle:OrderHistory')->findByUser($user);
        $lowest  = $em->getRepository('SpiritStockStockBundle:OrderHistory')->findOneByUser($user, array('time' => 'ASC'));
        $highest = $em->getRepository('SpiritStockStockBundle:OrderHistory')->findOneByUser($user, array('time' => 'DESC'));

        $sorted  = array();

        // TODO - Use more advanced techniques to simplify this immensely, such as array_walk or similar functions
        foreach($history as $item) {
            $sorted[$item->getStock()->getId()][] = $item;
        }

        $series = array();

        foreach ($sorted as $group) {
            $data       = array();
            $startPrice = 0;
            $currPrice  = 0;

            /** @var OrderHistory $item */
            foreach($group as $item) {
                $utcTime  = $this->getUtcTime($item);
                $dataItem = array($utcTime, $item->getPrice()*100);
                array_push($data, $dataItem);
            }

            $serie = array(
                "name" => $item->getStock()->getName().' ('.$item->getStock()->getStockType()->getName().')', "data" => $data,
            );

            array_push($series, $serie);
        }

        $func = new Expr("function() {
            return '<b>'+ this.series.name +'</b><br/>'+
            Highcharts.dateFormat('%e %b %H:%M:%S', this.x) +' --- Price:'+ this.y;
        }");

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('History');
        $ob->xAxis->title(array('text'  => "Time"));
        $ob->xAxis->type('datetime');
        $ob->xAxis->dateTimeLabelFormats(array('second' => '%H:%M:%S '));
        $ob->yAxis->title(array('text'  => "Price"));
        $ob->tooltip->formatter($func);
        $ob->series($series);

        return $this->render('SpiritStockStockBundle:History:testChart.html.twig', array(
            'chart' => $ob
        ));
    }

    /**
     * @param OrderHistory $item
     *
     * @return int
     */
    private function getUtcTime(OrderHistory $item) {
        $time = $item->getTime();
        $ts   = mktime($time->format('H'), $time->format('i'), $time->format('s'), $time->format('m'), $time->format('d'), $time->format('Y'));

        return $ts * 1000;
    }
}