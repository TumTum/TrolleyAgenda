<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 17.02.16
 * Time: 18:55
 * Copyright: 2014 Tobias Matthaiou
 */

namespace Trolly\AgendaBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstlastname", type="string", length=255)
     */
    private $firstlastname  = "";

    /**
     * @var int
     *
     * @ORM\Column(name="plz", type="integer", nullable=true)
     */
    private $plz = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city = "";

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */
    private $street = "";

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    private $phone = "";

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", nullable=true)
     */
    private $mobile = "";

    /**
     * @var string
     *
     * @ORM\Column(name="mobile2", type="string", nullable=true)
     */
    private $mobile2 = "";

    /**
     * @return string
     */
    public function getFirstlastname()
    {
        return $this->firstlastname;
    }

    /**
     * @param string $firstlastname
     */
    public function setFirstlastname($firstlastname)
    {
        $this->firstlastname = $firstlastname;
    }

    /**
     * @return int
     */
    public function getPlz()
    {
        return $this->plz;
    }

    /**
     * @param int $plz
     */
    public function setPlz($plz)
    {
        $this->plz = $plz;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return string
     */
    public function getMobile2()
    {
        return $this->mobile2;
    }

    /**
     * @param string $mobile2
     */
    public function setMobile2($mobile2)
    {
        $this->mobile2 = $mobile2;
    }
}