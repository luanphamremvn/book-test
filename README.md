# Quản Lý Sách

Chào mừng bạn đến với dự án **Quản Lý Sách**, một ứng dụng web được xây dựng bằng **Laravel** để quản lý danh mục sách, thông tin sách, và các tác vụ liên quan như thêm, sửa, xóa sách. Ứng dụng này sử dụng Laravel, một framework PHP mạnh mẽ với cú pháp thanh lịch và các tính năng tối ưu cho phát triển web.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Giới Thiệu Về Quản Lý Sách

Ứng dụng **Quản Lý Sách** được thiết kế để giúp người dùng dễ dàng quản lý thư viện sách với các tính năng chính như:

- Quản lý danh sách sách (thêm, sửa, xóa).
- Quản lý user (tạo user).

## Cài Đặt

Để chạy dự án **Quản Lý Sách** trên máy local, hãy làm theo các bước sau:

### Yêu Cầu Hệ Thống
- PHP >= 8.3
- Composer
- Node.js và npm
- MySQL hoặc cơ sở dữ liệu tương thích khác
- Laravel CLI
- Laravel Herd (Khuyến nghị)

### Hướng Dẫn Cài Đặt

1. **Cài đặt các thư viện PHP**:
   ```bash
   composer install
   ```

2. **Tạo khóa ứng dụng**:
   ```bash
   php artisan key:generate
   ```

3. **Cài đặt các thư viện JavaScript và build giao diện**:
   ```bash
   npm install && npm run build
   ```

4. **Chạy migration để tạo cơ sở dữ liệu**:
   ```bash
   php artisan migrate
   ```

5. **Chạy seeder để khởi tạo dữ liệu mẫu** (nếu cần):
   ```bash
   php artisan db:seed
   ```

6. **Khởi động server**:
   ```bash
   php artisan serve
   ```

   Sau đó, truy cập ứng dụng tại `http://book.test`.
