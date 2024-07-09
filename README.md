### 简单快速调用主流AI大模型接口

#### 安装命令：

`composer require cyokup/easy-aichat`

#### 特点

- 支持多种主流大模型，一套写法兼容所有平台
- 调用简单方便，统一的返回值格式
- 欢迎提PR一起完善项目

#### 已支持大模型

+ [x] [qwen][阿里通义千问](https://help.aliyun.com/document_detail/2400395.html)
+ [x] [moonshot][Moonshot AI](https://platform.moonshot.cn/)

#### 使用方法

```
use Cyokup\EasyAiChat\EasyAiChat;
$aiModel = new EasyAiChat();
// 使用通义千问实现文字转向量
list($embedding, $tokens) = $aiModel->gateway('qwen', ['api_key'=>'xxx'])->embeddings("你好");
// 使用通义千问实现问答
$content = [      
            {
                "role": "system",
                "content": "You are a helpful assistant."
            },
            {
                "role": "user",
                "content": "hello"
            }
        ];
// 设置模型名字     
$parameters['model'] = 'qwen-turbo';  
// 最后一个参数true标识流式输出，默认false      
$aiModel->gateway('qwen', ['api_key'=>'xxx'])->chat("你好",$parameters,true);
```

####      

### 其他

- 作者微信：cyokup
