<?php
/**
 * 月之暗面kimi
 */

namespace Cyokup\EasyAiChat\Gateways;

use App\Models\AiConfig;

class Moonshot extends Gateway
{
    public function chat($content, $parameters = [], $stream = false)
    {
        $baseUrl = "https://api.moonshot.cn/v1/chat/completions";
        $header[] = "Bearer " . $this->config->get('api_key');
        $header[] = "Content-Type:application/json";
        $params = [
            'model' => $parameters['model'] ?? 'moonshot-v1-8k',
            'messages' => [$content],
            'temperature' => $parameters['temperature'] ?? 0.3,
            'stream' => $stream,
        ];
        $params = json_encode($params, JSON_UNESCAPED_UNICODE);
        try {
            $result = $this->httpCurl($baseUrl, 'POST', $params, $header);
            $result = json_decode($result, true);
            if (isset($result['choices'][0]['message']['content'])) {
                return [$result['choices'][0]['message']['content'], $result['usage']['total_tokens']];
            }
            return [$result['message'] ?? 'data error', 0];
        } catch (\Exception $e) {
            return [$e->getMessage(), 0];
        }
    }

    public function embeddings($content, $parameters = [])
    {
        return ['Unsupported', 0];
    }
}
