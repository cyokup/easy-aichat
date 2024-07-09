<?php
/**
 * 通义千问
 */

namespace Cyokup\EasyAiChat\Gateways;

class Qwen extends Gateway
{
    public function chat($content, $parameters = [], $stream = false)
    {
        $baseUrl = "https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation";
        $header[] = "Authorization:Bearer " . $this->config->get('api_key');
        $header[] = "Content-Type:application/json";
        // 流式输出
        if ($stream) {
            $header['X-DashScope-SSE'] = 'enable';
        }
        $params = [
            'model' => $parameters['model'] ?? 'qwen-turbo',
            'input' => [
                'messages' => $content
            ]
        ];
        $params = json_encode($params, JSON_UNESCAPED_UNICODE);
        try {
            $result = $this->httpCurl($baseUrl, 'POST', $params, $header);
            $result = json_decode($result, true);
            if (isset($result['output']['text'])) {
                return [$result['output']['text'], $result['usage']['total_tokens']];
            }
            return [$result['message'] ?? 'data error', 0];
        } catch (\Exception $e) {
            return [$e->getMessage(), 0];
        }
    }

    public function embeddings($content, $parameters = [])
    {
        $baseUrl = "https://dashscope.aliyuncs.com/api/v1/services/embeddings/text-embedding/text-embedding";
        $header[] = "Authorization:Bearer " . $this->config->get('api_key');
        $header[] = "Content-Type:application/json";
        $params = [
            'model' => $parameters['model'] ?? 'text-embedding-v1',
            'type' => 'db',
            'input' => [
                'texts' => [$content]
            ],
        ];
        $params = json_encode($params, JSON_UNESCAPED_UNICODE);
        try {
            $result = $this->httpCurl($baseUrl, 'POST', $params, $header);
            $result = json_decode($result, true);
            if (isset($result['usage']['total_tokens']) && isset($result['output']['embeddings'][0]['embedding'])) {
                return [$result['output']['embeddings'][0]['embedding'], $result['usage']['total_tokens']];
            }
            return [['data error'], 0];
        } catch (\Exception $e) {
            return [$e->getMessage(), 0];
        }
    }
}
