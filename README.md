# Описание

Bitrix CLI - набор инструментов командной строки для администрирования Bitrix.

В основе - [философия unix](https://ru.wikipedia.org/wiki/%D0%A4%D0%B8%D0%BB%D0%BE%D1%81%D0%BE%D1%84%D0%B8%D1%8F_UNIX).

Для запуска необходимо наличие PHP. Путь к PHP передается через окружение:

```
export BITRIX_CLI_PHP="/usr/bin/php7"
```

Если не передавать - используется PHP по-умолчанию (`which php`).

# Составные части программы

* `bitrix-element`, `bitrix-section`, `bitrix-iblock` - работа с инфоблоками: поиск, вывод содержимого, свойств инфоблока
* `bitrix-component` - компоненты: поиск в файле
* `bitrix-error` - вывод сообщений об ошибках
* `bitrix-event` - события: поиск, вывод в разлиных форматах
* `bitrix-user` - пользователи: поиск, вывод информации
* `bitrix-file` - файлы: поиск, вывод информации и содержимого файла
* `bitrix-option` - опции из БД

Примеры использования:

```
bitrix element 1000
bitrix file 1000 > file.jpg
bitrix user --group 1 | bitrix user --modify active=0
bitrix error
```
