<?php

namespace App\Controller;

use Exception;
use App\Entity\Users;
use App\Entity\Payments;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoryController extends AbstractController
{   
    const INPUT_NOT_FOUND = "Input is missing!";
    const HISTORY_ERROR = "No data correspondig to this user in database!";

    /**
     * @Route("/api/history/", name="history", methods={"POST"})
     */
    public function GetHistory(Request $request, ManagerRegistry $doctrine)
    {
        $results = [
            'success' => true,
            'message' => '',
            'history' => []
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

        
        $em = $doctrine->getManager();
        
        try
        {
            $histories = $em->createQuery("SELECT p.pId as p_id, p.plate, p.price, p.date as date
                FROM App\Entity\Payments p
                WHERE p.uId = :uid
                ORDER BY p.date DESC")
                ->setParameter('uid', $userData->getUId())
            ->getResult();

            // print_r($histories);
            
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = $e->getMessage();
            return $this->json($results);
            die();
        }

        foreach($histories as $history)
        {
            $results['history'][] = [
                'p_id' => $history['p_id'],
                'plate' => $history['plate'],
                'price' => $history['price'],
                'date' => $history['date']->format("Y-m-d H:i")
            ];
        }

        return $this->json($results);

    }


    
}