<?php

namespace Cyokup\EasyAiChat\Gateways;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Qwen extends Gateway
{

    public function chat($content, $parameters = [])
    {
        $client = new Client();
        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer " . $this->config->get('api_key')
        ];
        // 流式输出
        if ($parameters['stream']) {
            $header['X-DashScope-SSE'] = 'enable';
        }
        $body = [
            'model' => $parameters['model'] ?? 'qwen-turbo',
            'input' => [
                'messages' => [$content]
            ]
        ];
        try {
            $response = $client->post('https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation', [
                'headers' => $header,
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

    public function embeddings($content, $parameters = [])
    {
        $client = new  Client();
        $body = [
            'model' => $parameters['model'] ?? 'text-embedding-v1',
            'type' => 'db',
            'input' => [
                'texts' => [$content]
            ],
        ];
        try {
            $response = $client->post('https://dashscope.aliyuncs.com/api/v1/services/embeddings/text-embedding/text-embedding', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer " . $this->config->get('api_key')
                ],
                RequestOptions::JSON => $body,
            ]);
            // 获取响应内容
            $responseData = $response->getBody()->getContents();
            $result = json_decode($responseData, true);
            if (isset($result['usage']['total_tokens']) && isset($result['output']['embeddings'][0]['embedding'])) {
                return [$result['output']['embeddings'][0]['embedding'], $result['usage']['total_tokens']];
            }
            return [$result['message'], 0];
        } catch (\Exception $e) {
            return [$e->getMessage(), 0];
        }
    }
}
