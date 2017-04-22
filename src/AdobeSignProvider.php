<?php


namespace ajohnson6494\AdobeSign;


use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class AdobeSignProvider extends AbstractProvider
{
    protected $scope;

    protected $baseAuthorizationUrl = 'https://secure.na1.echosign.com/public/oauth';

    protected $baseAccessTokenUrl = 'https://api.na1.echosign.com/oauth/token';

    protected $baseRefreshTokenUrl = 'https://api.na1.echosign.com/oauth/refresh';

    /**
     * AdobeSign constructor.
     * @param array $options
     * @param array $collaborators
     */
    public function __construct(array $options, array $collaborators = [])
    {
        if (isset($options['scope'])) {
            $this->scope = $options['scope'];
        }

        parent::__construct($options, $collaborators);
    }

    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->baseAuthorizationUrl;
    }

    /**
     * Returns the base URL for requesting an access token.
     *
     * Eg. https://oauth.service.com/token
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->baseAccessTokenUrl;
    }

    /**
     * Returns the base URL for requesting an refresh token.
     *
     * Eg. https://oauth.service.com/token
     *
     * @return string
     */
    public function getBaseRefreshTokenUrl()
    {
        return $this->baseRefreshTokenUrl;
    }

    /**
     * Returns the URL for requesting the resource owner's details.
     *
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        // TODO: Implement getResourceOwnerDetailsUrl() method.
    }

    /**
     * Returns the default scopes used by this provider.
     *
     * This should only be the scopes that are required to request the details
     * of the resource owner, rather than all the available scopes.
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return isset($this->scope) ? $this->scope : [];
    }

    /**
     * Returns the string that should be used to separate scopes when building
     * the URL for requesting an access token.
     *
     * @return string Scope separator, defaults to ','
     */
    protected function getScopeSeparator()
    {
        return '+';
    }

    /**
     * Builds the authorization URL's query string.
     *
     * @param  array $params Query parameters
     * @return string Query string
     */
    protected function getAuthorizationQuery(array $params)
    {
        if (isset($params['scope'])) {
            $scope = $params['scope'];
            unset($params['scope']);
            $query = parent::getAuthorizationQuery($params);

            return "$query&scope=$scope";
        } else {
            return parent::getAuthorizationQuery($params);
        }
    }

    /**
     * Checks a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  array|string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        // TODO: Implement checkResponse() method.
    }

    /**
     * Generates a resource owner object from a successful resource owner
     * details request.
     *
     * @param  array $response
     * @param  AccessToken $token
     * @return ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        // TODO: Implement createResourceOwner() method.
    }

    /**
     * Returns the authorization headers used by this provider.
     *
     * Typically this is "Bearer" or "MAC". For more information see:
     * http://tools.ietf.org/html/rfc6749#section-7.1
     *
     * No default is provided, providers must overload this method to activate
     * authorization headers.
     *
     * @param  mixed|null $token Either a string or an access token instance
     * @return array
     */
    protected function getAuthorizationHeaders($token = null)
    {
        return [
            'Access-Token' => $token
        ];
    }

    /**
     * Returns the full URL to use when requesting an access token.
     *
     * @param array $params Query parameters
     * @return string
     */
    protected function getAccessTokenUrl(array $params)
    {
        if (isset($params['refresh_token'])) {
            return $this->getBaseRefreshTokenUrl();
        } else {
            return $this->getBaseAccessTokenUrl($params);
        }
    }
}