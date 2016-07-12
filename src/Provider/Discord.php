<?php

namespace League\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\Exception\DiscordIdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Discord extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Domain
     *
     * @var string
     */
    public $domain = 'https://discordapp.com/';

    /**
     * Api domain
     *
     * @var string
     */
    public $apiDomain = 'https://discordapp.com/api';

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->domain . '/api/oauth2/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     * @param  array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->domain . '/api/oauth2/token';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->apiDomain . '/users/@me';

    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return ['identify', 'email'];
    }

    /**
     * Check a provider response for errors.
     *
     * @link   https://developer.github.com/v3/#client-errors
     * @link   https://developer.github.com/v3/oauth/#common-errors-for-the-access-token-request
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw DiscordIdentityProviderException::clientException($response, $data);
        } elseif (isset($data['error'])) {
            throw DiscordIdentityProviderException::oauthException($response, $data);
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     * @return League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        $user = new DiscordResourceOwner($response);

        return $user->setDomain($this->domain);
    }
}
