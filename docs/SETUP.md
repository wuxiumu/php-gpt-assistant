# SETUP.md 本地开发环境搭建 — php-gpt-assistant

本指南帮助开发者在本地快速搭建前后端联调环境，适用于 Mac、Windows、Linux。

---

## 目录

1. 前置环境安装
2. 后端项目搭建（Laravel）
3. 前端项目搭建（Vue3）
4. 数据库/Redis配置
5. 常见问题
6. 本地 mock 调试（可选）

---

## 1. 前置环境安装

### 1.1 推荐开发环境

- **操作系统**：MacOS/Windows/Linux
- **Node.js**：>= 18.x
- **npm**：>= 9.x
- **PHP**：7.4.x（推荐用 [phpenv](https://github.com/phpenv/phpenv) 或 [laravel valet](https://laravel.com/docs/7.x/valet)）
- **Composer**：2.x
- **MySQL**：5.7/8.0
- **Redis**：>= 5
- **Git**：最新版

#### 安装建议

- [Node.js 官网](https://nodejs.org/)
- [Composer 官网](https://getcomposer.org/)
- Mac 推荐 [Homebrew](https://brew.sh/) 安装 PHP/MySQL/Redis
- Windows 推荐 [XAMPP](https://www.apachefriends.org/zh_cn/index.html) 或 [Laragon](https://laragon.org/) 集成包

---

## 2. 后端项目搭建（Laravel）

```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
```
2.1 数据库/Redis/OSS 配置

•编辑 .env，配置好本地数据库账号、Redis 地址、OSS/阿里云等第三方服务

•示例（修改成你本地实际配置）：

```
DB_HOST=127.0.0.1
DB_DATABASE=gpt_assistant
DB_USERNAME=root
DB_PASSWORD=yourpassword
REDIS_HOST=127.0.0.1
OSS_ACCESS_KEY_ID=xxx
OSS_ACCESS_KEY_SECRET=xxx
OSS_BUCKET=xxx
OSS_ENDPOINT=oss-cn-xxx.aliyuncs.com
OSS_HOST=https://xxx.oss-cn-xxx.aliyuncs.com
```


2.2 初始化数据库
```
php artisan migrate
```
2.3 启动后端服务
```
php artisan serve --host=127.0.0.1 --port=8000
```
访问 http://127.0.0.1:8000/api 查看后端API

⸻

3. 前端项目搭建（Vue3 + Vite）
```
cd frontend
cp .env.example .env
npm install
```
3.1 配置后端 API 地址

•编辑 frontend/.env，设置后端 API 地址（本地调试推荐）
```
VITE_API_BASE=http://127.0.0.1:8000/api
```


3.2 启动前端项目
```
npm run dev
```
访问 http://localhost:5173 查看前端页面

⸻

4. 数据库/Redis 配置
```
•启动本地 MySQL 和 Redis 服务（端口分别为 3306、6379）
•如需本地测试数据，可自行在数据库插入用户
```
⸻

5. 常见问题
```
•端口被占用？
修改端口（如 php artisan serve --port=8010）

•数据库迁移失败？
检查 .env 配置和数据库连接

•安装依赖慢？
Node 推荐使用国内源如 淘宝NPM镜像

•前后端跨域问题？
Laravel 后端安装 fruitcake/laravel-cors

composer require fruitcake/laravel-cors
php artisan vendor:publish --provider="Fruitcake\Cors\CorsServiceProvider"


•PHP 报错/缺扩展？
确认已安装并启用 pdo_mysql、fileinfo 等扩展
```
⸻

6. 本地 Mock 调试（可选）
•前端可配置 mock server 进行接口联调
•推荐用 vite-plugin-mock

⸻

开发交流 & 贡献建议
•有问题可提 issue
•pull request 前请确保通过单元测试和 lint 检查

⸻

祝开发愉快！如遇环境或代码异常，可随时反馈 issue 或联系作者。
 