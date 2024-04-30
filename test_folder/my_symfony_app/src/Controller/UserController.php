<?php
declare(strict_types=1);

namespace App\Controller;

use App\Database\ConnectionProvider;
use App\Database\UserTable;
use App\Model\User;
use App\View\PhpTemplateEngine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserController extends AbstractController
{
    private UserTable $userTable;

    public function __construct()
    {
        $this->userTable = new UserTable(ConnectionProvider::connectDatabase());
    }

    public function index(): Response
    {
        $contents = PhpTemplateEngine::render('mainpage.php');
        return new Response($contents);
    }

    public function publish_form(): Response
    {
        $contents = PhpTemplateEngine::render('add_user_form.php');
        return new Response($contents);
    }

    public function delete_form(): Response
    {
        $contents = PhpTemplateEngine::render('delete_user_form.php');
        return new Response($contents);
    }

    public function show_form(): Response
    {
        $contents = PhpTemplateEngine::render('show_user_form.php');
        return new Response($contents);
    }

    public function publishUser(Request $request): Response
    {
        try{
            $user = new User(
                null, 
                $request->get('first_name'), 
                $request->get('last_name'), 
                $request->get('middle_name'), 
                $request->get('gender'), 
                $request->get('birth_date'), 
                $request->get('email'), 
                $request->get('phone'),
                $request->get('avatar_path'),
            );
            $userId = $this->userTable->add($user);
            var_dump($_FILES);
            if(!empty($_FILES["ImageFile"]["tmp_name"])){
                $format = mime_content_type($_FILES["ImageFile"]["tmp_name"]) ;
                var_dump($format);
                switch ($format) {
                    case 'image/png':
                        $format = '.png';
                        break;
                    case 'image/gif':
                         $format = '.gif';
                        break;
                    case 'image/jpeg':
                         $format = '.jpeg';
                        break;
                    default:
                        $format = null;
                }
                if($format != null){
                    move_uploaded_file($_FILES['ImageFile']['tmp_name'], "./uploads/avatar" . $userId . $format);
                    $this->userTable->update_avatar_path($userId, $format);
                }
                else{
                    echo "Неверный формат картинки";
                }
            }
        }catch(\Exception $e){
            echo "Error database:". $e->getMessage() ."";
        }
         return $this->redirectToRoute('show_user', ['userId' => $userId], Response::HTTP_SEE_OTHER);
    }

    public function showUser(int $userId): Response
    {
        if ($userId === null)
        {
            throw new \InvalidArgumentException('Parameter id is not defined');
        }
        $user = $this->userTable->find((int) $userId);
        if (!$user)
        {
            throw $this->createNotFoundException();
        }
        $contents = PhpTemplateEngine::render('user.php', ['user' => $user]);
        return new Response($contents);
    }
    public function findUser(Request $request): Response
    {
        $userId = $request->get('user_id') ?? null;
        if ($userId === null)
        {
            throw new \InvalidArgumentException('Parameter id is not defined');
        } 
        else
        {
            return $this->redirectToRoute('show_user', ['userId' => $userId], Response::HTTP_SEE_OTHER);
        }
    }
    public function deleteUser(Request $request): Response
    {
       $id = $request->get('user_id') ?? null;
        if ($id === null)
        {
            throw new \InvalidArgumentException('Parameter id is not defined');
        }
        $this->userTable->delete((int) $id);
        return $this->redirectToRoute('index');
    }

    public function updateUser(array $request): void
    {
        $userId = $request['user_id'];
        foreach($request as $key => $value){
            if(!empty($value) && $key != 'user_id'){
                $this->userTable->update($key, $value, intval($userId));
            }
            if(!empty($_FILES["ImageFile"]["tmp_name"])){
                $format = mime_content_type($_FILES["ImageFile"]["tmp_name"]) ;
                switch ($format) {
                    case 'image/png':
                        $format = '.png';
                        break;
                    case 'image/gif':
                         $format = '.gif';
                        break;
                    case 'image/jpeg':
                         $format = '.jpeg';
                        break;
                    default:
                        $format = null;
                }
                if($format != null){
                    move_uploaded_file($_FILES['ImageFile']['tmp_name'], "uploads/avatar" . $userId . $format);
                    $this->userTable->update_avatar_path(intval($userId), $format);
                }
            }    
        }
    }
}

