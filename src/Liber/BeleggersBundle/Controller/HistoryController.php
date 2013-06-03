<?php
namespace Liber\BeleggersBundle\Controller;

use Doctrine\ORM\EntityManager;

use Liber\BeleggersBundle\Entity\OrderHistory;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\DateTime;

class HistoryController extends Controller {

    public function testGraphAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $history = $em->getRepository('LiberBeleggersBundle:OrderHistory')->findByUser($user);

        $sorted = array();

        foreach($history as $item) {
            $sorted[$item->getStock()->getId()][] = $item;
        }

        $series = array();

        foreach($sorted as $group) {
            $data = array();
            /** @var OrderHistory $item */
            foreach($group as $item) {

                //$dataItem = array(strtotime($item->getTime()->format('H:i:s')), $item->getPrice());
                $dataItem = array($item->getTime()->format('H:i:s'), $item->getPrice()*100);
                array_push($data, $dataItem);
            }
            $serie = array(
                "name" => $item->getStock()->getName().' ('.$item->getStock()->getStockType()->getName().')', "data" => $data,
            );
            array_push($series,$serie);
        }

//        var_dump($series);die;
        // Chart
        /*$series = array(
            array(
                "name" => "Stock item 1", "data" => array(1,2,4,5,6,3,8),
            ),
            array(
                "name" => "Stock item 2", "data" => array(2,3,4,5,6,4,9),
            ),
            array(
                "name" => "Stock item 3", "data" => array(5,1,5,1,5,1,5),
            ),
        );*/

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('History');
        $ob->xAxis->title(array('text'  => "Time"));
        $ob->yAxis->title(array('text'  => "Price"));
        $ob->series($series);

        return $this->render('LiberBeleggersBundle:History:testChart.html.twig', array(
            'chart' => $ob
        ));
    }
}