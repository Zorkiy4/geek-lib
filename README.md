ТЕСТОВЫЙ ПРОЕКТ НА Yii 2
========================

небольшой сайт библиотеки. Регистрации пользователей нет. На сайте гость может искать книгу по названию,
также есть алфавитный указатель и дерево категорий. Перейдя на страницу книги гость видит её фото, описание
и ссылку для скачивания. При скачивании администратору приходит письмо, что скачали такую-то книгу.

В админ-панели администратор может добавлять, редактировать и удалять книги и категории, а также массово переносить
книги разных категорий в единую другую категорию.

Доступ на страницы администрирования появляется после входа на сайт. Для входа нужно использовать пару admin/admin.

УСТАНОВКА
---------

### Из архива

Распакуйте архив в директорию с установленным фреймворком Yii 2.

Созадайте базу данных. Для формирования структуры БД в пакет добавлен файл /web/intal_db.php. Отредактируйте в его
начале параметры доступа к БД и перейдите по соответствующему URL через браузер. Будет создана требуемая структура БД.