# Database Schema: AI Transcript SaaS Ecommerce Platform

## Users Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `name` - varchar(255)
- `email` - varchar(255) UNIQUE
- `email_verified_at` - timestamp NULL
- `password` - varchar(255)
- `remember_token` - varchar(100) NULL
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Roles Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `name` - varchar(255) UNIQUE
- `guard_name` - varchar(255)
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Role_User Pivot Table
- `role_id` - bigint(20) unsigned FOREIGN KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `model_type` - varchar(255)

## Permissions Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `name` - varchar(255) UNIQUE
- `guard_name` - varchar(255)
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Permission_Role Pivot Table
- `permission_id` - bigint(20) unsigned FOREIGN KEY
- `role_id` - bigint(20) unsigned FOREIGN KEY

## Products Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `name` - varchar(255)
- `slug` - varchar(255) UNIQUE
- `description` - text NULL
- `short_description` - text NULL
- `features` - json NULL
- `is_active` - boolean DEFAULT 1
- `visibility` - enum('public', 'private', 'restricted') DEFAULT 'public'
- `availability` - enum('coming_soon', 'available', 'discontinued') DEFAULT 'available'
- `is_featured` - boolean DEFAULT 0
- `show_on_homepage` - boolean DEFAULT 0
- `is_highlighted` - boolean DEFAULT 0
- `publish_at` - date NULL
- `unpublish_at` - date NULL
- `sort_order` - integer DEFAULT 0
- `image_path` - varchar(255) NULL
- `metadata` - json NULL
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL
- `deleted_at` - timestamp NULL

## Price_Plans Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `product_id` - bigint(20) unsigned FOREIGN KEY
- `name` - varchar(255)
- `slug` - varchar(255) UNIQUE
- `stripe_price_id` - varchar(255) NULL
- `price` - decimal(10,2)
- `monthly_price` - decimal(10,2) NULL
- `yearly_price` - decimal(10,2) NULL
- `custom_price` - decimal(10,2) NULL
- `billing_cycle` - enum('monthly', 'yearly', 'custom')
- `features` - json NULL
- `is_featured` - boolean DEFAULT 0
- `is_active` - boolean DEFAULT 1
- `trial_days` - integer DEFAULT 0
- `metadata` - json NULL
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL
- `deleted_at` - timestamp NULL

## Subscriptions Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `price_plan_id` - bigint(20) unsigned FOREIGN KEY
- `name` - varchar(255)
- `stripe_id` - varchar(255) UNIQUE
- `stripe_status` - varchar(255)
- `trial_ends_at` - timestamp NULL
- `ends_at` - timestamp NULL
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Subscription_Items Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `subscription_id` - bigint(20) unsigned FOREIGN KEY
- `stripe_id` - varchar(255) UNIQUE
- `stripe_product` - varchar(255)
- `stripe_price` - varchar(255)
- `quantity` - integer NULL
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Orders Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `price_plan_id` - bigint(20) unsigned FOREIGN KEY
- `order_number` - varchar(255) UNIQUE
- `status` - enum('pending', 'processing', 'completed', 'cancelled', 'failed')
- `total` - decimal(10,2)
- `payment_method` - varchar(255) NULL
- `payment_id` - varchar(255) NULL
- `billing_email` - varchar(255)
- `billing_name` - varchar(255)
- `billing_address` - text NULL
- `billing_city` - varchar(255) NULL
- `billing_state` - varchar(255) NULL
- `billing_zip` - varchar(255) NULL
- `billing_country` - varchar(255) NULL
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Invoices Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `subscription_id` - bigint(20) unsigned FOREIGN KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `stripe_id` - varchar(255) UNIQUE
- `total` - decimal(10,2)
- `status` - enum('draft', 'open', 'paid', 'uncollectible', 'void')
- `invoice_pdf` - varchar(255) NULL
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Posts Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `title` - varchar(255)
- `slug` - varchar(255) UNIQUE
- `content` - text NULL
- `excerpt` - text NULL
- `featured_image` - varchar(255) NULL
- `status` - enum('draft', 'published', 'archived')
- `published_at` - timestamp NULL
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Categories Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `name` - varchar(255)
- `slug` - varchar(255) UNIQUE
- `description` - text NULL
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Category_Post Pivot Table
- `category_id` - bigint(20) unsigned FOREIGN KEY
- `post_id` - bigint(20) unsigned FOREIGN KEY

## Tags Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `name` - varchar(255)
- `slug` - varchar(255) UNIQUE
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Post_Tag Pivot Table
- `post_id` - bigint(20) unsigned FOREIGN KEY
- `tag_id` - bigint(20) unsigned FOREIGN KEY

## Transcripts Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `title` - varchar(255) NULL
- `original_filename` - varchar(255) NULL
- `file_path` - varchar(255) NULL
- `content` - longtext NULL
- `duration` - integer NULL
- `status` - enum('pending', 'processing', 'completed', 'failed')
- `language` - varchar(50) DEFAULT 'en'
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Usage_Logs Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `transcript_id` - bigint(20) unsigned FOREIGN KEY NULL
- `action` - varchar(255)
- `resource_type` - varchar(255)
- `resource_id` - bigint(20) unsigned NULL
- `details` - json NULL
- `created_at` - timestamp NULL

## Settings Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `key` - varchar(255) UNIQUE
- `value` - text NULL
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL

## Product_Features Table
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `product_id` - bigint(20) unsigned FOREIGN KEY
- `name` - varchar(255)
- `key` - varchar(255) INDEX
- `description` - text NULL
- `type` - enum('boolean', 'numeric', 'text', 'list') DEFAULT 'text'
- `value` - text NULL
- `options` - json NULL
- `sort_order` - integer DEFAULT 0
- `is_active` - boolean DEFAULT 1
- `is_highlighted` - boolean DEFAULT 0
- `is_public` - boolean DEFAULT 1
- `created_at` - timestamp NULL
- `updated_at` - timestamp NULL
- `deleted_at` - timestamp NULL
- UNIQUE(`product_id`, `key`) 