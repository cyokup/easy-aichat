<?php

namespace Cyokup\EasyAiChat\Gateways;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Qwen extends Gateway
{
    /**
     * 设置认证的头信息
     * @return string[]
     */
    private function setHeader()
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer " . $this->config->get('key')
        ];
    }

    public function chat($content)
    {
        $client = new Client();
        $body = [
            'model' => 'qwen-turbo',
            'input' => [
                'messages' => [['role' => 'user', 'content' => $content]]
            ]
        ];
        try {
            $response = $client->post('https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation', [
                'headers' => $this->setHeader(),
                RequestOptions::JSON => $body,
            ]);
            // 获取响应内容
            $responseData = $response->getBody()->getContents();
            $result = json_decode($responseData, true);
            if (isset($result['output']['text'])) {
                return $result['output']['text'];
            }
            return $result['message'];
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function embeddings($content)
    {
        $client = new  Client();
        $body = [
            'model' => 'text-embedding-v1',
            'type' => 'db',
            'input' => [
                'texts' => [$content]
            ],
        ];
        try {
            $response = $client->post('https://dashscope.aliyuncs.com/api/v1/services/embeddings/text-embedding/text-embedding', [
                'headers' => $this->setHeader(),
                RequestOptions::JSON => $body,
            ]);
            // 获取响应内容
            $responseData = $response->getBody()->getContents();
            $result = json_decode($responseData, true);
            if (isset($result['usage']['total_tokens']) && isset($result['output']['embeddings'][0]['embedding'])) {
                return $result['output']['embeddings'][0]['embedding'];
            }
            return $result['message'];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
