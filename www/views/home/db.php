<h1>Работа с БД. Технология PDO</h1>

<?php

if ($this->get_db() === null)
{
  echo 'Ошибка подключения';
}
else
{
  echo 'Подключение успешно';
}

//phpinfo();
?>