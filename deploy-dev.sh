#/bin/bash
docker stop $(docker ps -a -q --filter ancestor=procurex-sso --format="{{.ID}}") || true
docker rmi -f $(docker images -f "dangling=true" -q) || true
cp /home/gitlab-runner/env-sso .env
cp /home/gitlab-runner/env-sso-testing .env.testing
docker build -t procurex-sso .
docker run -d --network=procurex-net --restart unless-stopped -p 9001:9000 procurex-sso
