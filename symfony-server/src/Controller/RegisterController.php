<?php

namespace App\Controller;

use Exception;
use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    const INPUT_NOT_FOUND = "Input is missing!";
    const PASSWORD_MATCH_ERROR = "Passwords do not match!";
    const INVALID_EMAIL = "Email is invalid!";
    const INSERT_FAILED = "Database insertion was not successful.";
    const SUCCESS = "Registration was successfull!";

    /**
     * @Route("/api/register", name="register", methods={"POST"})
     */
    public function Register(Request $request, ManagerRegistry $doctrine)
    {
        $results = [
            'success' => true,
            'message' => ''
        ];

        $data = json_decode($request->getContent(), true);
        try
        {
            $email = $data['email'];
            $password1 = $data['password1'];
            $password2 = $data['password2'];
            $name = $data['name'];
            $address = $data['address'];
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = self::INPUT_NOT_FOUND;
            return $this->json($results);
            die();
        }
        
        // $email = $request->request->get('email');
        // $password1 = $request->request->get('password1');
        // $password2 = $request->request->get('password2');
        // $name = $request->request->get('name');
        // $address = $request->request->get('address');

        if (!$email || !$password1 || !$password2 || !$name || !$address)
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

        if ($password1 !== $password2)
        {
            $results['success'] = false;
            $results['message'] = self::PASSWORD_MATCH_ERROR;
            return $this->json($results);
            die();
        }

        $hashedPassword = AuthController::Encrypt($password1);

        $em = $doctrine->getManager();

        $user = new Users();
        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        $user->setName($name);
        $user->setAddress($address);

        $em->persist($user);

        try {
            $em->flush();
        } catch (Exception $e) {
            $results['success'] = false;
            $results['message'] = $e->getMessage();
            return $this->json($results);
            die();
        }

        $results['message'] = self::SUCCESS;
        
        return $this->json($results);
    }



    
}