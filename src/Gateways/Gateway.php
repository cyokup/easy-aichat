<?php

namespace Cyokup\EasyAiChat\Gateways;

use Cyokup\EasyAiChat\Support\Config;

/**
 * 抽象类，定位必须实现的方法和共享的方法
 */
abstract class Gateway
{

    public $config;

    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * 文字转向量
     * @param $content
     * @return mixed
     */
    abstract public function embeddings($content);

    /**
     * 聊天方法
     * @return mixed
     */
    abstract public function chat($content);

}
