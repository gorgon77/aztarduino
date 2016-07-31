<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ibilgailua
 *
 * @ORM\Table(name="ibilgailua")
 * @ORM\Entity
 *
 */
class Ibilgailua
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
     * @var string
     *
     * @ORM\Column(name="kodea", type="string")
     */
    private $kodea;

    /**
     * @var string
     *
     * @ORM\Column(name="marka", type="string")
     */
    private $marka;

    /**
     * @var string
     *
     * @ORM\Column(name="modeloa", type="string")
     */
    private $modeloa;

    /**
     * @var string
     *
     * @ORM\Column(name="rutakolorea", type="string")
     */
    private $rutakolorea;


    /**
     * @ORM\Column(name="longitudea",type="decimal", precision=11, scale=8)
     */
    protected $longitudea;

    /**
     * @ORM\Column(name="latitudea",type="decimal", precision=10, scale=8)
     */
    protected $latitudea;




    /**
     *          ERLAZIOAK
     */

    /**
     * @var user
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",onDelete="SET NULL")
     *
     */
    private $user;


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
     * Set kodea
     *
     * @param string $kodea
     *
     * @return Ibilgailua
     */
    public function setKodea($kodea)
    {
        $this->kodea = $kodea;

        return $this;
    }

    /**
     * Get kodea
     *
     * @return string
     */
    public function getKodea()
    {
        return $this->kodea;
    }

    /**
     * Set marka
     *
     * @param string $marka
     *
     * @return Ibilgailua
     */
    public function setMarka($marka)
    {
        $this->marka = $marka;

        return $this;
    }

    /**
     * Get marka
     *
     * @return string
     */
    public function getMarka()
    {
        return $this->marka;
    }

    /**
     * Set modeloa
     *
     * @param string $modeloa
     *
     * @return Ibilgailua
     */
    public function setModeloa($modeloa)
    {
        $this->modeloa = $modeloa;

        return $this;
    }

    /**
     * Get modeloa
     *
     * @return string
     */
    public function getModeloa()
    {
        return $this->modeloa;
    }

    /**
     * Set rutakolorea
     *
     * @param string $rutakolorea
     *
     * @return Ibilgailua
     */
    public function setRutakolorea($rutakolorea)
    {
        $this->rutakolorea = $rutakolorea;

        return $this;
    }

    /**
     * Get rutakolorea
     *
     * @return string
     */
    public function getRutakolorea()
    {
        return $this->rutakolorea;
    }

    /**
     * Set longitudea
     *
     * @param string $longitudea
     *
     * @return Ibilgailua
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
     * @return Ibilgailua
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Ibilgailua
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
