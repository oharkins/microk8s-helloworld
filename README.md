# Node.js Application Status Page

This is a simple Node.js Express application that displays system information and database connection status. It's designed to run in a Kubernetes environment with PostgreSQL database support.

## Features

- Displays network information (Local IP, Server IP, Hostname)
- Tests and displays PostgreSQL database connection status
- Runs on port 8080
- Containerized with Docker
- Kubernetes-ready with YAML configurations

## Prerequisites

- Docker
- Kubernetes cluster (e.g., MicroK8s)
- kubectl command-line tool

## Environment Variables

The application uses the following environment variables for database configuration:

- `DB_HOST`: PostgreSQL host (default: 'postgres')
- `DB_NAME`: Database name (default: 'lapp_db')
- `DB_USER`: Database user (default: 'lapp_user')
- `DB_PASS`: Database password (default: 'lapp_password')
- `PORT`: Application port (default: 8080)

## Building and Running Locally

1. Build the Docker image:
```bash
docker build -t express-status-app .
```

2. Run the container:
```bash
docker run -p 8080:8080 express-status-app
```

The application will be available at `http://localhost:8080`

## Kubernetes Deployment

1. Create the namespace:
```bash
kubectl apply -f namespace.yaml
```

2. Create the secrets:
```bash
kubectl apply -f secrets.yaml
```

3. Deploy PostgreSQL:
```bash
kubectl apply -f postgres-pv.yaml
kubectl apply -f postgres.yaml
```

4. Build and push the Express application image:
```bash
# For AMD64 architecture (Intel/AMD)
docker buildx build --platform linux/amd64,linux/arm64 -t oharkins/express-status-app:latest .
docker push oharkins/express-status-app:latest
```

5. Deploy the application:
```bash
kubectl apply -f express-app.yaml
kubectl apply -f service.yaml
kubectl apply -f ingress.yaml
```

## Project Structure

- `server.js`: Main application file
- `package.json`: Node.js dependencies and scripts
- `Dockerfile`: Container configuration
- Kubernetes configuration files:
  - `namespace.yaml`: Creates the application namespace
  - `secrets.yaml`: Contains database credentials
  - `postgres-pv.yaml`: PostgreSQL persistent volume
  - `postgres.yaml`: PostgreSQL deployment and service
  - `express-app.yaml`: Express application deployment
  - `service.yaml`: Application service
  - `ingress.yaml`: Ingress configuration

## Security

- The application runs as a non-root user in the container
- Database credentials are stored in Kubernetes secrets
- HTTPS is enforced through ingress configuration

## Maintenance

- Built with Node.js 20 LTS
- Uses Express.js for the web server
- PostgreSQL client for database connectivity
- Containerized with Docker

## Monitoring

To check the status of the deployments:

```bash
kubectl get pods -n lapp
kubectl get services -n lapp
kubectl get ingress -n lapp
```

## Cleanup

To remove the entire stack:

```bash
kubectl delete namespace lapp
```

# LAPP Stack on MicroK8s

This repository contains Kubernetes manifests for deploying a LAPP (Linux, Apache, PHP, PostgreSQL) stack on MicroK8s.

## Prerequisites

- MicroK8s installed and running
- kubectl configured to work with MicroK8s
- Docker installed

## Enable Required MicroK8s Addons

```bash
microk8s enable ingress
microk8s enable storage
```

## Deploy the Stack

1. Create the namespace and apply all manifests:

```bash
kubectl apply -f namespace.yaml
kubectl apply -f secrets.yaml
kubectl apply -f postgres-pv.yaml
kubectl apply -f postgres.yaml
kubectl apply -f php-app.yaml
kubectl apply -f ingress.yaml
```

2. Build and push the PHP application image:

```bash
# For AMD64 architecture (Intel/AMD)
docker buildx build --platform linux/amd64,linux/arm64 -t oharkins/lapp-php:latest .
docker push oharkins/lapp-php:latest
```

3. Update the PHP application deployment to use your image:

```bash
kubectl set image deployment/php-app php-app=oharkins/lapp-php:latest -n lapp-stack
```

## Access the Application

1. Add the following entry to your `/etc/hosts` file:
```
127.0.0.1 lapp.local
```

2. Access the application at http://lapp.local

## Database Credentials

The PostgreSQL database credentials are stored in Kubernetes secrets:
- Database: lapp_db
- Username: lapp_user
- Password: lapp_password

## Monitoring

To check the status of the deployments:

```bash
kubectl get pods -n lapp-stack
kubectl get services -n lapp-stack
kubectl get ingress -n lapp-stack
```

## Cleanup

To remove the entire stack:

```bash
kubectl delete namespace lapp-stack
```

# PHP Application Deployment Guide for Portainer

This guide will help you deploy the PHP application using Portainer UI.

## Prerequisites

- Portainer installed and configured
- Access to Portainer UI
- Docker image built and available in your registry

## Deployment Steps

1. Log in to your Portainer UI
2. Navigate to the Kubernetes section
3. Go to "Applications" and click "Add application"
4. Choose "Upload" as the deployment method
5. Upload the following files in order:
   - `deployment.yaml`
   - `service.yaml`

## Configuration Details

The deployment includes:
- 1 replica of the PHP application
- Resource limits:
  - Memory: 256Mi
  - CPU: 200m
- LoadBalancer service type for external access

## Accessing the Application

After deployment, you can access the application using the external IP address assigned to the LoadBalancer service. You can find this IP in the Portainer UI under the Services section.

## Troubleshooting

If you encounter any issues:
1. Check the pod status in Portainer UI
2. View pod logs for detailed error messages
3. Ensure the Docker image is properly built and available
4. Verify network connectivity and firewall rules 