#/bin/bash
docker rmi -f $(docker images -f "dangling=true" -q) || true
cp /home/devadmin/docker-production/environtment/env-sso .env
#cp /home/gitlab-runner/env-masterdata-testing .env.testing
docker build -t procurex-sso .
docker stop procurex-sso || true
docker container rm procurex-sso
docker rmi -f $(docker images -f "dangling=true" -q) || true
docker run -d --name=procurex-sso --network=procurex-net --restart unless-stopped -p 9005:9000 procurex-sso
