<?php

namespace Cyokup\EasyAiChat;

class EasyAiChat
{

    /**
     * 设置需要实现的类名
     * @param $name
     * @return mixed
     */
    public function gateway($name, $config)
    {
        // $name转成首字母大写的类名
        $className = 'Cyokup\\EasyAiChat\\Gateways\\' . ucfirst($name);
        if (!class_exists($className)) {
            throw new \Exception("Unsupported strategy \"{$className}\"");
        }
        return new $className($config);
    }

}
