<?php
/**
 * Created by PhpStorm.
 * User: jurgen
 * Date: 7/28/14
 * Time: 5:53 PM
 */

namespace SpiritStock\StockBundle\Service;

use Doctrine\ORM\EntityManager;

use SpiritStock\StockBundle\Entity\OrderHistory;
use SpiritStock\StockBundle\Entity\Stock;
use SpiritStock\UserBundle\Entity\User;

class HistoryService {

    /** @var EntityManager $em */
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Update the order history for graphs
     */
    public function updateHistory(User $user) {
        /** @var Stock[] $stock */
        $stock = $this->em->getRepository('SpiritStockStockBundle:Stock')->findByUser($this->user);
        $now   = new \DateTime();

        foreach($stock as $item) {
            $history = new OrderHistory();

            $history->setStock($item);
            $history->setUser($user);
            $history->setPrice($item->getCurrentPrice());
            $history->setTime($now);
            $history->setChangeType($item->getChangeType());

            $this->em->persist($history);
        }
    }
} 