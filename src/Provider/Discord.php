<?php

namespace pnobbe\OAuth2\Client\Provider;
use pnobbe\OAuth2\Client\Provider\Exception\DiscordRequestException;
use pnobbe\OAuth2\Client\Provider\Parts\User;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Discord extends AbstractProvider
{
    /**
     * The base API url.
     *
     * @var string Base API url.
     */
    const BASE_API_URL = 'https://discord.com/api';
    /**
     * An array of available OAuth scopes.
     *
     * @var array Available scopes.
     */
    protected $scopes = [
        'identify', // Allows you to retrieve user data (except for email)
        'email', // The same as identify but with email
        'connections', // Allows you to retrieve connected YouTube and Twitch accounts
        'guilds', // Allows you to retrieve the guilds the user is in
        'guilds.join', // Allows you to join the guild for the user
        'bot', // Defines a bot
    ];
    /**
     * {@inheritdoc}
     */
    public function getBaseAuthorizationUrl()
    {
        return self::BASE_API_URL.'/oauth2/authorize';
    }
    /**
     * {@inheritdoc}
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return self::BASE_API_URL.'/oauth2/token';
    }
    /**
     * {@inheritdoc}
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return self::BASE_API_URL.'/users/@me';
    }
    /**
     * {@inheritdoc}
     */
    protected function getDefaultScopes()
    {
        return ['email', 'identify'];
    }
    /**
     * {@inheritdoc}
     */
    public function getScopeSeparator()
    {
        return ' ';
    }
    /**
     * {@inheritdoc}
     */
    public function getAuthorizationHeaders($token = null)
    {
        return [
            'Authorization' => 'Bearer ' . $token->getToken(),
        ];
    }
    /**
     * {@inheritdoc}
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error'])) {
            throw new DiscordRequestException('Error in response from Discord: '.$data['error']);
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new User($this, $token, (array) $response);
    }
    /**
     * Runs a request.
     *
     * @param string      $method The HTTP method.
     * @param string      $url    The URL.
     * @param AccessToken $token  The auth token.
     *
     * @return array Response.
     */
    public function request($method, $url, $token)
    {
        $request = $this->getAuthenticatedRequest(
            $method, $url, $token
        );
        return $this->getResponse($request);
    }
    /**
     * Gets the guilds endpoint.
     *
     * @return string Endpoint.
     */
    public function getGuildsEndpoint()
    {
        return self::BASE_API_URL.'/users/@me/guilds';
    }
    /**
     * Gets the connections endpoint.
     *
     * @return string Endpoint.
     */
    public function getConnectionsEndpoint()
    {
        return self::BASE_API_URL.'/users/@me/connections';
    }
    /**
     * Gets the accept invite endpoint.
     *
     * @param string $invite The invite.
     *
     * @return string Endpoint.
     */
    public function getInviteEndpoint($invite)
    {
        return self::BASE_API_URL.'/invites/'.$invite;
    }
    /**
     * Builds a part.
     *
     * @param string      $part       The part to build.
     * @param AccessToken $token      The access token.
     * @param array       $attributes Array of attributes.
     *
     * @return Part A part.
     */
    public function buildPart($part, AccessToken $token, $attributes = [])
    {
        return new $part($this, $token, (array) $attributes);
    }
}
