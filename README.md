###Задание с SQL

Необходимо составить запрос, который находит пользователя, сумма платежей которого находится 
на втором месте после максимальной.

```sql
SELECT student_id
FROM payments
GROUP BY student_id
ORDER BY sum(amount)
LIMIT 1
```

Необходимо показать имена и фамилии всех студентов,
чей пол до сих не известен (gender = 'unknown') 
и они сейчас находятся на каникулах (status = ‘vacation’).

```sql
SELECT
  st.name,
  st.surname
FROM
  (SELECT
     student.id,
     student.name,
     student.surname,
     student.gender
   FROM student) st,
  (SELECT
     student_status.student_id,
     student_status.status
   FROM student_status
   ORDER BY student_status.datetime DESC
   LIMIT 1) ss
WHERE st.id = ss.student_id
      AND st.gender = 'unknown'
      AND ss.status = 'vacation';
```

Используя три предыдущие таблицы, найти имена и фамилии всех студентов,
которые заплатили не больше трех раз и перестали
учиться (status = ‘lost’). Нулевые платежи (amount = 0) не учитывать.

```sql
SELECT
  st.name,
  st.surname
FROM
  (SELECT
     student.id,
     student.name,
     student.surname
   FROM student
   WHERE id IN (
     SELECT student_id
     FROM payments
     WHERE amount > 0
     GROUP BY student_id
     HAVING COUNT(student_id) > 1
   )) st,
  (SELECT
     student_status.student_id,
     student_status.status
   FROM student_status
   ORDER BY student_status.datetime DESC
   LIMIT 1) ss
WHERE st.id = ss.student_id
      AND ss.status = 'lost'
```

###Приложения статистики

Демо
~~~
http://sky-test.raketa.guru/
~~~

Контроллер
~~~
controllers/SiteController.php
~~~

Модель
~~~
models/Customers.php
~~~

Отображение
~~~
views/site/index.php
~~~

Для наполнение базы данными нужно сгенерировать фикитуры (или загрузить SQL дамп customers.sql)

~~~
php yii fixture/generate --count=10000 --language="ru_RU"
php yii fixture/load Customers
~~~

###Приложение логгера

Запуск теста
~~~
php yii test-logs
~~~

Тест
~~~
commands/TestLogsController.php
~~~

Логер
~~~
commands/Logs.php
~~~