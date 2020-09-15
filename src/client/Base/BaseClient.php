<?php

namespace EPort\EPortWmsClient\Base;

use EPort\EPortWmsClient\Application;
use EPort\EPortWmsClient\Base\Exceptions\ClientError;
use GuzzleHttp\RequestOptions;

/**
 * 底层请求.
 */
class BaseClient
{
    use MakesHttpRequests;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $uri = '';

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->baseUri = $this->app['config']->get('base_uri');
    }

    /**
     * Set params.
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Set uri.
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Make a patch request.
     *
     * @throws ClientError
     */
    public function httpPatchJson()
    {
        return $this->requestPatch();
    }

    /**
     * Make a Put request.
     *
     * @throws ClientError
     */
    public function httpPutJson()
    {
        return $this->requestPut();
    }

    /**
     * Make a post request.
     *
     * @throws ClientError
     */
    public function httpPostJson()
    {
        return $this->requestPost();
    }

    /**
     * Make a get request.
     * @throws ClientError
     */
    public function httpGet()
    {
        return $this->requestGet();
    }

    /**
     * @throws ClientError
     */
    protected function requestPatch()
    {
        $options[RequestOptions::JSON] = $this->app['md5']->getRequestParams($this->params);
        $options[RequestOptions::HEADERS] = $this->app['md5']->getRequestHeaders();

        return $this->request('PATCH', $this->uri, $options);
    }

    /**
     * @throws ClientError
     */
    protected function requestPut()
    {
        $options[RequestOptions::JSON] = $this->app['md5']->getRequestParams($this->params);
        $options[RequestOptions::HEADERS] = $this->app['md5']->getRequestHeaders();

        return $this->request('PUT', $this->uri, $options);
    }

    /**
     * @throws ClientError
     */
    protected function requestPost()
    {
        $options[RequestOptions::JSON] = $this->app['md5']->getRequestParams($this->params);
        $options[RequestOptions::HEADERS] = $this->app['md5']->getRequestHeaders();

        return $this->request('POST', $this->uri, $options);
    }

    /**
     * @throws ClientError
     */
    protected function requestGet()
    {
        $options[RequestOptions::JSON] = $this->app['md5']->getRequestParams($this->params);
        $options[RequestOptions::HEADERS] = $this->app['md5']->getRequestHeaders();

        return $this->request('GET', $this->uri, $options);
    }
}