<?php
namespace CCMS\User;

use BroCom\Broker\Broker;
use CCMS\Parameters\Parameters;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $role;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $firstName;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastName;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $email;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $password;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
	protected $emailVerified;

    /**
     * @ORM\Column(type="string")
     */
    protected $emailVerifyHash;

    /**
     * @ORM\OneToOne(targetEntity="BroCom\Broker\Broker", mappedBy="user")
     * @var Broker|null
     */
    protected $broker;

    public function setEmailVerified(){
        $this->emailVerified = true;
        $this->emailVerifyHash = null;
    }

	public function __construct($data)
    {
        $this->loadData($data);
    }

    public function update(array $data){
        $this->loadData($data + $this->toArray());
    }

    /**
	 * @return mixed
	 */
	public function getRole()
	{
		return $this->role;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}

    /**
     * @return mixed
     */
    public function getEmailVerifyHash()
    {
        return $this->emailVerifyHash;
    }

	public function setPassword($password)
	{
		$this->password = self::calculateHash($password);
		return $this;
	}

	/**
	 * @param $password
	 * @return string
	 */
	public static function calculateHash($password)
	{
		return password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
	}

	/**
	 * @param string $password
	 * @return bool
	 */
	public function verifyPassword($password)
	{
		return password_verify($password, $this->password);
	}

    public function toArray()
    {
        return [
            'id' => $this->id,
            'brokerId' => $this->broker !== null ? $this->broker->getId() : null,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }

    private function loadData($data)
    {
        $parameters = Parameters::from($data);
        $this->role = $parameters->getString('role');
        $this->firstName = $parameters->getString('firstName');
        $this->lastName = $parameters->getString('lastName');
        $this->email = $parameters->getString('email');
        $this->setPassword($parameters->getString('password'));
        $this->emailVerified = $parameters->getBool('emailVerified', false);
        $this->createEmailVerifyHash();
    }

    private function createEmailVerifyHash()
    {
        $this->emailVerifyHash = substr(md5(openssl_random_pseudo_bytes(20)),-32);
    }
} 