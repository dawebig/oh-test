# oh-test

# Projekt beüzemeléséhez szükséges software-ek
1. Docker
2. Docker compose (legalább 1.29-es verzió)


## Telepítés
```bash
$ make install
```

## Összes service elindítása
```bash
$ make start
```

## Service-ek leállítása
```bash
$ make stop
```

## PHPUNIT futtatása
```bash
$ make phpunit
```

## PHPMD futtatása
```bash
$ make phpmd
```

## PHPCS futtatása
```bash
$ make phpcs
```

## Postman collection
```
./OH TEST.postman_collection.json
```