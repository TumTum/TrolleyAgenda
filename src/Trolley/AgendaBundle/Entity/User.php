<?php
/**
 * Created for TrolleyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 17.02.16
 * Time: 18:55
 * Copyright: 2014 Tobias Matthaiou
 */

namespace Trolley\AgendaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="fos_user",
 *     uniqueConstraints=@ORM\UniqueConstraint(name="autocomplete",columns={"firstlastname"})
 * )
 * @ORM\Entity(repositoryClass="Trolley\AgendaBundle\Repository\UserRepository")
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
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Day", mappedBy="taUsers")
     */
    private $days = null;

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

    /**
     * @return ArrayCollection
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @param ArrayCollection $days
     */
    public function setDays($days)
    {
        $this->days = $days;
    }

    /**
     * @param Day $user
     */
    public function addDay(Day $user)
    {
        $this->days->add($user);
    }

    /**
     * @param Day $day
     */
    public function removeDay(Day $day)
    {
        $this->days->removeElement($day);
    }

    /**
     * Wird gebrauch wird das Formialar
     *
     * @return string
     */
    public function getAdminRole()
    {
        return $this->hasRole('ROLE_ADMIN') ? 'yes' : 'no';
    }

    /**
     * Upgrade oder Degrade den User
     * @param $upgrade
     */
    public function setAdminRole($upgrade)
    {
        if ($upgrade == 'yes') {
            $this->addRole('ROLE_ADMIN');
        } else {
            $this->removeRole('ROLE_ADMIN');
        }
    }

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->days = new ArrayCollection();
        parent::__construct();
    }


}