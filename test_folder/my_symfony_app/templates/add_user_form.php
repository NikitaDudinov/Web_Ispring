<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Добавление пользователя</title>
    <link rel="stylesheet" href="/css/add_post.css">
</head>
<body>
<form action="/user/publish" method="post" class="add-post-form" enctype="multipart/form-data">
<div>
        <label for="first_name">first_name:</label>
        <input name="first_name" id="first_name" type="string">
    </div>
    <div>
        <label for="last_name">last_name:</label>
        <input name="last_name" id="last_name" type="string">
    </div>
    <div>
        <label for="middle_name">middle_name:</label>
        <input name="middle_name" id="middle_name" type="string">
    </div>
    <div>
        <label for="gender">gender:</label>
        <input name="gender" id="gender" type="string">
    </div>
    <div>
        <label for="birth_date">birth_date:</label>
        <input name="birth_date" id="birth_date" type="TEXT">
    </div>
    <div>
        <label for="email">email:</label>
        <input name="email" id="email" type="string">
    </div>
    <div>
        <label for="phone">phone:</label>
        <input name="phone" id="phone" type="string">
    </div>
    <div>
        <label for="image">Image:</label>
        <input type="file" id="ImageFile" name="ImageFile" accept="image/png, image/gif, image/jpeg"/>
    </div>
    <button type="submit">Submit</button>
</form>

<form action="/update_user.php" method="post">
    <div>
        <label for="user_id">Введите id пользователя которого нужно изменить:</label>
        <input name="user_id" id="user_id" type="int">
    </div>
    <button type="submit">Submit</button>
</form>
</body>
</html>
