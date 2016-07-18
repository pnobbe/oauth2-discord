<?php namespace League\OAuth2\Client\Provider;

class DiscordResourceOwner implements ResourceOwnerInterface
{
    /**
     * Domain
     *
     * @var string
     */
    protected $domain;

    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->response['id'] ?: null;
    }

    /**
     * Get resource owner email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->response['email'] ?: null;
    }

    /**
     * Get resource owner name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->response['username'] ?: null;
    }

    /**
     * Get resource owner nickname
     *
     * @return string|null
     */
    public function getNickname()
    {
        return $this->response['username'] ?: null;
    }

    /**
     * Get resource owner url
     *
     * @return string|null
     */
    public function getUrl()
    {
        return trim($this->domain.'/'.$this->getNickname()) ?: null;
    }

    /**
     * Set resource owner domain
     *
     * @param  string $domain
     *
     * @return ResourceOwner
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Retrieve picture url
     *
     * @return string|null
     */
    public function getPictureUrl() {
        return "https://discordapp.com/api/users/" . $this->response['id'] . "/avatars/" . $this->response['avatar'] . ".jpg" ?: null;
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
