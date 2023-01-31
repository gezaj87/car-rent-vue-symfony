<?php

namespace App\Controller;

use Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Users;

class AuthController extends AbstractController
{
    const AUTH_ERROR = "Email and password do not match!";
    const WRONG_TOKEN = "Token is not valid!";
    const TOKEN_NOT_FOUND = "Token is not found!";
    const INPUT_MISSING = "Input is missing!";


    /**
     * @Route("/api/auth", name="auth", methods={"POST"})
     */
    public function Auth(Request $request, ManagerRegistry $doctrine)
    {
        $results = [
            'success' => true,
            'message' => ''
        ];

        // $token = $request->request->get('token');
        $data = json_decode($request->getContent(), true);
        try
        {
            $token = $data['token'];
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = self::INPUT_MISSING;
            return $this->json($results);
            die();
        }
        

        try
        {
            $userData = self::AuthUserData($token, $doctrine);
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = $e->getMessage();
            return $this->json($results);
            die();
        }

        
        return $this->json($results);
    }

    public static function AuthUserData($token, ManagerRegistry $doctrine)
    {
        if (!$token)
        {
            throw new Exception(self::TOKEN_NOT_FOUND);
        }

        $decryptedToken = self::Decrypt($token);
        if (!$decryptedToken) {
            throw new Exception(self::WRONG_TOKEN);
        }

        $tokenData = explode(" ", $decryptedToken);
        if (!$tokenData || count($tokenData) != 2) {
            throw new Exception(self::WRONG_TOKEN);
        }

        $email = $tokenData[0];
        $password = $tokenData[1];

        // $user = $doctrine->getRepository('Users')->findOneBy([
        //     'email' => $email,
        //     'password' => $password
        // ]);
        
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
            throw new Exception(self::AUTH_ERROR);
        }

        if (self::Decrypt($user->getPassword()) != self::Decrypt($password)) {
            throw new Exception(self::AUTH_ERROR);
        }

        return $user;

    }

    public static function Encrypt(string $text): string
    {
        $key = $_ENV['APP_SECRET'];

        $ivlen = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($ivlen);
        return base64_encode($iv . openssl_encrypt($text, 'aes-256-cbc', $key, 0, $iv));
    }

    public static function Decrypt(string $encryptedText): string
    {
        $key = $_ENV['APP_SECRET'];

        $ciphertext = base64_decode($encryptedText);
        $ivlen = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($ciphertext, 0, $ivlen);
        $iv = str_pad($iv, 16, "\0");
        $ciphertext = substr($ciphertext, $ivlen);
        return openssl_decrypt($ciphertext, 'aes-256-cbc', $key, 0, $iv);
    }

    
}