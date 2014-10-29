<?php

namespace Ceten\SsoBundle\Security\Core\User;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Ceten\CetenBundle\Entity\User;

class SsoUserProvider extends ContainerAware implements UserProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $user = $em->getRepository('CetenCetenBundle:User')->findOneBy(array('username' => $username));
        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" not found.', $username));
        }
        return $user;
    }

    /**
     * Load user for a given sso user key
     * @param  string $sessionKey
     * @return
     */
    public function getUserForSessionKey($sessionKey)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $session = $em->getRepository('CetenCetenBundle:Session')->findOneBy(array('name' => $sessionKey));

        return (!$session) ? null : $session->getUser();
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === 'Ceten\\CetenBundle\\Entity\\User';
    }
}