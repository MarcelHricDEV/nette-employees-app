#/bin/bash
docker-compose up -d

# Build assets
docker-compose exec app npm run build
