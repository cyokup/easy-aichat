<?php

namespace Cyokup\EasyAiChat\Gateways;

use Cyokup\EasyAiChat\Support\Config;
use Cyokup\EasyAiChat\Traits\Helper;

/**
 * 抽象类，定位必须实现的方法和共享的方法
 */
abstract class Gateway
{
    use Helper;

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
    abstract public function embeddings($content, $parameters = []);

    /**
     * 聊天方法
     * @return mixed
     */
    abstract public function chat($content, $parameters = [], $stream = false);

}
