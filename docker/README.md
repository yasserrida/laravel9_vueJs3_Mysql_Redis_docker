# Docker

## SETUP
```
mkdir volumes
mkdir volumes/redis
mkdir volumes/mysql
```

## UP
```
docker-compose up -d
```

## DOWN
```
docker-compose down -d
```

## Rebuild
```
docker-compose up -d --build --force-recreate --remove-orphans
```