<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Homepage
 *
 * @ORM\Table(name="homepage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HomepageRepository")
 */
class Homepage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255)
     */
    private $subtitle;

    /**
     * @var array
     *
     * @ORM\Column(name="feature", type="json_array")
     */
    private $feature;

    /**
     * @var array
     *
     * @ORM\Column(name="mmi_detail", type="json_array")
     */
    private $mmiDetail;


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
     * Set title
     *
     * @param string $title
     *
     * @return Homepage
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return Homepage
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set feature
     *
     * @param array $feature
     *
     * @return Homepage
     */
    public function setFeature($feature)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature
     *
     * @return array
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * Set mmiDetail
     *
     * @param array $mmiDetail
     *
     * @return Homepage
     */
    public function setMmiDetail($mmiDetail)
    {
        $this->mmiDetail = $mmiDetail;

        return $this;
    }

    /**
     * Get mmiDetail
     *
     * @return array
     */
    public function getMmiDetail()
    {
        return $this->mmiDetail;
    }
}

