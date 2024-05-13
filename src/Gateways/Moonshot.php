<?php

namespace Cyokup\EasyAiChat\Gateways;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Moonshot extends Gateway
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
            'model' => 'moonshot-v1-8k',
            'messages' => [['role' => 'user', 'content' => $content]],
            'temperature' => 0.3
        ];
        try {
            $response = $client->post('https://api.moonshot.cn/v1/chat/completions', [
                'headers' => $this->setHeader(),
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
