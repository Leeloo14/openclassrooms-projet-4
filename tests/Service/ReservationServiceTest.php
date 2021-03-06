<?php


use App\Entity\Reservation;
use App\Entity\Tarif;
use App\Entity\Visitor;
use PHPUnit\Framework\TestCase;

class ReservationServiceTest extends TestCase
{
    public function testGetReservationTotal()
    {
        // PREPARE
        $em = $this->createMock(\Doctrine\ORM\EntityManager::class);
        $sm = $this->createMock(\Swift_Mailer::class);
        $twig = $this->createMock(\Twig_Environment::class);
        $tarifRepository = $this->createMock(\App\Repository\TarifRepository::class);
        $reservationService = new \App\Service\ReservationService($em, $sm, $twig);
        $tarif1 = new Tarif();
        $tarif1->setPrice(10);
        $tarifRepository->expects($this->any())
            ->method('find')
            ->willReturn($tarif1);
        $em->expects($this->any())
            ->method('getRepository')
            ->willReturn($tarifRepository);
        $reservation = new Reservation();
        $visitor1 = new Visitor();
        $visitor1->setTarif($tarif1);
        $visitor2 = new Visitor();
        $visitor2->setTarif($tarif1);

        // EXECUTE
        $reservation
            ->addVisitor($visitor1)
            ->addVisitor($visitor2);

        // CHECK
        $this->assertEquals($reservationService->getReservationTotal($reservation), 20);
    }

    public function testGetNbVisitors()
    {
        $em = $this->createMock(\Doctrine\ORM\EntityManager::class);
        $sm = $this->createMock(\Swift_Mailer::class);
        $twig = $this->createMock(\Twig_Environment::class);
        $reservationService = new \App\Service\ReservationService($em, $sm, $twig);
        $res1 = new Reservation();
        $res2 = new Reservation();
        $res1->addVisitor(new Visitor());
        $res2->addVisitor(new Visitor());
        $reservations = [$res1, $res2];

        $nbVisitors = $reservationService->getNbVisitors($reservations);

        $this->assertEquals($nbVisitors, 2);

    }

}
