<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aztarna
 *
 * @ORM\Table(name="aztarna")
 * @ORM\Entity
 *
 */
class Aztarna
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Date
     *
     * @ORM\Column(name="eguna", type="date", nullable=false)
     */
    private $eguna;

    /**
     * @var Time
     *
     * @ORM\Column(name="ordua", type="time", nullable=false)
     */
    private $ordua;

    /**
     * @ORM\Column(name="longitudea",type="decimal", precision=11, scale=8)
     */
    protected $longitudea;

    /**
     * @ORM\Column(name="latitudea",type="decimal", precision=10, scale=8)
     */
    protected $latitudea;

    /**
     * @var integer
     *
     * @ORM\Column(name="abiadura", type="integer")
     */
    private $abiadura;

    /**
     * @var integer
     *
     * @ORM\Column(name="inklinazioa", type="integer")
     */
    private $inklinazioa;

    /**
     * @var string
     *
     * @ORM\Column(name="malda", type="string")
     */
    private $malda;



    /**
     *          ERLAZIOAK
     */

    /**
     * @var ibilgailua
     * @ORM\ManyToOne(targetEntity="Ibilgailua")
     * @ORM\JoinColumn(name="ibilgailua_id", referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $ibilgailua;
    
    
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set eguna
     *
     * @param \DateTime $eguna
     *
     * @return Aztarna
     */
    public function setEguna($eguna)
    {
        $this->eguna = $eguna;

        return $this;
    }

    /**
     * Get eguna
     *
     * @return \DateTime
     */
    public function getEguna()
    {
        return $this->eguna;
    }

    /**
     * Set ordua
     *
     * @param \DateTime $ordua
     *
     * @return Aztarna
     */
    public function setOrdua($ordua)
    {
        $this->ordua = $ordua;

        return $this;
    }

    /**
     * Get ordua
     *
     * @return \DateTime
     */
    public function getOrdua()
    {
        return $this->ordua;
    }

    /**
     * Set longitudea
     *
     * @param string $longitudea
     *
     * @return Aztarna
     */
    public function setLongitudea($longitudea)
    {
        $this->longitudea = $longitudea;

        return $this;
    }

    /**
     * Get longitudea
     *
     * @return string
     */
    public function getLongitudea()
    {
        return $this->longitudea;
    }

    /**
     * Set latitudea
     *
     * @param string $latitudea
     *
     * @return Aztarna
     */
    public function setLatitudea($latitudea)
    {
        $this->latitudea = $latitudea;

        return $this;
    }

    /**
     * Get latitudea
     *
     * @return string
     */
    public function getLatitudea()
    {
        return $this->latitudea;
    }

    /**
     * Set abiadura
     *
     * @param integer $abiadura
     *
     * @return Aztarna
     */
    public function setAbiadura($abiadura)
    {
        $this->abiadura = $abiadura;

        return $this;
    }

    /**
     * Get abiadura
     *
     * @return integer
     */
    public function getAbiadura()
    {
        return $this->abiadura;
    }

    /**
     * Set inklinazioa
     *
     * @param integer $inklinazioa
     *
     * @return Aztarna
     */
    public function setInklinazioa($inklinazioa)
    {
        $this->inklinazioa = $inklinazioa;

        return $this;
    }

    /**
     * Get inklinazioa
     *
     * @return integer
     */
    public function getInklinazioa()
    {
        return $this->inklinazioa;
    }

    /**
     * Set malda
     *
     * @param integer $malda
     *
     * @return Aztarna
     */
    public function setMalda($malda)
    {
        $this->malda = $malda;

        return $this;
    }

    /**
     * Get malda
     *
     * @return integer
     */
    public function getMalda()
    {
        return $this->malda;
    }

    /**
     * Set ibilgailua
     *
     * @param \AppBundle\Entity\Ibilgailua $ibilgailua
     *
     * @return Aztarna
     */
    public function setIbilgailua(\AppBundle\Entity\Ibilgailua $ibilgailua = null)
    {
        $this->ibilgailua = $ibilgailua;

        return $this;
    }

    /**
     * Get ibilgailua
     *
     * @return \AppBundle\Entity\Ibilgailua
     */
    public function getIbilgailua()
    {
        return $this->ibilgailua;
    }
}
