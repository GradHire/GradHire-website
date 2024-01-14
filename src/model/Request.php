<?php

namespace app\src\model;

class Request
{
    private array $routeParams = [];

    public function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) $path = substr($path, 0, $position);
        return $path;
    }

    public function getBody(): array
    {
        $body = $this->isGet() ? $_GET : ($this->isPost() ? $_POST : []);
        $data = [];

        foreach ($body as $key => $value) {
            if (is_array($value))
                $data[$key] = array_map(function ($item) {
                    return filter_var($item, FILTER_SANITIZE_SPECIAL_CHARS);
                }, $value);
            else
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $data;
    }

    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isPost()
    {
        return $this->getMethod() === 'post';
    }

    public function getRouteParams()
    {
        return $this->routeParams;
    }

    public function setRouteParams($params)
    {
        $this->routeParams = $params;
        return $this;
    }

    public function getRouteParam($param, $default = null)
    {
        return $this->routeParams[$param] ?? $default;
    }
}
