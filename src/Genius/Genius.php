<?php
declare(strict_types=1);

namespace Genius;

use Genius\Authentication\OAuth2;
use Genius\Exception\ConnectGeniusException;
use Genius\Resources;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Genius
{
    /** @var RequestFactoryInterface */
    protected $requestFactory;
    
    /** @var PluginClient */
    protected $httpClient;
    
    /** @var Authentication|OAuth2 */
    protected $authentication;
    
    /** @var array All created resource objects */
    protected $resourceObjects = [];
    
    /**
     * ClientGenius constructor.
     *
     * @param Authentication $authentication
     * @param HttpClient|null $httpClient
     * @throws ConnectGeniusException
     */
    public function __construct(Authentication $authentication, ?HttpClient $httpClient = null)
    {
        $this->authentication = $authentication;
        $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        
        $connection = new ConnectGenius($authentication);
        
        if ($httpClient !== null) {
            $connection->setHttpClient($httpClient);
        }
        
        $this->httpClient = $connection->createConnection();
    }

    public function getHttpClient(): PluginClient
    {
        return $this->httpClient;
    }

    /**
     * @return Authentication|OAuth2
     */
    public function getAuthentication(): Authentication
    {
        return $this->authentication;
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        return Psr17FactoryDiscovery::findStreamFactory();
    }

    public function getAccountResource(): Resources\AccountResource
    {
        return new Resources\AccountResource($this);
    }

    public function getAnnotationsResource(): Resources\AnnotationsResource
    {
        return new Resources\AnnotationsResource($this);
    }

    public function getArtistsResource(): Resources\ArtistsResource
    {
        return new Resources\ArtistsResource($this);
    }

    public function getReferentsResource(): Resources\ReferentsResource
    {
        return new Resources\ReferentsResource($this);
    }

    public function getSearchResource(): Resources\SearchResource
    {
        return new Resources\SearchResource($this);
    }

    public function getSongsResource(): Resources\SongsResource
    {
        return new Resources\SongsResource($this);
    }

    public function getWebPagesResource(): Resources\WebPagesResource
    {
        return new Resources\WebPagesResource($this);
    }
}
