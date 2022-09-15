<?php

namespace Fabgg\UrlSearchParams;

class URLSearchParams
{

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param array|string|null $search
     */
    public function __construct($search = null)
    {
        if($search) {
            $this->parseSearch($search);
        }
    }

    public function __toString() :string
    {
        $this->toString();
    }

    private function parseSearch($search): void
    {
        /** if $search is array */
        if (is_array($search)) {
            foreach ($search as $key => $value) {
                $this->appendTo($key, $value);
            }
        } elseif (is_string($search)) {
            /** split string */
            $searchString = urldecode($search);
            $questionMarkPosition = strpos($searchString, '?');
            if(0 <= $questionMarkPosition) {
                $searchString = substr($searchString, $questionMarkPosition + 1);
            }
            $pairs = explode('&', $searchString);
            foreach($pairs as $pair){
                $position = strpos($pair,'=');
                $key = substr($pair, 0, $position);
                $value = substr($pair, $position + 1);
                $value = str_replace('+', ' ', $value);
                $this->appendTo($key, $value);
            }
        }
    }

    public function merge($search) :void
    {
        $this->parseSearch($search);
    }

    public function append(array $pair):void
    {
        foreach($pair as $key => $value) {
            $this->appendTo($key, $value);
        }
    }

    public function delete($key): void
    {
        if($this->has($key)) {
            unset($this->parameters[$key]);
        }
    }

    public function appendTo($key, $value):void
    {
        if (is_array($value) && count($value)) {
            foreach ($value as $subValue) {
                $this->appendTo($key, $subValue);
            }
        } else {
            $this->parameters[$key] = $this->has($key)
                ? array_merge($this->parameters[$key], [$value])
                : [$value];
        }
    }

    public function get($key) {
        return array_key_exists($key, $this->parameters) ? array_values(array_unique($this->parameters[$key])) : false;
    }

    public function getAll($key = null) {
        if($key) {
            return $this->get($key);
        }
        $response = [];
        foreach($this->parameters as $k => $value) {
            $response[$k] = array_values(array_unique($value));
        }
        return $response;
    }


    public function keys(){
        return array_keys($this->parameters);
    }

    public function has($key) : bool {
        return array_key_exists($key, $this->parameters) && 0 < count(array_unique($this->parameters[$key]));
    }

    public function toString() :string
    {
        $string = '';
        foreach($this->getAll() as $key => $array) {
            foreach($array as $value) {
                if(0 < strlen($string)) {
                    $string .= '&';
                }
                $value = str_replace('%20','+', urlencode($value));
                $string .=  urlencode($key).'='. $value;
            }
        }

        return 0 < strlen($string) ? '?'.$string: $string;
    }
}
