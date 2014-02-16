<?php
namespace mheap\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\DBAL\Connection;
use mheap\User;

class UserProvider implements UserProviderInterface
{
    private $conn;
    private $salt;
 
    public function __construct(Connection $conn, $salt)
    {
        $this->conn = $conn;
        $this->salt = $salt;
    }
 
    public function loadUserByUsername($email)
    {
        $stmt = $this->conn->executeQuery('SELECT u.* FROM user u WHERE u.email = ?', array($email));

        $user = $stmt->fetch();
        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Email "%s" does not exist.', $email));
        }

        $userObject = new User($user['id'], $user['email'], $user['password'], $user['name'], $this->salt, explode(',', $user['roles']));

        return $userObject;
    }
 
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
 
        return $this->loadUserByUsername($user->getUsername());
    }
 
    public function supportsClass($class)
    {
        return $class === 'mheap\User';
    }
}
