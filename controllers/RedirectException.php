<?php

class RedirectException extends Exception
{
    public function __construct(private string $route)
    {
        parent::__construct("Redirect to {$route}");
    }

    public function getRoute(): string
    {
        return $this->route;
    }
}
