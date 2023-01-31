<?php

namespace App\Controller;

use Exception;
use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{   
    const INPUT_NOT_FOUND = "Input is missing!";
    const WRONG_EMAIL_PASSWD = "Wrong email or password!";

    /**
     * @Route("/api/login", name="login", methods={"POST"})
     */
    public function Login(Request $request, ManagerRegistry $doctrine)
    {
        $results = [
            'success' => true,
            'message' => '',
            'token' => '',
        ];

        $data = json_decode($request->getContent(), true);
        try
        {
            $email = $data['email'];
            $password = $data['password'];
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = self::INPUT_NOT_FOUND;
            return $this->json($results);
            die();
        }

        // $email = $request->request->get('email');
        // $password = $request->request->get('password');


        if (!$email || !$password) {
            $results['success'] = false;
            $results['message'] = self::INPUT_NOT_FOUND;
            return $this->json($results);
        }

        

        $em = $doctrine->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('u')
            ->from(Users::class, 'u')
            ->where('u.email = :email')
            ->andWhere('u.active = :active')
            ->setParameter('email', $email)
            ->setParameter('active', 1);
        
        $query = $qb->getQuery();
        $user = $query->getOneOrNullResult();

        if (!$user) {
            $results['success'] = false;
            $results['message'] = self::WRONG_EMAIL_PASSWD;
            return $this->json($results);
        }

        if (AuthController::Decrypt($user->getPassword()) != $password) {
            $results['success'] = false;
            $results['message'] = self::WRONG_EMAIL_PASSWD;
            return $this->json($results);
        }
        
        $results['token'] = AuthController::Encrypt($email . ' ' . AuthController::Encrypt($password));
        

        
        return $this->json($results);
    }



    
}