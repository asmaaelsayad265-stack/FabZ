FROM node:20-alpine

WORKDIR /app

COPY apps/web/package.json apps/web/package-lock.json* ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY apps/web ./

# Build for production
RUN npm run build

# Next.js production
EXPOSE 3000
CMD ["npm","run","start"]

