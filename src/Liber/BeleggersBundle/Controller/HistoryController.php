<?php
namespace Liber\BeleggersBundle\Controller;

use Doctrine\ORM\EntityManager;

use Liber\BeleggersBundle\Entity\OrderHistory;
use Liber\BeleggersBundle\Controller\LocaleController;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Zend\Json\Expr;
use Zend\Json\Json;

class HistoryController extends LocaleController {

    public function testGraphAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $history = $em->getRepository('LiberBeleggersBundle:OrderHistory')->findByUser($user);

        $lowest = $em->getRepository('LiberBeleggersBundle:OrderHistory')->findOneByUser($user, array('time' => 'ASC'));
        $highest = $em->getRepository('LiberBeleggersBundle:OrderHistory')->findOneByUser($user, array('time' => 'DESC'));

        $sorted = array();

        foreach($history as $item) {
            $sorted[$item->getStock()->getId()][] = $item;
        }

        $series = array();

        foreach($sorted as $group) {
            $data = array();
            $startPrice = 0;
            $currPrice = 0;
            /** @var OrderHistory $item */
            foreach($group as $item) {
                $utcTime = $this->getUtcTime($item);

                $dataItem = array($utcTime, $item->getPrice()*100);
                array_push($data, $dataItem);
            }

            $serie = array(
                "name" => $item->getStock()->getName().' ('.$item->getStock()->getStockType()->getName().')', "data" => $data,
            );
            array_push($series,$serie);
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
        $ob->xAxis->dateTimeLabelFormats(array(
            'second' => '%H:%M:%S '

        ));
        $ob->yAxis->title(array('text'  => "Price"));
        $ob->tooltip->formatter($func);
        $ob->series($series);

        return $this->render('LiberBeleggersBundle:History:testChart.html.twig', array(
            'chart' => $ob
        ));
    }

    private function getUtcTime($item) {
        $time = $item->getTime();

        $ts = mktime($time->format('H'), $time->format('i'), $time->format('s'), $time->format('m'), $time->format('d'), $time->format('Y'));

        return $ts *1000;
    }
}