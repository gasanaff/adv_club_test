## Требования
- make
- docker (docker-compose)

## Запуск
- `make build` - первичная сборка и запуск приложения
- `make up` - запуск приложения
- `make down` - остановка приложения
- `make test` - запуск тестов

## Использование API калькулятора
        curl --location 'http://127.0.0.1:8088/api/calc' \
        --header 'Content-Type: application/json' \
        --data '{
        "operator":"*",
        "operand1":2,
        "operand2":2
        }'

## Использование API расчута стоимости путешествия
        curl --location 'http://127.0.0.1:8088/api/travel_cost' \
        --header 'Content-Type: application/json' \
        --data '{
        "birth_date":"2003-12-30",
        "travel_date":"2020-12-31",
        "base_cost": 1000
        }'

### PS
Так как в задании не было указано как расчитывать стоимость путешествия для детей с 3 до 6 лет, для них стоимость путешествия будет равна базовой стоимости.