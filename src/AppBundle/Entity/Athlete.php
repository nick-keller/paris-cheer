<?php

namespace AppBundle\Entity;

use AppBundle\Enum\Gender;
use Doctrine\ORM\Mapping as ORM;
use Ferrandini\Urlizer;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Athlete
 *
 * @ORM\Table(name="athlete")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AthleteRepository")
 * @Vich\Uploadable
 */
class Athlete
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
     * @ORM\Column(name="licence_id", type="string", length=10, nullable=true)
     */
    private $licenceId;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=100)
     * @Assert\NotBlank(groups={"basic_info"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100)
     * @Assert\NotBlank(groups={"basic_info"})
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date")
     * @Assert\NotBlank(groups={"basic_info"})
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=10)
     * @Assert\NotBlank(groups={"info"})
     * @Assert\Choice({"male", "female"}, groups={"info"})
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=15, nullable=true)
     * @Assert\NotBlank(groups={"contact"})
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     * @Assert\Email(groups={"contact"})
     * @Assert\NotBlank(groups={"contact"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="birth_place", type="string", length=100, nullable=true)
     * @Assert\NotBlank(groups={"info"})
     */
    private $birthPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="nationality", type="string", length=100, nullable=true)
     * @Assert\NotBlank(groups={"info"})
     */
    private $nationality = 'Française';

    /**
     * @var string
     *
     * @ORM\Column(name="emergency_name", type="string", length=100, nullable=true)
     * @Assert\NotBlank(groups={"emergency"})
     */
    private $emergencyName;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency_phone", type="string", length=15, nullable=true)
     * @Assert\NotBlank(groups={"emergency"})
     */
    private $emergencyPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency_email", type="string", length=100, nullable=true)
     * @Assert\Email(groups={"emergency"})
     */
    private $emergencyEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="address_address", type="string", length=255, nullable=true)
     * @Assert\Email(groups={"address"})
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="address_zip_code", type="string", length=5, nullable=true)
     * @Assert\Email(groups={"address"})
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="address_city", type="string", length=100, nullable=true)
     * @Assert\Email(groups={"address"})
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="program", type="string", length=100, nullable=true)
     */
    private $program;

    /**
     * @var string
     */
    private $category;

    /**
     * @var bool
     *
     * @ORM\Column(name="free_tshirt", type="boolean", nullable=true)
     * @Assert\NotNull(groups={"tshirt"})
     */
    private $freeTShirt;

    /**
     * @var string
     *
     * @ORM\Column(name="tshirt_size", type="string", length=3, nullable=true)
     * @Assert\Choice(choices={"XS", "S", "M", "L", "XL", "XXL"}, groups={"tshirt"})
     */
    private $tShirtSize;

    /**
     * @var string
     *
     * @ORM\Column(name="tshirt_color", type="string", length=10, nullable=true)
     * @Assert\Choice(choices={"blue", "orange"}, groups={"tshirt"})
     */
    private $tShirtColor;

    /**
     * @var bool
     *
     * @ORM\Column(name="paris_cheer_bra", type="boolean", nullable=true)
     * @Assert\NotNull(groups={"bra"})
     */
    private $parisCheerBra;

    /**
     * @var string
     *
     * @ORM\Column(name="bra_size", type="string", length=3, nullable=true)
     * @Assert\Choice(choices={"XS", "S", "M", "L"}, groups={"bra"})
     */
    private $braSize;

    /**
     * @var bool
     *
     * @ORM\Column(name="qs_sport_only_nos", type="boolean", nullable=true)
     */
    private $qsSportOnlyNos;

    /**
     * @Vich\UploadableField(mapping="athletes_files", fileNameProperty="pictureFileName")
     * @Assert\Image(groups={"file"})
     * @Assert\NotBlank(groups={"file"})
     * @var File
     */
    private $pictureFile;

    /**
     * @ORM\Column(name="picture_file_name", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $pictureFileName;

    /**
     * @Vich\UploadableField(mapping="athletes_files", fileNameProperty="passportFileName")
     * @Assert\File(mimeTypes = {"application/pdf", "application/x-pdf", "image/*"}, groups={"file"}, maxSize="10M")
     * @Assert\NotBlank(groups={"file"})
     * @var File
     */
    private $passportFile;

    /**
     * @ORM\Column(name="passport_file_name", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $passportFileName;

    /**
     * @Vich\UploadableField(mapping="athletes_files", fileNameProperty="paperFormFileName")
     * @Assert\File(mimeTypes = {"application/pdf", "application/x-pdf", "image/*"}, groups={"file"}, maxSize="10M")
     * @Assert\NotBlank(groups={"file"})
     * @var File
     */
    private $paperFormFile;

    /**
     * @ORM\Column(name="paper_form_file_name", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $paperFormFileName;


    public function __toString()
    {
        return $this->firstName . ' ' . strtoupper($this->lastName);
    }

    /**
     * Set id
     *
     * @param int $id
     *
     * @return Athlete
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Athlete
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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Athlete
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
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Athlete
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

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Athlete
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    public function isMale()
    {
        return $this->gender == Gender::Male;
    }

    public function isFemale()
    {
        return $this->gender == Gender::Female;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Athlete
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Athlete
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set birthPlace
     *
     * @param string $birthPlace
     *
     * @return Athlete
     */
    public function setBirthPlace($birthPlace)
    {
        $this->birthPlace = preg_replace('/,.+/', '', $birthPlace);

        return $this;
    }

    /**
     * Get birthPlace
     *
     * @return string
     */
    public function getBirthPlace()
    {
        return $this->birthPlace;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     *
     * @return Athlete
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set emergencyName
     *
     * @param string $emergencyName
     *
     * @return Athlete
     */
    public function setEmergencyName($emergencyName)
    {
        $this->emergencyName = $emergencyName;

        return $this;
    }

    /**
     * Get emergencyName
     *
     * @return string
     */
    public function getEmergencyName()
    {
        return $this->emergencyName;
    }

    /**
     * Set emergencyPhone
     *
     * @param string $emergencyPhone
     *
     * @return Athlete
     */
    public function setEmergencyPhone($emergencyPhone)
    {
        $this->emergencyPhone = $emergencyPhone;

        return $this;
    }

    /**
     * Get emergencyPhone
     *
     * @return string
     */
    public function getEmergencyPhone()
    {
        return $this->emergencyPhone;
    }

    /**
     * Set emergencyEmail
     *
     * @param string $emergencyEmail
     *
     * @return Athlete
     */
    public function setEmergencyEmail($emergencyEmail)
    {
        $this->emergencyEmail = $emergencyEmail;

        return $this;
    }

    /**
     * Get emergencyEmail
     *
     * @return string
     */
    public function getEmergencyEmail()
    {
        return $this->emergencyEmail;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Athlete
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Does not do anything.
     *
     * @param string $address
     *
     * @return Athlete
     */
    public function setFullAddress($address)
    {
        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getFullAddress()
    {
        if ($this->address && $this->city) {
            return $this->address . ', ' . $this->city;
        }

        return '';
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return Athlete
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Athlete
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return bool
     */
    public function isMinor()
    {
        return $this->birthday > new \DateTime('1999-09-24');
    }

    /**
     * @return boolean
     */
    public function isQsSportOnlyNos()
    {
        return $this->qsSportOnlyNos;
    }

    /**
     * @param boolean $qsSportOnlyNos
     */
    public function setQsSportOnlyNos($qsSportOnlyNos)
    {
        $this->qsSportOnlyNos = $qsSportOnlyNos;
    }

    /**
     * Set program
     *
     * @param string $program
     *
     * @return Athlete
     */
    public function setProgram($program)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return string
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set freeTShirt
     *
     * @param boolean $freeTShirt
     *
     * @return Athlete
     */
    public function setFreeTShirt($freeTShirt)
    {
        $this->freeTShirt = $freeTShirt;

        return $this;
    }

    /**
     * Get freeTShirt
     *
     * @return boolean
     */
    public function getFreeTShirt()
    {
        return $this->freeTShirt;
    }

    /**
     * Set tShirtSize
     *
     * @param string $tShirtSize
     *
     * @return Athlete
     */
    public function setTShirtSize($tShirtSize)
    {
        $this->tShirtSize = $tShirtSize;

        return $this;
    }

    /**
     * Get tShirtSize
     *
     * @return string
     */
    public function getTShirtSize()
    {
        return $this->tShirtSize;
    }

    /**
     * Set tShirtColor
     *
     * @param string $tShirtColor
     *
     * @return Athlete
     */
    public function setTShirtColor($tShirtColor)
    {
        $this->tShirtColor = $tShirtColor;

        return $this;
    }

    /**
     * Get tShirtColor
     *
     * @return string
     */
    public function getTShirtColor()
    {
        return $this->tShirtColor;
    }

    /**
     * Set parisCheerBra
     *
     * @param boolean $parisCheerBra
     *
     * @return Athlete
     */
    public function setParisCheerBra($parisCheerBra)
    {
        $this->parisCheerBra = $parisCheerBra;

        return $this;
    }

    /**
     * Get parisCheerBra
     *
     * @return boolean
     */
    public function getParisCheerBra()
    {
        return $this->parisCheerBra;
    }

    /**
     * Set braSize
     *
     * @param string $braSize
     *
     * @return Athlete
     */
    public function setBraSize($braSize)
    {
        $this->braSize = $braSize;

        return $this;
    }

    /**
     * Get braSize
     *
     * @return string
     */
    public function getBraSize()
    {
        return $this->braSize;
    }

    /**
     * Set licenceId
     *
     * @param string $licenceId
     *
     * @return Athlete
     */
    public function setLicenceId($licenceId)
    {
        $this->licenceId = $licenceId;

        return $this;
    }

    /**
     * Get licenceId
     *
     * @return string
     */
    public function getLicenceId()
    {
        return $this->licenceId;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     *
     * @return Athlete
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    public function needsMedicalCertificate()
    {
        return null == $this->licenceId || false === $this->qsSportOnlyNos;
    }

    public function isSurclasse()
    {
        return $this->category = 'junior_senior';
    }

    /**
     * Get qsSportOnlyNos
     *
     * @return boolean
     */
    public function getQsSportOnlyNos()
    {
        return $this->qsSportOnlyNos;
    }

    /**
     * @return File
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * @param File $pictureFile
     * @return Athlete
     */
    public function setPictureFile($pictureFile)
    {
        $this->pictureFile = $pictureFile;

        if ($pictureFile) {
            $this->firstName .= ' ';
        }

        return $this;
    }

    /**
     * Set pictureFileName
     *
     * @param string $pictureFileName
     *
     * @return Athlete
     */
    public function setPictureFileName($pictureFileName)
    {
        $this->pictureFileName = $pictureFileName;

        return $this;
    }

    /**
     * Get pictureFileName
     *
     * @return string
     */
    public function getPictureFileName()
    {
        return $this->pictureFileName;
    }

    /**
     * @return File
     */
    public function getPassportFile()
    {
        return $this->passportFile;
    }

    /**
     * @param File $passportFile
     * @return Athlete
     */
    public function setPassportFile($passportFile)
    {
        $this->passportFile = $passportFile;

        if ($passportFile) {
            $this->firstName .= ' ';
        }

        return $this;
    }

    /**
     * Set passportFileName
     *
     * @param string $passportFileName
     *
     * @return Athlete
     */
    public function setPassportFileName($passportFileName)
    {
        $this->passportFileName = $passportFileName;

        return $this;
    }

    /**
     * Get passportFileName
     *
     * @return string
     */
    public function getPassportFileName()
    {
        return $this->passportFileName;
    }

    /**
     * @return File
     */
    public function getPaperFormFile()
    {
        return $this->paperFormFile;
    }

    /**
     * @param File $paperFormFile
     * @return Athlete
     */
    public function setPaperFormFile($paperFormFile)
    {
        $this->paperFormFile = $paperFormFile;

        if ($paperFormFile) {
            $this->firstName .= ' ';
        }

        return $this;
    }

    /**
     * Set paperFormFileName
     *
     * @param string $paperFormFileName
     *
     * @return Athlete
     */
    public function setPaperFormFileName($paperFormFileName)
    {
        $this->paperFormFileName = $paperFormFileName;

        return $this;
    }

    /**
     * Get paperFormFileName
     *
     * @return string
     */
    public function getPaperFormFileName()
    {
        return $this->paperFormFileName;
    }
}
