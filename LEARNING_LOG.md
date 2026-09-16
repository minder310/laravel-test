# 學習紀錄

> 後續學習內容依日期持續追加於本文件，保留過去紀錄。

## 2026-09-16：Laravel、Docker 與 Composer 基礎

### 今天建立的整體觀念

目前的開發環境可以理解為：

```text
Windows
└── WSL 2
    └── Ubuntu
        ├── Laravel 專案程式碼
        └── Docker
            ├── Nginx 容器
            ├── PHP 容器（包含 Composer）
            └── MySQL 容器
```

- Windows 是主要作業系統。
- WSL 2 讓 Windows 可以執行 Linux 環境。
- Ubuntu 是目前操作專案與檔案的 Linux 環境。
- Docker 負責建立和執行一致的服務環境。
- Laravel 程式碼保存在 Ubuntu 的專案資料夾，不是保存在容器裡。
- VS Code 連入 Ubuntu 後，可以編輯 Laravel 程式碼。
- Docker 或容器停止時，程式碼不會消失，只是網站服務無法運行。

### Docker、Docker Compose 與 Composer 的差別

- **Docker**：建立與執行容器。
- **Docker Compose**：依照 `docker-compose.yml`，一起管理 PHP、Nginx、MySQL 等多個容器。
- **Composer**：PHP 的套件管理工具，用來建立 Laravel 專案及安裝 PHP 套件。

Composer 本身是 PHP 程式，所以需要 PHP 才能執行。這個專案選擇把 PHP 與 Composer 放在 PHP 容器內，讓環境更容易重建與分享。

### Dockerfile 與映像

`docker/php/Dockerfile` 是 PHP 環境的製作說明書，其中這一行會把 Composer 放進 PHP 映像：

```dockerfile
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
```

`composer:latest` 會使用目前標記為最新版的 Composer；`composer:2` 則限定在 Composer 2 系列，可降低未來自動跨大版本的風險。

修改 Dockerfile 後，只重新啟動 Docker 不會重新建置映像，需要執行：

```bash
docker compose build php
docker compose up -d
```

也可以合併成：

```bash
docker compose up -d --build
```

### Laravel 與 Composer 指令

以下三種動作不同：

```text
安裝 Composer
→ 把 Composer 工具放入 PHP 環境

composer create-project
→ 建立一個全新的 Laravel 專案

composer install
→ 安裝既有專案在 composer.lock 中指定的套件
```

目前的 `My-Test` 已經是 Laravel 專案，因為存在 `artisan`、`app/`、`routes/`、`composer.json` 與 `vendor/` 等內容，不應在裡面再次執行 `create-project`。

管理目前專案套件時，可以在 `My-Test` 目錄使用：

```bash
docker compose exec php composer install
```

確認 Composer 與 Laravel：

```bash
docker compose exec php composer --version
docker compose exec php php artisan --version
```

### 建立全新的 Laravel 專案

新專案應建立在 `/home/ser20251216/projects`，而不是現有的 `My-Test` 裡。例如：

```bash
cd /home/ser20251216/projects
docker run --rm -v "$PWD":/app composer:2 create-project laravel/laravel New-Laravel
```

這條指令的意思：

- `docker run`：建立並執行一個臨時容器。
- `--rm`：工作完成後刪除臨時容器，但不刪除寫入 Ubuntu 的專案。
- `-v "$PWD":/app`：把 Ubuntu 目前所在的資料夾掛載到容器的 `/app`。
- `composer:2`：使用包含 PHP 與 Composer 2 的官方映像。
- `create-project laravel/laravel New-Laravel`：用 Laravel 官方範本建立 `New-Laravel` 專案。

這只會建立 Laravel 專案，不會自動建立該專案專用的 Nginx、MySQL、Dockerfile 或 `docker-compose.yml`。

### 常用位置與檢查指令

查看目前所在位置：

```bash
pwd
```

查看目前資料夾內容：

```bash
ls
ls -la
```

回上一層或回家目錄：

```bash
cd ..
cd ~
```

查看專案容器狀態：

```bash
docker compose ps
```

### 今日重點結論

1. 程式碼、工具安裝、映像建置與容器啟動是不同的事情。
2. `My-Test` 同時是 Laravel 專案根目錄，也是目前 Docker Compose 設定所在的位置。
3. 使用 Docker 的主要價值，是快速重建一致的 PHP、Composer、Nginx 與 MySQL 環境。
4. 執行建立專案或 Docker 指令前，先用 `pwd` 和 `ls` 確認所在位置。
5. 作為 PM，理解各層的責任和基本排錯方式，比鑽研所有底層細節更重要。
