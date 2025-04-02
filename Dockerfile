# Use Node.js LTS version
FROM node:20-slim

# Create app directory
WORKDIR /usr/src/app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy application files
COPY . .

# Create non-root user
RUN useradd -m -u 1000 appuser \
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