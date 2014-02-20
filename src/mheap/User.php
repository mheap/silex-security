<?php

namespace mheap;

class User implements \Symfony\Component\Security\Core\User\UserInterface {
	
	private $id;
	private $username;
	private $password;
	private $name;
	private $salt;
	private $roles;
	private $emails;

	public function __construct($id, $email, $password, $name, $salt, array $roles)
	{
		$this->id = $id;
		$this->username = $email;
		$this->password = $password;
		$this->name = $name;
		$this->salt = $salt;
		$this->roles = $roles;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getRoles()
	{
		return $this->roles;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getSalt()
	{
		return $this->salt;
	}

	public function getEmail()
	{
		return $this->getUsername();
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function eraseCredentials()
	{
	}

	public function isEqualTo(UserInterface $user)
	{
		if (!$user instanceof User) {
			return false;
		}

		if ($this->password !== $user->getPassword()) {
			return false;
		}

		if ($this->getSalt() !== $user->getSalt()) {
			return false;
		}

		if ($this->username !== $user->getUsername()) {
			return false;
		}

		return true;
	}

}


