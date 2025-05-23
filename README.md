# AI Transcript SaaS Ecommerce Platform

An ecommerce platform for AI-powered transcript services built with Laravel 10 and Vue.js 3.

## Project Overview

This platform provides AI transcript services through a subscription-based model with multiple pricing tiers. It includes:

- User authentication with multiple roles (Admin, Sales, Customer)
- Product management system 
- Subscription-based pricing (Monthly, Yearly, Enterprise)
- Order processing and payment integration
- Content management system
- Analytics and reporting dashboard

## Technology Stack

- **Backend**: Laravel 10
- **Frontend**: Vue.js 3 + Vite
- **Database**: MySQL/PostgreSQL
- **Payment Processing**: Stripe (planned)

## Project Status

Currently in development. See our [Project Roadmap](docs/planning/ROADMAP.md) for detailed progress.

- ✅ Phase 1: Foundation (Complete)
- 🔄 Phase 2: Backend Core (In Progress)
- 🔄 Phase 3: Frontend Essentials (Pending)
- 🔄 Phase 4: Integration & Enhancement (Pending)
- 🔄 Phase 5: Testing & Optimization (Pending)
- 🔄 Phase 6: Launch Preparation (Pending)
- 🔄 Phase 7: Launch & Post-Launch (Pending)

## Setup Instructions

### Prerequisites

- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL

### Installation

1. Clone the repository
   ```
   git clone [repository-url]
   cd [project-directory]
   ```

2. Install PHP dependencies
   ```
   composer install
   ```

3. Install JavaScript dependencies
   ```
   npm install
   ```

4. Set up environment variables
   ```
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database in the `.env` file

6. Run migrations
   ```
   php artisan migrate
   ```

7. Compile assets
   ```
   npm run dev
   ```

8. Start the development server
   ```
   php artisan serve
   ```

## Contributing

Please read our contribution guidelines before submitting pull requests.

## License

This project is licensed under the MIT License.
