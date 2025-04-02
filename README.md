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
docker buildx build --platform linux/amd64,linux/arm64 -t oharkins/pen-stack:latest .
docker push oharkins/pen-stack:latest
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
- `.dockerignore`: Specifies files to exclude from Docker builds
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
kubectl get pods -n pen-stack
kubectl get services -n pen-stack
kubectl get ingress -n pen-stack
```

## Cleanup

To remove the entire stack:

```bash
kubectl delete namespace pen-stack
``` 