<?php

namespace App\Controller;

use Exception;
use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    const INPUT_NOT_FOUND = "Input is missing!";
    const PASSWORD_MATCH_ERROR = "Passwords do not match!";
    const INVALID_EMAIL = "Email is invalid!";
    const INSERT_FAILED = "Database insertion was not successful.";
    const SUCCESS = "Registration was successfull!";
    const UPDATE_SUCCESS = "Update was successfull!";
    const DELETE_FAILED = "Delete was not successfull!";
    const UPDATE_FAILED = "Update was not successfull!";
    const DELETE_SUCCESS = "Delete was successfull!";


    /**
     * @Route("/api/user", name="user_data", methods={"POST"})
     */
    public function GetUserData(Request $request, ManagerRegistry $doctrine)
    {
        $results = [
            'success' => true,
            'message' => '',
            'userData' => []
        ];

        $data = json_decode($request->getContent(), true);
        try
        {
            $token = $data['token'];
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = self::INPUT_NOT_FOUND;
            return $this->json($results);
            die();
        }

        // $token = $request->request->get('token');

        try
        {
            $userData = AuthController::AuthUserData($token, $doctrine);
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = $e->getMessage();
            return $this->json($results);
            die();
        }

        $results['userData'] = [
            'email' => $userData->getEmail(),
            'name' => $userData->getName(),
            'address' => $userData->getAddress(),
        ];

        return $this->json($results);

    }

    /**
     * @Route("/api/user", name="user_data_update", methods={"PUT"})
     */
    public function PutUserData(Request $request, ManagerRegistry $doctrine)
    {
        $results = [
            'success' => true,
            'message' => '',
            'logout' => false
        ];

        $data = json_decode($request->getContent(), true);
        try
        {
            $token = $data['token'];
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = self::INPUT_NOT_FOUND;
            return $this->json($results);
            die();
        }

        // $token = $request->request->get('token');

        try
        {
            $userData = AuthController::AuthUserData($token, $doctrine);
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = $e->getMessage();
            return $this->json($results);
            die();
        }

        $email = $data['email'];
        $name = $data['name'];
        $address = $data['address'];
        $isPasswordChanged = $data['isPasswordChanged'];
        $oldPassword = $data['oldPassword'];
        $newPassword1 = $data['newPassword1'];
        $newPassword2 = $data['newPassword2'];

        if (!$email || !$name || !$address)
        {
            $results['success'] = false;
            $results['message'] = self::INPUT_NOT_FOUND;
            return $this->json($results);
            die();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $results['success'] = false;
            $results['message'] = self::INVALID_EMAIL;
            return $this->json($results);
            die();
        }

        if ($isPasswordChanged)
        {
            if (!$oldPassword || !$newPassword1 || !$newPassword2)
            {
                $results['success'] = false;
                $results['message'] = self::INPUT_NOT_FOUND;
                return $this->json($results);
                die();
            }

            if ($newPassword1 != $newPassword2)
            {
                $results['success'] = false;
                $results['message'] = self::PASSWORD_MATCH_ERROR;
                return $this->json($results);
                die();
            }

            if (AuthController::Decrypt($userData->getPassword()) != AuthController::Decrypt($oldPassword))
            {
                $results['success'] = false;
                $results['message'] = self::PASSWORD_MATCH_ERROR;
                return $this->json($results);
                die();
            }
        
        }


        if ($isPasswordChanged)
        {
            $em = $doctrine->getManager();
            $user = $em->find(Users::class, $userData->getId());

            if (!$user) {

                $results['success'] = false;
                $results['message'] = self::INSERT_FAILED;
                return $this->json($results);
                die();
            }

            $user->setName($name);
            $user->setEmail($email);
            $user->setAddress($address);
            $user->setPassword(AuthController::Encrypt($newPassword1));

            $em->persist($user);
            $em->flush();
        }
        else
        {
            try
            {
                $em = $doctrine->getManager();
                $user = $em->getRepository(Users::class)->find($userData->getUId());

                if (!$user) {

                    $results['success'] = false;
                    $results['message'] = self::INSERT_FAILED;
                    return $this->json($results);
                    die();
                }

                $user->setName($name);
                $user->setEmail($email);
                $user->setAddress($address);

                $em->persist($user);
                $em->flush();
            }
            catch(Exception $e)
            {
                $results['success'] = false;
                $results['message'] = self::UPDATE_FAILED;
                return $this->json($results);
                die();
            }
        }


        if ($userData->getEmail() != $email || $isPasswordChanged)
        {
            $results['logout'] = true;
        }

        $results['message'] = self::UPDATE_SUCCESS;
        return $this->json($results);


        

    }

    /**
     * @Route("/api/user", name="user_data_delete", methods={"DELETE"})
     */
    public function DeleteUserData(Request $request, ManagerRegistry $doctrine)
    {
        $results = [
            'success' => true,
            'message' => '',
            'logout' => false
        ];

        $data = json_decode($request->getContent(), true);
        try
        {
            $token = $data['token'];
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = self::INPUT_NOT_FOUND;
            return $this->json($results);
            die();
        }

        try
        {
            $userData = AuthController::AuthUserData($token, $doctrine);
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = $e->getMessage();
            return $this->json($results);
            die();
        }

        try
        {
            $em = $doctrine->getManager();
            $user = $em->getRepository(Users::class)->find($userData->getUId());

            if (!$user) {

                $results['success'] = false;
                $results['message'] = self::INSERT_FAILED;
                return $this->json($results);
                die();
            }

            $user->setActive(0);

            $em->persist($user);
            $em->flush();
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = self::DELETE_FAILED;
            return $this->json($results);
            die();
        }

        $results['message'] = self::DELETE_SUCCESS;
        $results['logout'] = true;

        return $this->json($results);

    }


    
}