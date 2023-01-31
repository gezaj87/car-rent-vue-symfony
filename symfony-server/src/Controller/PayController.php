<?php

namespace App\Controller;

use Exception;
use App\Entity\Users;
use App\Entity\Payments;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PayController extends AbstractController
{   
    const INPUT_NOT_FOUND = "Input is missing!";
    const BANKCARD_ERROR = "Bankcard must be 16 digits!";
    const EXP_DATE_ERROR = "Expiration date must be 4 digits!";
    const CVC_ERROR = "CVC number must be 3 digits!";
    const CAR_PRICE_ERROR = "The given price do not match with the one in database! Or plate number is not found!";
    const PAYMENT_SUCCESS = "Payment was successful!";
    const PAYMENT_ERROR = "Payment was unsuccessful!";

    /**
     * @Route("/api/pay/", name="pay", methods={"POST"})
     */
    public function Pay(Request $request, ManagerRegistry $doctrine)
    {
        $results = [
            'success' => true,
            'message' => '',
            'payment_id' => ''
        ];

        $data = json_decode($request->getContent(), true);
        try
        {
            $token = $data['token'];
            $name = $data['name'];
            $cardNumber = preg_replace('/\s+/', '', $data['cardNumber']);
            $expDate = preg_replace('/\/+/', '', $data['expDate']);
            $cvc = $data['cvc'];
            $price = $data['price'];
            $plate = $data['plate'];

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
            if (empty($token) || empty($name) || empty($cardNumber) || empty($expDate) || empty($cvc) || empty($price) || empty($plate)) {
                throw new Exception(self::INPUT_NOT_FOUND);
            }

            if (strlen($cardNumber) !== 16 || !ctype_digit($cardNumber)) {
                throw new Exception(self::BANKCARD_ERROR);
            }

            if (strlen($expDate) !== 4 || !ctype_digit($expDate)) {
                throw new Exception(self::EXP_DATE_ERROR);
            }

            if (strlen($cvc) !== 3 || !ctype_digit($cvc)) {
                throw new Exception(self::CVC_ERROR);
            }

            if (!ctype_digit($price)) {
                throw new Exception(self::CAR_PRICE_ERROR);
            }
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
            $userData = AuthController::AuthUserData($token, $doctrine);
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = $e->getMessage();
            return $this->json($results);
            die();
        }


        //price check
        try
        {
            self::CheckPrice($price, $plate, $doctrine);
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = $e->getMessage();
            return $this->json($results);
            die();
        }


        //send request to bank
        try
        {
            $data_to_bank = [
                "token" => $_ENV['BANKTOKEN'],
                "name" => $name,
                "card_number" => $cardNumber,
                "exp_date" => $expDate,
                "cvc" => $cvc,
                "price" => $price
            ];
            
            $data_string = json_encode($data_to_bank);
            
            $ch = curl_init('http://127.0.0.1/sop/pay');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            ]);
            
            $result = curl_exec($ch);
            
            $response = json_decode($result, true);

            if (!$response["success"]) {
                $res["success"] = false;
                $res["message"] = $response["message"];
            }
            else
            {
                self::SavePayment($response['payment_id'], $userData, $plate, $price, $doctrine);
                self::SetCarAvailability($plate, $doctrine);
            }
        }
        catch(Exception $e)
        {
            $results['success'] = false;
            $results['message'] = self::PAYMENT_ERROR;
            return $this->json($results);
            die();
        }

        $results['message'] = self::PAYMENT_SUCCESS;
        $results['payment_id'] = $response['payment_id'];
        return $this->json($results);


    }

    public static function CheckPrice($price, $plate, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();

        $car = $em->createQuery("SELECT c.price
            FROM App\Entity\Cars c
            WHERE c.plate = :plate")
            ->setParameter('plate', $plate)
            ->getOneOrNullResult();

        if ($car['price'] !== $price || empty($car)) {
            throw new Exception(self::CAR_PRICE_ERROR);
        }
    }

    public static function SavePayment($payment_id, $user, $plate, $price, ManagerRegistry $doctrine)
    {
        try
        {
            $em = $doctrine->getManager();

            $payment = new Payments();
            $payment->setPId($payment_id);
            $payment->setUId($user->getUId());
            $payment->setPlate($plate);
            $payment->setPrice($price);
            // $payment->setDate(new \DateTime());

            $em->persist($payment);
            $em->flush();
        }
        catch(Exception $e)
        {
            throw $e;
        }

    }

    public static function SetCarAvailability($plate, ManagerRegistry $doctrine)
    {
        try
        {
            $em = $doctrine->getManager();

            $car = $em->createQuery("SELECT c
                FROM App\Entity\Cars c
                WHERE c.plate = :plate")
                ->setParameter('plate', $plate)
                ->getOneOrNullResult();

            $car->setAvailable(0);

            $em->persist($car);
            $em->flush();
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }


    
}