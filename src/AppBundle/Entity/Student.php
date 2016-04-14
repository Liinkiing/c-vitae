<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 */
class Student
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
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="text", nullable=true)
     */
    private $website;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="datetime")
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="bac", type="string", length=10)
     */
    private $bac;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer")
     */
    private $age;

    /**
     * @var double
     *
     * @ORM\Column(name="fbid", type="float", nullable=true, unique=true)
     */
    private $fbid;


    /**
     * @var string
     *
     * @ORM\Column(name="groupe_tp", type="string", length=1)
     */
    private $groupeTp;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin", type="text", nullable=true)
     */
    private $linkedin;

    /**
     * @var string
     *
     * @ORM\Column(name="viadeo", type="text", nullable=true)
     */
    private $viadeo;

    /**
     * @var string
     *
     * @ORM\Column(name="professional_mail", type="string", length=255)
     */
    private $professionalMail;

    /**
     * @var array
     *
     * @ORM\Column(name="hobbies", type="simple_array", nullable=true)
     */
    private $hobbies;

    /**
     * @var string
     *
     * @ORM\Column(name="project_role", type="string", length=255)
     */
    private $projectRole;

    /**
     * @var string
     *
     * @ORM\Column(name="favorite_quote", type="text", nullable=true)
     */
    private $favoriteQuote;

    /**
     * @var string
     *
     * @ORM\Column(name="favorite_music", type="text", nullable=true)
     */
    private $favoriteMusic;

    /**
     * @var array
     *
     * @ORM\Column(name="languages", type="simple_array", nullable=true)
     */
    private $languages;

    /**
     * @var array
     *
     * @ORM\Column(name="softwares", type="simple_array", nullable=true)
     */
    private $softwares;

    /**
     * @var array
     *
     * @ORM\Column(name="programming_languages", type="simple_array", nullable=true)
     */
    private $programmingLanguages;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="secteur", type="string", length=255, nullable=true)
     */
    private $secteur;


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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Student
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Student
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Student
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set bac
     *
     * @param string $bac
     *
     * @return Student
     */
    public function setBac($bac)
    {
        $this->bac = $bac;

        return $this;
    }

    /**
     * Get bac
     *
     * @return string
     */
    public function getBac()
    {
        return $this->bac;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Student
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set groupeTp
     *
     * @param string $groupeTp
     *
     * @return Student
     */
    public function setGroupeTp($groupeTp)
    {
        $this->groupeTp = $groupeTp;

        return $this;
    }

    /**
     * Get groupeTp
     *
     * @return string
     */
    public function getGroupeTp()
    {
        return $this->groupeTp;
    }

    /**
     * Set linkedin
     *
     * @param string $linkedin
     *
     * @return Student
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    /**
     * Get linkedin
     *
     * @return string
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * Set viadeo
     *
     * @param string $viadeo
     *
     * @return Student
     */
    public function setViadeo($viadeo)
    {
        $this->viadeo = $viadeo;

        return $this;
    }

    /**
     * Get viadeo
     *
     * @return string
     */
    public function getViadeo()
    {
        return $this->viadeo;
    }

    /**
     * Set professionalMail
     *
     * @param string $professionalMail
     *
     * @return Student
     */
    public function setProfessionalMail($professionalMail)
    {
        $this->professionalMail = $professionalMail;

        return $this;
    }

    /**
     * Get professionalMail
     *
     * @return string
     */
    public function getProfessionalMail()
    {
        return $this->professionalMail;
    }

    /**
     * Set hobbies
     *
     * @param array $hobbies
     *
     * @return Student
     */
    public function setHobbies($hobbies)
    {
        $this->hobbies = $hobbies;

        return $this;
    }

    /**
     * Get hobbies
     *
     * @return array
     */
    public function getHobbies()
    {
        return $this->hobbies;
    }

    /**
     * Set projectRole
     *
     * @param string $projectRole
     *
     * @return Student
     */
    public function setProjectRole($projectRole)
    {
        $this->projectRole = $projectRole;

        return $this;
    }

    /**
     * Get projectRole
     *
     * @return string
     */
    public function getProjectRole()
    {
        return $this->projectRole;
    }

    /**
     * Set favoriteQuote
     *
     * @param string $favoriteQuote
     *
     * @return Student
     */
    public function setFavoriteQuote($favoriteQuote)
    {
        $this->favoriteQuote = $favoriteQuote;

        return $this;
    }

    /**
     * Get favoriteQuote
     *
     * @return string
     */
    public function getFavoriteQuote()
    {
        return $this->favoriteQuote;
    }

    /**
     * Set favoriteMusic
     *
     * @param string $favoriteMusic
     *
     * @return Student
     */
    public function setFavoriteMusic($favoriteMusic)
    {
        $this->favoriteMusic = $favoriteMusic;

        return $this;
    }

    /**
     * Get favoriteMusic
     *
     * @return string
     */
    public function getFavoriteMusic()
    {
        return $this->favoriteMusic;
    }

    /**
     * Set languages
     *
     * @param array $languages
     *
     * @return Student
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * Get languages
     *
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Set softwares
     *
     * @param array $softwares
     *
     * @return Student
     */
    public function setSoftwares($softwares)
    {
        $this->softwares = $softwares;

        return $this;
    }

    /**
     * Get softwares
     *
     * @return array
     */
    public function getSoftwares()
    {
        return $this->softwares;
    }

    /**
     * Set programmingLanguages
     *
     * @param array $programmingLanguages
     *
     * @return Student
     */
    public function setProgrammingLanguages($programmingLanguages)
    {
        $this->programmingLanguages = $programmingLanguages;

        return $this;
    }

    /**
     * Get programmingLanguages
     *
     * @return array
     */
    public function getProgrammingLanguages()
    {
        return $this->programmingLanguages;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Student
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set secteur
     *
     * @param string $secteur
     *
     * @return Student
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
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Student
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }


    public function getImageUrl() {
        if(!$this->fbid) return null;
        return "https://graph.facebook.com/v2.5/" . explode('.', sprintf('%f', $this->fbid))[0] . '/picture?type=large&width=400&height=400';
    }
}
