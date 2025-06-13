<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard.index'));
});

Breadcrumbs::for('book', function (BreadcrumbTrail $trail) {
    $trail->push('Danh sách quyển sách', route('books.index'));
});

Breadcrumbs::for('book.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('book');
    $trail->push('Chỉnh sửa', route('books.index'));
});

Breadcrumbs::for('book.create', function (BreadcrumbTrail $trail) {
    $trail->parent('book');
    $trail->push('Tạo sách', route('books.create'));
});

Breadcrumbs::for('user', function (BreadcrumbTrail $trail) {
    $trail->push('Danh sách user', route('users.index'));
});

Breadcrumbs::for('user.create', function (BreadcrumbTrail $trail) {
    $trail->parent('user');
    $trail->push('Tạo user', route('users.create'));
});
