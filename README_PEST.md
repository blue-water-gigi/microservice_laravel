# Запуск тестов Pest
## Проблема
При запуске `vendor/bin/pest` без `./` или `php` вы получите:
- Exit code 2
- Отсутствие вывода в консоли
## Решение
### ✅ Правильные способы запуска:
```bash
# Способ 1: С явным путем
./vendor/bin/pest tests/
# Способ 2: С явным PHP
php vendor/bin/pest tests/
# Способ 3: Запуск конкретного теста
./vendor/bin/pest tests/Feature/HealthcheckTest.php
# Способ 4: С флагами
./vendor/bin/pest tests/ --verbose --debug
```
### ✅ Конфигурация PhpStorm
1. Откройте `Settings` → `Tools` → `PHP` → `Test Frameworks`
2. Выберите `Pest` или создайте новый
3. Убедитесь, что путь указан как: `/path/to/project/vendor/bin/pest`
4. Установите `Default working directory` как корень проекта
### Почему так происходит?
- `vendor/bin` не находится в системном PATH
- Bash ищет команду `vendor/bin/pest` в PATH и не находит
- Нужно либо явно указать путь (`./vendor/bin/pest`), либо использовать PHP напрямую (`php vendor/bin/pest`)
