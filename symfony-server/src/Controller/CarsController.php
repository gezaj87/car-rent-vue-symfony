<?php

namespace App\Controller;

use Exception;
use App\Entity\Users;
use App\Entity\Cars;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarsController extends AbstractController
{   
    const INPUT_NOT_FOUND = "Input is missing!";
    const WRONG_EMAIL_PASSWD = "Wrong email or password!";

    /**
     * @Route("/api/cars/", name="cars", methods={"POST"})
     */
    public function GetCars(Request $request, ManagerRegistry $doctrine)
    {
        $results = [
            'success' => true,
            'message' => '',
            'cars' => []
        ];

        $data = json_decode($request->getContent(), true);
        try
        {
            $token = $data['token'];
            $avaiableOnly = $data['avaiableOnly'];
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
            $cars = $em->getRepository(Cars::class)->findBy([], ['available' => 'DESC']);
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = $e->getMessage();
            return $this->json($results);
            die();
        }

        foreach($cars as $car)
        {
            if($avaiableOnly && !$car->getAvailable())
            {
                continue;
            }
            $results['cars'][] = [
                'plate' => $car->getPlate(),
                'year' => $car->getYear(),
                'capacity' => $car->getCapacity(),
                'price' => $car->getPrice(),
                'image' => $car->getImage(),
                'avaiable' => $car->getAvailable()? 1 : 0,
                'brand' => $car->getBrand(),
            ];
        }

        return $this->json($results);

    }


    
}