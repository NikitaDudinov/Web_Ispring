<?php
declare(strict_types=1);
echo '<p> First Name: '. htmlentities($user->GetFirstName(), ENT_QUOTES) . '</p>';
echo '<p> Last Name: '. htmlentities($user->GetLastName(), ENT_QUOTES) . '</p>';
echo '<p> Middle Name: '. htmlentities($user->GetMiddleName(), ENT_QUOTES) . '</p>';
echo '<p> Gender: '. htmlentities($user->GetGender(), ENT_QUOTES) . '</p>';
echo '<p> Email: '. htmlentities($user->GetEmail(), ENT_QUOTES) . '</p>';
echo '<p> Phone: ' . htmlentities($user->GetPhone(), ENT_QUOTES) . '</p>';
echo '<p> Avatar Path: '. htmlentities($user->GetAvatarPath(), ENT_QUOTES) . '</p>';
echo '<img src="../uploads/'. htmlentities($user->GetAvatarPath(), ENT_QUOTES). '" alt="">';