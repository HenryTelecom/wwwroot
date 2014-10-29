<?php

namespace Oauth\SsoBundle\Security\Core\User;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;

use Ceten\CetenBundle\Entity\User;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class OAuthUserProvider extends ContainerAware implements UserProviderInterface, OAuthAwareUserProviderInterface
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
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $res = $response->getResponse();
        
        // Only allow telecomnancy.net users
        if (!isset($res['hd']) || strtolower($res['hd']) !== 'telecomnancy.net') {
            throw new UsernameNotFoundException('Only telecomnancy.net user allowed');
        }


        try {
            $user = $this->loadUserByUsername($res['email']);
        } catch (UsernameNotFoundException $e) {
            // Create new user
            $user = new User();
            $user->setUsername($res['email']);
            $user->setFirstname($res['given_name']);
            $user->setLastname($res['family_name']);
            $user->addDefaultRoles();
            $user->setEnabled(true);
            $user->setOauth(true);
            
            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();
        }

        return $user;
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
