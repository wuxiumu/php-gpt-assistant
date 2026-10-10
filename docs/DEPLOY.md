# DEPLOY.md 部署文档 — php-gpt-assistant

本文档说明本项目前后端分离的几种常见部署方式，包括 Docker Compose 一键部署、宝塔手动部署、Nginx/Apache 配置等。

---

## 目录

1. Docker Compose 一键部署
2. 宝塔手动部署（前端+后端）
3. Nginx/Apache 配置（静态资源/history路由）
4. 重要环境变量说明
5. 常见问题

---

## 1. Docker Compose 一键部署

> 推荐本地开发/测试/生产服务器全流程

### 1.1 前提条件

- 服务器已安装 [Docker](https://docs.docker.com/get-docker/) 和 [docker-compose](https://docs.docker.com/compose/install/)

### 1.2 步骤

1. 克隆项目代码：

```bash
   git clone https://github.com/wuxiumu/php-gpt-assistant.git
   cd php-gpt-assistant
```



2. 准备环境变量（示例文件只有占位符，不要把真实密钥提交回仓库）：

```
cp .env.example .env
cp backend/.env.example backend/.env
# 编辑 .env：把 MYSQL_ROOT_PASSWORD 改成你的数据库密码
# 编辑 backend/.env：填写同一套数据库密码，并生成应用密钥
cd backend
php artisan key:generate
php artisan jwt:secret
cd ..
```

没有本机 PHP 时，在 `backend/.env` 里自行填入 `APP_KEY`（`base64:` 开头）和 `JWT_SECRET`。暂不上传头像时，OSS 变量留空。

3. 一键启动。镜像内会安装依赖；修改代码后需要重新构建：


```
docker-compose up --build -d
```

4. 后端容器会等待数据库就绪，然后执行 `php artisan migrate --force`。Compose 把 `DB_HOST` 覆盖为 `db`、`REDIS_HOST` 覆盖为 `redis`，数据库密码使用根目录 `.env` 的 `MYSQL_ROOT_PASSWORD`。

5. 访问：

- 前端：http://服务器IP:5173
- 后端 API：http://服务器IP:8000/api

MySQL（3306）和 Redis（6379）只绑定在服务器本机 `127.0.0.1`。前端镜像使用根目录 `.env` 的 `VITE_API_BASE`（默认 `http://localhost:8000/api`）。配置检查：`sh scripts/smoke.sh`。

 

------





## **2. 宝塔面板手动部署**





适合国内服务器、习惯宝塔面板的同学





### **2.1 后端（Laravel）**





1. **添加网站**：在宝塔新建一个网站（如 api.yourdomain.com），PHP 版本 >= 7.4。

2. **上传代码**：上传 backend/ 目录下全部文件到网站根目录。

3. **安装依赖**：



- 进入 SSH，切到网站根目录
- 执行 composer install



4. **配置 .env**：复制 .env.example 为 .env，配置数据库/Redis/OSS等。

5. **生成 key & 初始化表**：



```
php artisan key:generate
php artisan migrate --force
```



1.
2. **设置运行目录**：宝塔 → 网站设置 → 运行目录 → 选 public。
3. **（可选）安装扩展**：如 fileinfo、pdo_mysql、redis


------


### **2.2 前端（Vue）**


1. **本地打包**：

```
cd frontend
npm install
npm run build
```

2. **上传 dist 文件**：将 frontend/dist/ 目录下所有文件上传到宝塔新建网站（如 gpt.yourdomain.com）根目录。

3. **配置伪静态**（history路由支持）：



- **Nginx伪静态**：


```
location / {
  try_files $uri $uri/ /index.html;
}
```



1. **Apache .htaccess**：


```
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^index\.html$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.html [L]
</IfModule>
```



2. **重启网站**，访问域名即可上线。





------





## **3. Nginx/Apache 配置（静态资源和 history 路由）**





**前端所有路由都返回 index.html：**



- Nginx 配置示例



```
location / {
  try_files $uri $uri/ /index.html;
}
```



-
- Apache .htaccess 已见上





------





## **4. 重要环境变量说明**





- **后端 backend/.env**



```
APP_KEY=（用 php artisan key:generate 生成）
JWT_SECRET=（用 php artisan jwt:secret 生成）
DB_HOST=数据库地址
DB_DATABASE=数据库名
DB_USERNAME=用户名
DB_PASSWORD=密码
REDIS_HOST=redis地址
OSS_ACCESS_KEY_ID=阿里云OSS key
OSS_ACCESS_KEY_SECRET=阿里云OSS secret
OSS_BUCKET=Bucket名称
OSS_ENDPOINT=oss-cn-xxx.aliyuncs.com
OSS_HOST=https://xxx.oss-cn-xxx.aliyuncs.com
CORS_ALLOWED_ORIGINS=*
```

Docker Compose 启动时会覆盖 `DB_HOST=db`、`REDIS_HOST=redis`，并用根目录 `.env` 的 `MYSQL_ROOT_PASSWORD` 作为 `DB_PASSWORD`。



-
- **前端 frontend/.env**



```
VITE_API_BASE=http://你的后端API地址
```

本地开发复制 `frontend/.env.example`。Docker 构建读取根目录 `.env` 的 `VITE_API_BASE`。





------





## **5. 常见问题**





- **前端刷新页面404？**

  必须设置好伪静态（Nginx/Apache如上）。

- **接口跨域？**

  后端 Laravel 推荐用 [fruitcake/laravel-cors](https://github.com/fruitcake/laravel-cors) 允许跨域。

- **数据库迁移报错？**

  检查数据库连接和账号权限。

- **头像上传失败？**

  检查 OSS 配置、php.ini 文件上传限制、OSS 跨域设置。

- **端口占用/无法访问？**

  检查防火墙/安全组，放通 5173、8000 端口或用80/443端口映射。





------





## **6. 其它部署方式**





- 前端可一键上传到 Vercel、Netlify 等静态云平台。
- 后端可用 Laravel Forge、宝塔一键包、阿里云 ECS 部署。





------
