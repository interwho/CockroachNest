<?php
namespace HackTheNorth\CockroachNest\Entities;

use Doctrine\ORM\Mapping;
// ID, Name, URL, Lat, Lng, Status
/**
 * Node
 *
 * @Table(name="nodes")
 * @Entity
 */
class Node
{
    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer", nullable=true)
     * @Id
     */
    protected $id;

    /**
     * @var string $location
     *
     * @Column(name="location", type="string", nullable=true)
     */
    protected $location;

    /**
     * @var string $merchantName
     *
     * @Column(name="merchant_name", type="string", nullable=true)
     */
    protected $merchantName;

    public function __construct($id, $location, $merchantName)
    {
        $this->id = $id;
        $this->location = $location;
        $this->merchantName = $merchantName;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getMerchantName()
    {
        return $this->merchantName;
    }

    /**
     * @param string $merchantName
     */
    public function setMerchantName($merchantName)
    {
        $this->merchantName = $merchantName;
    }
}