#!/bin/sh
set -eu
root=$(CDPATH= cd -- "$(dirname "$0")/.." && pwd)
cd "$root"

fail() {
  echo "smoke: $1" >&2
  exit 1
}

test -f .env.example || fail "missing .env.example"
test -f backend/.env.example || fail "missing backend/.env.example"
test -f frontend/.env.example || fail "missing frontend/.env.example"
test -f docker-compose.yml || fail "missing docker-compose.yml"
test -f docs/DEPLOY.md || fail "missing docs/DEPLOY.md"
test -f docs/SETUP.md || fail "missing docs/SETUP.md"

for key in MYSQL_ROOT_PASSWORD MYSQL_DATABASE VITE_API_BASE; do
  grep -q "^${key}=" .env.example || fail ".env.example missing $key"
done

for key in APP_KEY DB_HOST DB_DATABASE DB_USERNAME DB_PASSWORD REDIS_HOST JWT_SECRET OSS_ACCESS_KEY_ID OSS_ACCESS_KEY_SECRET OSS_BUCKET OSS_ENDPOINT OSS_HOST CORS_ALLOWED_ORIGINS; do
  grep -q "^${key}=" backend/.env.example || fail "backend/.env.example missing $key"
done

grep -q '^VITE_API_BASE=' frontend/.env.example || fail "frontend/.env.example missing VITE_API_BASE"

if grep -E 'sk-[A-Za-z0-9]{20,}|AKIA[0-9A-Z]{16}|BEGIN (RSA |OPENSSH )?PRIVATE KEY' \
  .env.example backend/.env.example frontend/.env.example docker-compose.yml README.md docs/DEPLOY.md docs/SETUP.md; then
  fail "tracked config or docs contain a secret-like value"
fi

if grep -E '^[[:space:]]*(MYSQL_ROOT_PASSWORD|DB_PASSWORD):[[:space:]]*["'\'']?[A-Za-z0-9]' docker-compose.yml; then
  fail "docker-compose.yml hardcodes a database password"
fi

grep -q 'DB_HOST: db' docker-compose.yml || fail "compose backend DB_HOST is not db"
grep -q 'REDIS_HOST: redis' docker-compose.yml || fail "compose backend REDIS_HOST is not redis"
grep -q '8000:8000' docker-compose.yml || fail "compose does not publish backend 8000"
grep -q '5173:80' docker-compose.yml || fail "compose does not publish frontend 5173"
grep -q '127.0.0.1:3306:3306' docker-compose.yml || fail "MySQL is not bound to localhost"
grep -q '127.0.0.1:6379:6379' docker-compose.yml || fail "Redis is not bound to localhost"
grep -q 'env_file:' docker-compose.yml || fail "compose does not load backend env file"
grep -q 'VITE_API_BASE' docker-compose.yml || fail "compose does not pass VITE_API_BASE"

grep -q 'import.meta.env.VITE_API_BASE' frontend/src/api/request.js || fail "frontend API base is not env-driven"
if grep -q '51dsn.com' frontend/src/api/request.js; then
  fail "frontend client still hardcodes the vendor API host"
fi

grep -q 'Listen 8000' backend/Dockerfile || fail "backend image does not listen on 8000"
grep -q 'php artisan migrate --force' backend/docker/entrypoint.sh || fail "entrypoint does not migrate"
grep -q 'try_files' frontend/nginx.conf || fail "frontend image missing history fallback"

grep -q 'github.com/wuxiumu/php-gpt-assistant' docs/DEPLOY.md || fail "DEPLOY.md clone URL is stale"
grep -q 'backend/.env.example' docs/DEPLOY.md || fail "DEPLOY.md does not mention backend/.env.example"
grep -q 'JWT_SECRET' docs/DEPLOY.md || fail "DEPLOY.md missing JWT_SECRET"
grep -q 'VITE_API_BASE' docs/DEPLOY.md || fail "DEPLOY.md missing VITE_API_BASE"
grep -q '5173' docs/DEPLOY.md || fail "DEPLOY.md missing frontend port"
grep -q '8000' docs/DEPLOY.md || fail "DEPLOY.md missing backend port"
grep -q '127.0.0.1' docs/DEPLOY.md || fail "DEPLOY.md does not document localhost-only data ports"
grep -q 'JWT_SECRET' docs/SETUP.md || fail "SETUP.md missing JWT_SECRET"
grep -q 'cp .env.example .env' docs/SETUP.md || fail "SETUP.md should keep the env example copy step"

for heading in '核心特性' '适用场景' '在线体验' '项目预览'; do
  grep -q "$heading" README.md || fail "README commercial section missing: $heading"
done
grep -q 'docs/SETUP.md' README.md || fail "README setup link is not docs/SETUP.md"
grep -q 'docs/DEPLOY.md' README.md || fail "README deploy link is not docs/DEPLOY.md"

if command -v docker >/dev/null 2>&1; then
  docker compose --env-file .env.example -f docker-compose.yml config >/dev/null
fi

echo "smoke: ok"
