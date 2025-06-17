# php-gpt-assistant

> 🚀 一款基于 Laravel + Vue3 + OpenAI 的全栈 GPT 助手，支持多用户、模型切换、系统提示词、聊天历史、Token 统计、头像上传等功能。

## 🧩 技术栈

- **后端**：Laravel 7、MySQL、Redis、JWT、阿里云 OSS
- **前端**：Vue 3、Vite、Pinia、TailwindCSS
- **部署**：Docker Compose / 分离独立服务

---

## 🎉 在线体验

> Demo：`http://chat.51dsn.com/`  
> 账号/密码：`demo@example.com` / `123456`（体验账号可选）

---

## ✨ 主要特性

- [x] JWT 用户认证/注册
- [x] 聊天系统（支持自定义 system prompt、模型选择）
- [x] 聊天记录查询
- [x] Token 消耗统计
- [x] OpenAI API Key 绑定
- [x] 头像上传/裁剪（支持 OSS）
- [x] 权限管理、全局消息提示、主题切换
- [x] Docker 一键部署
- [x] 完善接口文档/前端单元测试

---

## 文档
```php
docs/
├── API.md               # 接口文档，最常用，前后端联调必备
├── DEPLOY.md            # 部署文档，含Docker/宝塔/云服务等
├── SETUP.md             # 开发环境本地搭建文档，区别于线上部署
├── CI_CD.md             # 持续集成与部署配置说明
├── DATABASE.md          # 数据库结构文档（ER图、建表SQL、表字段说明）
├── OSS.md               # OSS/第三方存储使用说明（头像/附件/云存储等）
├── CONFIG.md            # 项目配置文件说明（env/敏感信息/密钥/开放配置项）
├── MOCK.md              # API Mock 文档（前端联调、测试用）
├── TESTING.md           # 单元测试/接口测试说明
├── FEATURES.md          # 功能列表与实现要点（便于产品经理/全局把控）
├── FAQ.md               # 常见问题与排查
├── CHANGELOG.md         # 变更日志，团队协作、长期维护必备
└── ...（可按需要扩展：如国际化I18N.md、前端自定义主题THEME.md、性能优化PERF.md等）
```

## 项目预览

### 登陆
<img src="https://archive.biliimg.com/bfs/archive/0eab6f0369257c71e6e05bb48c5cc4c3011973be.png"  referrerpolicy="no-referrer">

### 注册
<img src="https://archive.biliimg.com/bfs/archive/7f6ce0fcccf23b7319866e43d73e117fc2a04176.png"  referrerpolicy="no-referrer">

### 聊天
<img src="https://archive.biliimg.com/bfs/archive/2ae0ede957d11382e71c0c1fd8d12115cc8d5e02.png"  referrerpolicy="no-referrer">

### 设置
<img src="https://archive.biliimg.com/bfs/archive/458ac73bdf96f94e7bcb6c1da5d58fc9aee6d6a2.png"  referrerpolicy="no-referrer">

### 主题切换

<img src="https://archive.biliimg.com/bfs/archive/4b5edd96f6b0ca1345bb4ffc0910c443a2d65b1d.png"  referrerpolicy="no-referrer">


