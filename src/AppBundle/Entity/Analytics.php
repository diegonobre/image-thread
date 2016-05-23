<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Analytics
 *
 * @ORM\Table(name="analytics")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnalyticsRepository")
 */
class Analytics
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="view_counter", type="bigint", nullable=true)
     */
    private $viewCounter;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set viewCounter
     *
     * @param integer $viewCounter
     *
     * @return Analytics
     */
    public function setViewCounter($viewCounter)
    {
        $this->viewCounter = $viewCounter;

        return $this;
    }

    /**
     * Get viewCounter
     *
     * @return int
     */
    public function getViewCounter()
    {
        return $this->viewCounter;
    }
}
