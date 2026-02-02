# coachtechフリマ

商品の出品と購入を行うことができるフリマアプリです。

## 環境構築

#### リポジトリをクローン

```
git clone git@github.com:ikemi-yuki/flea-market-app.git
```

#### Laravelのビルド

```
docker-compose up -d --build
```

#### Laravelパッケージのダウンロード

```
docker-compose exec php bash
```

```
composer install
```

#### .envファイルの作成

```
cp .env.example .env
```

#### .envファイルの修正

```
DB_HOST=mysql

DB_DATABASE=laravel_db

DB_USERNAME=laravel_user

DB_PASSWORD=laravel_pass
```

#### キー生成

```
php artisan key:generate
```

#### マイグレーション・ストレージリンク作成・シーディングを実行

```
php artisan migrate
```
```
php artisan storage:link
```
```
php artisan db:seed
```

## テスト環境のセットアップ

#### .env.testing ファイルの作成

```
cp .env.testing.example .env.testing
```

#### .env.testing ファイルの修正

```
DB_DATABASE=demo_test

DB_USERNAME=root

DB_PASSWORD=root
```

#### キー生成・マイグレーション・テスト実行

```
php artisan key:generate --env=testing
```
```
php artisan migrate --env=testing
```
```
php artisan test

```

## 使用技術（実行環境）

フレームワーク: Laravel:8.83.8

言語：HTML CSS Javascript PHP

Webサーバー: Nginx:1.21.1

データベース: MySQL:8.0.26

## ER図

![ER図](flea-market-app.drawio.png)

## URL

アプリケーション：http://localhost/

phpMyAdmin：http://localhost:8080/
