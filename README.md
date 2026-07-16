# FabZ Educational Platform (Robotics, Coding, AI, Electronics, Fabrication)

This repository is a monorepo containing:

- `apps/web`: Next.js 16 (App Router) frontend
- `apps/api`: Laravel 12 REST API backend
- `infra`: Docker + deployment scaffolding

## Prerequisites

- Docker Desktop
- Docker Compose

## Getting Started (Docker)

1. Create `.env` files from `.env.example`:
   - `apps/api/.env`
   - `apps/web/.env.local`
2. Build and run:

   ```bash
   docker compose up --build
   ```

3. Apply migrations:

   ```bash
   docker compose exec api php artisan migrate --force
   ```

## Default Ports

- Web: `3000`
- API: `8000`
- MySQL: `3306`

## Notes

- This project uses Laravel Sanctum for authentication.
- Storage is configured to work with Cloudinary.
- Payments support Paymob + Fawry Ready (provider abstraction).
- Google Meet integration will be implemented in subsequent steps.
- Certificates PDF generation will be implemented in subsequent steps.
