# Dostourna

A crowdfunding platform for young entrepreneurs. Project owners publish their projects, and other users top up a
wallet with Stripe and fund the projects they believe in. The interface is in French.

Built with Symfony 7.1, Doctrine, Twig and Stripe.

## Features

- **Projects**: create, edit and delete a project with a funding goal, category, description, image and location.
- **Browse**: list projects by category with pagination, and open a detail page for each one.
- **Interactive map**: projects are shown on a [Leaflet](https://leafletjs.com) map using their coordinates. The
  location is picked on a map when a project is created.
- **Wallet**: users add money to their balance through Stripe (PaymentIntents).
- **Funding**: users fund a project from their balance. The project's current funding grows, and each payment is
  saved as a transaction.
- **Accounts**: sign up, log in and a profile page (Symfony Security with form login).

## Data model

```
User ──< Investments >── Category
  │           │
  └──< Transactions >──┘
```

| Entity | What it holds |
|--------|---------------|
| `User` | email, phone, hashed password, wallet balance |
| `Investments` | a project: name, goal (`price`), current funding, type, status, image, latitude/longitude, category, owner |
| `Category` | name, description, icon |
| `Transactions` | a payment from a user to a project: amount, type, date |

## Routes

| Path | Page |
|------|------|
| `/` | Home, with the project map |
| `/about` | About |
| `/projects/{cat}/{page}` | Projects in a category, paginated |
| `/details/{id}` | Project details and funding form |
| `/projet` | Add a project |
| `/infos/{id}` | Edit a project |
| `/register`, `/login`, `/profile` | Account pages |
| `/create-payment-intent`, `/payment-success` | Stripe top-up flow |

## Getting started

### Requirements

- PHP 8.2+
- [Composer](https://getcomposer.org)
- MySQL (or MariaDB)
- A [Stripe](https://stripe.com) account (test mode is enough)
- Optional: the [Symfony CLI](https://symfony.com/download)

### 1. Install

```bash
git clone https://github.com/KacemAbdelbaki/Dostourna.git
cd Dostourna
composer install
```

### 2. Configure

Create a `.env.local` file (it is not committed) and set your own values:

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/Dostourna"
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_SECRET_KEY=sk_test_...
```

### 3. Create the database

No migrations are committed yet, so create the schema from the entities:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

Then add a few rows to the `category` table, since projects are listed by category.

### 4. Run

```bash
symfony server:start
# or: php -S localhost:8000 -t public
```

Open http://localhost:8000.

To test a top-up, use a Stripe [test card](https://docs.stripe.com/testing) such as `4242 4242 4242 4242`.

## Project structure

```
src/
├── Controller/   # Home, Projects, Payment (Stripe), User (auth + profile)
├── Entity/       # User, Investments, Category, Transactions
├── Form/         # InvestmentType
├── Repository/
└── Service/      # StripeService
templates/
├── home/         # index, about, projects, details, add/update project
└── user/         # login, register, profile
public/           # CSS, JS, images, uploaded project images
```
