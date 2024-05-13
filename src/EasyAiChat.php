<?php

namespace Cyokup\EasyAiChat;

use Cyokup\EasyAiChat\Support\Config;

class EasyAiChat
{
    protected $config;


    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * 设置需要实现的类名
     * @param $name
     * @return mixed
     */
    public function gateway($name)
    {
        // $name转成首字母大写的类名
        $className = 'Cyokup\\EasyAiChat\\Gateways\\' . ucfirst($name);
        if (!class_exists($className)) {
            throw new \Exception("Unsupported strategy \"{$className}\"");
        }
        $config = $this->config->get($name);
        return new $className($config);
    }

}
