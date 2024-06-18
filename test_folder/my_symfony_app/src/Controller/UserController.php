<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\UserServiceInterface;
use App\Service\ImageServiceInterface;

class UserController extends AbstractController
{
    private UserServiceInterface $userService;
    private ImageServiceInterface $imageService;
    public function __construct(UserServiceInterface $userService, ImageServiceInterface $imageService)
    {
        $this->userService = $userService;
        $this->imageService = $imageService;
    }

    public function index(): Response
    {
        return $this->render('index.html.twig', ['array_action' => [['Добавить пользователя', '/user/publish_form'], 
                                                                    ['Удалить пользователя', '/user/delete_form'],
                                                                    ['Показать пользователя', '/user/show_form'],
                                                                    ['Изменить пользователя', '/user/update_form'],
                                                                    ['Отобразить всех', '/users'],
                                                                   ]]);
    }

    public function publish_form(): Response
    {
        return $this->render('add_user.html.twig', ['action' => '/user/publish', 'user_id' => '']);
    }

    public function delete_form(): Response
    {
        return $this->render('input_id.html.twig', ['title' => 'Введите id пользователя, котрого нужно удалить', 'action' => '/user/delete', 'method' => 'POST']);
    }

    public function show_form(): Response
    {
        return $this->render('input_id.html.twig', ['title' => 'Введите id пользователя, котрого нужно показать', 'action' => '/user/show', 'method' => 'GET']);
    }

    public function update_form(): Response
    {     
        return $this->render('input_id.html.twig', ['title' => 'Введите id пользователя которого нужно изменить:', 'action' => '/user/update_content', 'method' => 'POST']);
    }

    public function showAllUser(): Response
    {
        $ListUsers = $this->userService->showUsers();
        return $this->render('list_users.html.twig', ['users_list' => $ListUsers]);
    }

    public function publishUser(Request $request): Response
    {
        $imageData = $request->files->get('ImageFile');
        if ($imageData !== null)
        {
            $imageArr = [
                'type' => $imageData->getClientMimeType(),
                'name' => $imageData->getFilename(),
                'tmp_name' => $imageData->getPathname(),
                'error' => $imageData->getError(),
            ];
            $pathImage = $this->imageService->moveImageToUploads($imageArr);
        }
        else{
            $pathImage = null;
        }   
        $userId = $this->userService->saveUser($request->get('first_name'), $request->get('last_name'), $request->get('middle_name'), $request->get('gender'), $request->get('birth_date'), $request->get('email'), $request->get('phone'), $pathImage);
        if($userId === null){
            $message = 'Остались поля, котороые должны быть заполненными';
            return $this->render('user_page.html.twig', ['message' => $message]);
        }
        return $this->redirectToRoute('show_user', ['user_id' => $userId], Response::HTTP_SEE_OTHER);        
    }

    public function showUser(Request $request): Response
    {
        $userId =  $request->get('user_id');
        if(empty($userId))
        {
            return $this->redirect('index');
        }
        $user =$this->userService->getUser((int)$userId);
        if($user !== null)
        {
            return $this->render('view_user.html.twig', ['user' => $user]);
        }
        else
        {
            $message = "Пользователя с id = $userId не существует";
            return $this->render('user_page.html.twig', ['message' => $message]);
        }
    }
    public function deleteuser(Request $request): ?Response
    {
        $userId = $request->get('user_id') ?? null;
        if(!empty($userId ))
        {
            $avatarPath = $this->userService->deleteUser((int) $userId);
            if(!empty($avatarPath)){
                $this->imageService->deleteImage($avatarPath);
                $message = "Пользователя с id = $userId успешно удален";
                return $this->render('user_page.html.twig', ['message' => $message]);
            }
            $message = "Пользователя с id = $userId не существует";
            return $this->render('user_page.html.twig', ['message' => $message]);
        }
    }
    

    public function updateUser(Request $request): ?Response
    {
        $userId = $request->get('user_id');
        $user =$this->userService->getUser((int)$userId);
        if(empty($user))
        {
            $message = "Пользователя с таким id не существует";
            return $this->render('user_page.html.twig', ['message' => $message]);
        }
        $pathImage = $this->userService->getUserPathImage((int) $userId);
        if(empty($request->files->get('ImageFile')))
        {
            $newPathImage = null;
        }
        else
        {
            $imageData = $request->files->get('ImageFile');
            if ($imageData !== null)
            {
                $imageArr = [
                    'type' => $imageData->getClientMimeType(),
                    'name' => $imageData->getFilename(),
                    'tmp_name' => $imageData->getPathname(),
                    'error' => $imageData->getError(),
                ];
            }
            $newPathImage = $this->imageService->updateImage($pathImage, $imageArr);
        }
        $user = $this->userService->updateUser((int) $userId, $request->get('first_name'), $request->get('last_name'), $request->get('middle_name'), $request->get('gender'), $request->get('birth_date'), $request->get('email'), $request->get('phone'), $newPathImage);    
        return $this->render('update_user_page.html.twig', ['action' => '/user/update_content', 'user' => $user, 'user_id' => $userId]);
    }

}
