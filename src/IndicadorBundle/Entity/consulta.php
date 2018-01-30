<?php

namespace IndicadorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * consulta
 *
 * @ORM\Table(name="consulta")
 * @ORM\Entity(repositoryClass="IndicadorBundle\Repository\consultaRepository")
 */
class consulta
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

