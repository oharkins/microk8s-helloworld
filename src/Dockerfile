# Build stage
FROM node:20-slim AS builder

# Create app directory
WORKDIR /usr/src/app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci --only=production

# Production stage
FROM node:20-slim

# Create app directory
WORKDIR /usr/src/app

# Copy built dependencies from builder
COPY --from=builder /usr/src/app/node_modules ./node_modules

# Copy application files
COPY . .

# Create non-root user with a different UID if 1000 is taken
RUN if getent passwd 1000; then \
        useradd -m -u 1001 appuser; \
    else \
        useradd -m -u 1000 appuser; \
    fi \
    && chown -R appuser:appuser /usr/src/app

# Switch to non-root user
USER appuser

# Expose port
EXPOSE 8080

# Start the application
CMD [ "npm", "start" ]

# Add labels
LABEL maintainer="Odis Harkins <odisjamesharkins@gmail.com>"
LABEL version="1.0"
LABEL description="Node.js Express application with PostgreSQL support" 