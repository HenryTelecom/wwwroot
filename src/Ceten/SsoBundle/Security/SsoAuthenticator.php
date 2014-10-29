<?php

namespace Ceten\SsoBundle\Security;

use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

use Ceten\SsoBundle\Security\Core\User\SsoUserProvider;

class SsoAuthenticator implements SimplePreAuthenticatorInterface
{
    protected $userProvider;
    protected $ssoKey;

    public function __construct(SsoUserProvider $userProvider, $ssoKey)
    {
        $this->userProvider = $userProvider;
        $this->ssoKey = $ssoKey;
    }

    public function createToken(Request $request, $providerKey)
    {
        // this key always exist in cookies
        $sessionKey = $request->cookies->get($this->ssoKey);
        return new PreAuthenticatedToken(
            'anon.',
            $sessionKey,
            $providerKey
        );
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $sessionKey = $token->getCredentials();
        $user = $this->userProvider->getUserForSessionKey($sessionKey);

        if (!$user) {
            return new AnonymousToken($providerKey, 'anon.');
        }

        return new PreAuthenticatedToken(
            $user,
            $sessionKey,
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return ($token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey) || $token instanceof AnonymousToken;
    }
}