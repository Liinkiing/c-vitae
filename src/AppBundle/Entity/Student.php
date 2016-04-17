<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 * @Uploadable()
 */
class Student implements UserInterface, \Serializable, NamerInterface
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
     * @ORM\Column(name="username", type="string", length=30)
     */
    private $username;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="profile_picture", type="text", nullable=true)
     *
     */
    private $profilePicture;

    /**
     * @ORM\Column(name="cv", type="text", nullable=true)
     *
     */
    private $cv;

    /**
     * @UploadableField(mapping="student_cv", fileNameProperty="cv")
     */
    private $cvFile;


    /**
     * @UploadableField(mapping="student_image", fileNameProperty="profilePicture")
     */
    private $profilePictureFile;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="4096")
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role", inversedBy="students", cascade={"persist"})
     */
    private $roles;

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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="author", cascade={"remove"})
     */
    private $posts;




    public function getFullName($inversed = false){
        return ($inversed) ? $this->getLastName() . " " . $this->getFirstName() : $this->getFirstName() . " " . $this->getLastName();
    }


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


    public function getFacebookImageUrl() {
        if(!$this->fbid) return null;
        return "https://graph.facebook.com/v2.6/" . explode('.', sprintf('%f', $this->fbid))[0] . '/picture?type=large&width=400&height=400';
    }

    // Impossibilité d'accéder à la photo de couverture Facebook d'un utilisateur sans ayant obtenu au préalable un token d'accès. Je laisse quand même la fonction présente, au cas où
    public function getFacebookCoverUrl() {
        if(!$this->fbid) return null;
        $json = file_get_contents("https://graph.facebook.com/v2.6/" . explode('.', sprintf('%f', $this->fbid))[0] . '?fields=cover');
        $object = json_decode($json);
        return $object->cover->source;

    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles = [];
        for ($i = 0; $i < $this->roles->count(); $i++) {
            $roles[$i] = $this->roles->get($i)->getRole();
        }
        return $roles;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRolesObject()
    {
        return $this->roles;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;

    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Student
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Student
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Student
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set fbid
     *
     * @param float $fbid
     *
     * @return Student
     */
    public function setFbid($fbid)
    {
        $this->fbid = $fbid;

        return $this;
    }

    /**
     * Get fbid
     *
     * @return float
     */
    public function getFbid()
    {
        return $this->fbid;
    }

    /**
     * Add role
     *
     * @param \AppBundle\Entity\Role $role
     *
     * @return Student
     */
    public function addRole(\AppBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \AppBundle\Entity\Role $role
     */
    public function removeRole(\AppBundle\Entity\Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @param $role
     * @return mixed
     */
    public function isGranted($role)
    {
//        $stringArray = [];
//        for($i = 0; $i < count($this->getRoles()); $i++){
//            $stringArray[$i] = $this->getRoles()[$i]->getRole();
//        }
        return in_array($role, $this->getRoles());
    }

    /**
     * Add post
     *
     * @param \AppBundle\Entity\Post $post
     *
     * @return Student
     */
    public function addPost(\AppBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \AppBundle\Entity\Post $post
     */
    public function removePost(\AppBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Student
     */
    public function setImageFile(File $image = null)
    {
        $this->profilePictureFile = $image;

//        if ($image) {
            $this->updatedAt = new \DateTime('now');
//        }

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->profilePictureFile;
    }

    /**
     * Set profilePicture
     *
     * @param string $profilePicture
     *
     * @return Student
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Student
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return mixed
     */
    public function getProfilePictureFile()
    {
        return $this->profilePictureFile;
    }

    /**
     * @param mixed $profilePictureFile
     */
    public function setProfilePictureFile($profilePictureFile)
    {
        $this->profilePictureFile = $profilePictureFile;
    }

    /**
     * Creates a name for the file being uploaded.
     *
     * @param object $object The object the upload is attached to.
     * @param PropertyMapping $mapping The mapping to use to manipulate the given object.
     *
     * @return string The file name.
     */
    public function name($object, PropertyMapping $mapping)
    {
        return 'CV_' . strtolower(str_replace(' ', '_', $object->getFullName())) . "." . $mapping->getFile($object)->guessExtension();
    }

    public function setCvFile(File $cv = null)
    {
        $this->cvFile = $cv;

        if ($cv) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getCvFile()
    {
        return $this->cvFile;
    }

    /**
     * @return mixed
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * @param mixed $cv
     */
    public function setCv($cv)
    {
        $this->cv = $cv;
    }
}
