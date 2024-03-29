<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "/admin/check-current-password","/admin/update-section-status","/admin/update-category-status",
        "/admin/append-categories-level","/admin/update-product-status","/admin/update-attribute-status",
        "/admin/update-image-status","/admin/update-brand-status","/admin/update-banner-status","/get-product-price",
        "/update-cart-item-qty","/delete-cart-item","/check-user-password","/admin/update-coupon-status",
        "/apply-coupon"
    ];
}
