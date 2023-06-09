## ARM (Автоматизированое рабочее место)
(Автоматизированое рабочее место по вводу информации о протоколах испытаний материалов)<br/><br/>

![Главный экран приложения и пример валидации значения поля][homepage]

[homepage]: https://github.com/natural-coding/ARM/blob/main/screenshots/for-readme/01-homepage-and-validation.png "Главный экран приложения и пример валидации значения поля"


### Папки проекта

- [**src**](./src) => Всё, что относится к исходному коду
- [**project-management**](./project-management)
	- [!approach.txt](./project-management/!approach.txt) => Подход к решению задачи
	- [requirement-specification.txt](./project-management/requirement-specification.txt) => Техническое задание
	- [time-consuming-activities.txt](./project-management/time-consuming-activities.txt) => "Пожиратели" времени along the way
	- [timeline.txt](./project-management/timeline.txt) => Выделение времени с часами и минутами на проект
	- [timeline-detailed.txt](./project-management/timeline-detailed.txt) => Все 48 "коммитов" от пустой папки до конечного продукта
- [**screenshots**](./screenshots) => Главный экран приложения и примеры обрабатываемых ошибок

### Техническое задание
Выполните, пожалуйста, небольшое тестовое задание, так мы сможем лучше познакомиться и сэкономим и ваше время, и наше. Мы не ограничиваем по времени, но рассчитываем, что на выполнение у вас уйдет не больше часа. Если это не так, пожалуйста, дайте нам знать об этом и аргументируйте почему. Спасибо! 


ВАЖНО: для выполнения потребуется веб-сервер с php и сервер БД MySQL.

1. Создайте БД
2. Создайте таблицу в БД под названием PROTOCOL_TABLE
3. Добавьте в нее столбцы: 
	- Номер протокола
	- Дата выдачи
	- Ответственный сотрудник
	- Признак соответствия значений в протоколе нормам
4. Создайте на сервере файл protocol.php, который позволяет вывести в табличном виде данные из PROTOCOL_TABLE.

	- № п\п
	- Номер протокола
	- Дата выдачи (дд.мм.гг)
	- Ответственный (ФИО)
	- Соответствие («да», «нет»)

5. Создайте под таблицей с результатами запроса к БД кнопку: «Добавить протокол». При клике происходит переход к форме добавления записи в таблицу PROTOCOL_TABLE. В форме должны содержаться требуемые поля для заполнения и кнопка «сохранить».
6. По нажатию кнопки "сохранить", должна производится запись значений в 
PROTOCOL_TABLE и возврат к таблице с протоколами. 
7. В случае, когда указанный номер протокола совпадает с уже существующим в базе, должно появляться всплывающее окно с текстом предупреждения. Сохранения в БД введенных данных при этом не производится.

### Заметки по выполнению проекта

Проект выполнялся в спешке (кое-что скопипастил, без ООП и пространств имен, без frontend), т.к. срок 1 час изначально задан был, но проверки введенных данных сделал, все работает.

Форма ввода данных ещё без security token отдаётся с сервера. (Т.е. не весь код соответствует OWASP и phptherightway.com)

Показывается типовое сообщение об ошибке "Проверьте данные", хотя в бекенде целый массив отдаётся как JSON с подробным описанием ошибок валидации данных для каждого поля формы. (Thanks to [jQuery in Action book](https://www.manning.com/books/jquery-in-action-third-edition)! Chapter 11!)

Сделал за два дня (см. файлы [timeline.txt](./project-management/timeline.txt), [timeline-detailed.txt](./project-management/timeline-detailed.txt)), т.к. опыта именно в приложениях такого типа не было. Время потратилось, чтобы настроиться на PHP, Ajax и frontend, и плюс разбирался с кириллицей и встроенными функциями MySQL.
