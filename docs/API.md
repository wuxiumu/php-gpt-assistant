# API 文档 — php-gpt-assistant

本项目所有 API 均为 RESTful 风格，返回统一 JSON 结构，参数区分 `必填/选填`，所有受保护接口都需 Header 携带：
```

Authorization: Bearer {token}

```
---

## 目录

1. [用户注册与登录](#用户注册与登录)
2. [用户相关](#用户相关)
3. [OpenAI 配置](#OpenAI-配置)
4. [头像上传/OSS](#头像上传-OSS)
5. [聊天相关](#聊天相关)
6. [统计](#统计)
7. [错误码说明](#错误码说明)
8. [请求示例](#请求示例)

---

## 1. 用户注册与登录

### 1.1 用户注册

- **URL**：`POST /api/register`

- **参数**：

  | 参数                | 类型     | 必填 | 说明           |
  |---------------------|---------|-----|----------------|
  | name                | string  | 是  | 昵称           |
  | email               | string  | 是  | 邮箱           |
  | password            | string  | 是  | 密码           |
  | password_confirmation | string | 是  | 重复密码      |

- **返回**：
```json
{
  "token": "jwt_token_string",
  "user": { "id":1, "name":"张三", ... }
}
```



### **1.2 用户登录**





- **URL**：POST /api/login
- **参数**：



| **参数** | **类型** | **必填** | **说明** |
| -------- | -------- | -------- | -------- |
| email    | string   | 是       | 邮箱     |
| password | string   | 是       | 密码     |



-
- **返回**：



```
{
  "token": "jwt_token_string",
  "user": { ... }
}
```



------





## **2. 用户相关**







### **2.1 获取当前用户信息**





- **URL**：GET /api/user
- **需认证**：是
- **返回**：



```
{
  "id":1,
  "name":"张三",
  "email":"test@example.com",
  "api_key":"sk-xxxx",
  "avatar":"https://oss.xxx.com/avatars/xxx.png"
}
```



------





## **3. OpenAI 配置**







### **3.1 绑定/修改 OpenAI API Key**





- **URL**：POST /api/user/api-key
- **需认证**：是
- **参数**：



| **参数** | **类型** | **必填** | **说明**       |
| -------- | -------- | -------- | -------------- |
| api_key  | string   | 是       | OpenAI API key |



-
- **返回**：



```
{ "message":"API key updated successfully" }
```



------





## **4. 头像上传/OSS**







### **4.1 上传头像（后端中转）**





- **URL**：POST /api/user/avatar
- **需认证**：是
- **参数**：form-data avatar（图片文件，最大10MB）
- **返回**：



```
{ "avatar":"https://oss.xxx.com/avatars/xxx.png" }
```



### **4.2 OSS 直传签名（前端直传用）**





- **URL**：GET /api/oss-signature
- **需认证**：是
- **返回**：



```
{
  "accessid":"xxx",
  "host":"https://your-bucket.oss-cn-xxx.aliyuncs.com",
  "policy":"...",
  "signature":"...",
  "expire":1718860000,
  "key":"avatars/uid_xxx.png"
}
```



### **4.3 保存头像 URL**





- **URL**：POST /api/user/avatar-url
- **需认证**：是
- **参数**：



| **参数** | **类型** | **必填** | **说明** |
| -------- | -------- | -------- | -------- |
| url      | string   | 是       | 头像地址 |



-
- **返回**：



```
{ "avatar":"https://oss.xxx.com/avatars/xxx.png" }
```



------





## **5. 聊天相关**







### **5.1 发送消息（Chat）**





- **URL**：POST /api/chat
- **需认证**：是
- **参数**：



| **参数**      | **类型** | **必填** | **说明**                      |
| ------------- | -------- | -------- | ----------------------------- |
| message       | string   | 是       | 用户输入                      |
| system_prompt | string   | 否       | 系统 prompt                   |
| model         | string   | 否       | 使用的模型 (如 gpt-3.5-turbo) |



-
- **返回**：



```
{
  "reply":"你好，有什么可以帮您？",
  "usage": { "prompt_tokens":12, "completion_tokens":15, "total_tokens":27 }
}
```



### **5.2 获取聊天历史**





- **URL**：GET /api/chat/history
- **需认证**：是
- **返回**：



```
[
  {
    "id": 1,
    "prompt": "今天天气如何？",
    "response": "今天天气晴朗，适合出行。",
    "created_at":"2024-06-17 17:20:31"
  }
]
```



------





## **6. 统计**







### **6.1 Token 消耗统计**





- **URL**：GET /api/stat/token-usage
- **需认证**：是
- **返回**：



```
{
  "days": [
    {"day":"2024-06-16", "total_tokens":4321},
    {"day":"2024-06-17", "total_tokens":2244}
  ],
  "total": 6565
}
```



------





## **7. 错误码说明**





- 401 未认证/登录失效
- 400 参数错误/缺少必填
- 403 权限不足
- 429 访问频率过高
- 500 服务端异常





------





## **8. 请求示例**







### **登录请求示例**



```
POST /api/login
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "123456"
}
```



### **发送聊天消息请求示例**



```
POST /api/chat
Authorization: Bearer {token}
Content-Type: application/json

{
  "message": "你是谁？",
  "model": "gpt-3.5-turbo"
}
```



------



如需补充更多接口细节，请查阅后端源码或联系开发者！
