<?php

namespace Cyokup\EasyAiChat\Gateways;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Moonshot extends Gateway
{
    public function chat($content, $parameters = [])
    {
        $client = new Client();
        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer " . $this->config->get('api_key')
        ];
        $body = [
            'model' => $parameters['model'] ?? 'moonshot-v1-8k',
            'messages' => [$content],
            'temperature' => $parameters['temperature'] ?? 0.3,
            'stream' => $parameters['stream'] ?? 'false',
        ];
        try {
            $response = $client->post('https://api.moonshot.cn/v1/chat/completions', [
                'headers' => $header,
                RequestOptions::JSON => $body,
            ]);
            // 获取响应内容
            $responseData = $response->getBody()->getContents();
            $result = json_decode($responseData, true);
            if (isset($result['choices'][0]['message']['content'])) {
                return [$result['choices'][0]['message']['content'], $result['usage']['total_tokens']];
            }
            return [$result['message'], 0];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function embeddings($content)
    {
    }
}
