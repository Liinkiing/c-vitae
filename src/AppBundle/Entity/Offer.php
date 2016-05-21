<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offer
 *
 * @ORM\Table(name="offer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OfferRepository")
 */
class Offer
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="secteur", type="string", length=255)
     */
    private $secteur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_at", type="datetime")
     */
    private $publishedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="string", length=255)
     */
    private $duree;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_post", type="integer")
     */
    private $nbPost;

    /**
     * @var string
     *
     * @ORM\Column(name="remuneration", type="string", length=255)
     */
    private $remuneration;

    /**
     * @var string
     *
     * @ORM\Column(name="type_contrat", type="string", length=255)
     */
    private $typeContrat;

    /**
     * @var string
     *
     * @ORM\Column(name="localisation", type="string", length=255)
     */
    private $localisation;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=255)
     */
    private $contact;

    /**
     * @var array
     *
     * @ORM\Column(name="students_postuled", type="simple_array")
     */
    private $studentsPostuled;


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
     * Set name
     *
     * @param string $name
     *
     * @return Offer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set secteur
     *
     * @param string $secteur
     *
     * @return Offer
     */
    public function setSecteur($secteur)
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * Get secteur
     *
     * @return string
     */
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return Offer
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set duree
     *
     * @param string $duree
     *
     * @return Offer
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return string
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set nbPost
     *
     * @param integer $nbPost
     *
     * @return Offer
     */
    public function setNbPost($nbPost)
    {
        $this->nbPost = $nbPost;

        return $this;
    }

    /**
     * Get nbPost
     *
     * @return int
     */
    public function getNbPost()
    {
        return $this->nbPost;
    }

    /**
     * Set remuneration
     *
     * @param string $remuneration
     *
     * @return Offer
     */
    public function setRemuneration($remuneration)
    {
        $this->remuneration = $remuneration;

        return $this;
    }

    /**
     * Get remuneration
     *
     * @return string
     */
    public function getRemuneration()
    {
        return $this->remuneration;
    }

    /**
     * Set typeContrat
     *
     * @param string $typeContrat
     *
     * @return Offer
     */
    public function setTypeContrat($typeContrat)
    {
        $this->typeContrat = $typeContrat;

        return $this;
    }

    /**
     * Get typeContrat
     *
     * @return string
     */
    public function getTypeContrat()
    {
        return $this->typeContrat;
    }

    /**
     * Set localisation
     *
     * @param string $localisation
     *
     * @return Offer
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get localisation
     *
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return Offer
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set studentsPostuled
     *
     * @param array $studentsPostuled
     *
     * @return Offer
     */
    public function setStudentsPostuled($studentsPostuled)
    {
        $this->studentsPostuled = $studentsPostuled;

        return $this;
    }

    /**
     * Get studentsPostuled
     *
     * @return array
     */
    public function getStudentsPostuled()
    {
        return $this->studentsPostuled;
    }
}

