# php-gpt-assistant · 深优化建议（文档先行）

> 与父仓 `docs/OPTIMIZATION-NEXT.md` Top7 对齐。  
> 轻量加固：✅ C2 PR（前后端 `.env.example` / Compose 对齐 / smoke）。**下列留给下一轮代码。**

## 现状对照

| 项 | 现状 |
|---|---|
| 结构 | `backend/` + `frontend/` + 根 `docker-compose.yml`（MySQL 5.7） |
| 文档 | `docs/SETUP.md` / `DEPLOY.md` / `API.md` |
| Demo | README 有 `http://chat.51dsn.com/` |
| 风险 | 演示账号 / 生产 profile 需强制改密；阿里云内容安全需降级说明 |

## 建议（可执行）

| # | 建议 | 类型 | 验收 |
|---|---|---|---|
| 1 | Compose 一键：MySQL + 后端 + 前端，`.env.example` 全注释关键变量 | 可运行 | `docker compose up` 可聊 |
| 2 | 白标：Logo / 主色 / 站点名三处配置（前端主题 + 后端站点名） | 定制 | 换三文件即品牌 |
| 3 | 关闭演示账号或生产 profile 强制改密 | 安全 | 生产启动拒绝默认口令 |
| 4 | 用量统计导出 CSV（或管理接口），谈单证明 ROI | 定制 | 一键导出近 7/30 天 |
| 5 | README 第一屏：结果→Demo→联系；工程细节下沉 `docs/` | 文档 | 转化友好 |
| 6 | 内容安全：无阿里云时的降级路径写进 SETUP 一节 | 文档 | 可关审核仍可本地跑 |

## 建议落地顺序

3 → 1 → 6 → 5 → 2 → 4。

## 定制联系

微信 **qingbao199101**（备注：AI助手）· [wuxiumu@163.com](mailto:wuxiumu@163.com)
