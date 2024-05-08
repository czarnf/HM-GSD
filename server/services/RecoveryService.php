<?php

namespace app\services;

use app\config\MysqlDBH;
use app\dto\ResponseDto;
use app\model\Contact;
use app\model\Recovery;
use app\services\impl\RecoveryServiceImpl;
use app\utils\JWTHandler;
use app\utils\PasswordEncoder;

class RecoveryService implements RecoveryServiceImpl
{

    private $model;
    private $mail;

    function __construct()
    {
        $mysqlConnector = new MysqlDBH();
        $this->model = new Recovery($mysqlConnector);
        $this->mail = new MailerService();
    }


    function setRecovery(array $data): string
    {
        $response = $this->model->createRecovery($data);
        if (is_bool($response)) {
            $this->mail->sendNotification(
                $data["recovery_name"],
                $data["recovery_email"],
                $data["recovery_phone"],
                $data["recovery_broker"],
                $data["recovery_amount"],
            );
            return ResponseDto::json("Your free consultation has been scheduled. We will send you a mail soon!", 201);
        } else if (is_string($response)) {
            return ResponseDto::json("Your free consultation has already been scheduled. We will get back to you before the next 24 hours!", 201);
        }

        return ResponseDto::json("An error was encountered while trying to schedule a free consultation. Try again later.", 302);
    }


    function getRecoveries(): string
    {
        $response = $this->model->fetchRecoveries();
        return ResponseDto::json("fetched recovery fun request!", 200, $response);
    }

    function getOnRecoveries(): string
    {
        $response = $this->model->fetchOnRecoveries();
        return ResponseDto::json("fetched recovery fun request!", 200, $response);
    }



    function updateRecoveryStatus(int $status, string $depositSlug): string
    {
        $response = $this->model->editRecovery($depositSlug, $status);
        if ($response) {
            return ResponseDto::json("This recovery process status has been successfully updated!", 200);
        }
        return ResponseDto::json("An error was encountered while trying to update recovery status...", 500);
    }




    function getOnRecoveryBySlug(string $slug): string
    {
        $response = $this->model->fetchBySlug($slug);
        return ResponseDto::json($response, 200);
    }

    // Auth


    function registerUser(string $email, string $code): string
    {
        $response = $this->model->fetchOn($email);

        if (is_array($response) && count($response) > 3) {

            if ($response["on_code"] == $code) {
                return ResponseDto::json($response["on_recovery_id"], 201);
            }
            return ResponseDto::json("This user code is incorrect. Please check and try again!", 500);
        }
        return ResponseDto::json("We are unable to identify this user. Please contact us via our email address!", 422);
    }


    function userAuthentication(string $email, string $password): string
    {
        $response = $this->model->fetchOn($email);
        if (count($response) > 4) {
            $passwordHash = $response["on_password"];
            if (PasswordEncoder::decodePassword($password, $passwordHash)) {
                return ResponseDto::json($response, 200);
                // return ResponseDto::json( JWTHandler::encodePayload($response), 200);
            }
            return ResponseDto::json("Your password is not recognized!");
        }
        return ResponseDto::json("Your email address is not recognized!");
    }


    function changePassword(int $id, string $password,): string
    {
        $response = $this->model->updatePassword($id, PasswordEncoder::encodePassword($password));
        if ($response) {
            return ResponseDto::json("Account password has been updated successfully!", 200);
        }
        return ResponseDto::json("We are unable to update the account password at this time. Please try again later!", 404);
    }

    function editCollectedAmount(string $slug, string $amount,): string
    {
        $response = $this->model->updateCollectedAmount($slug, $amount);
        if ($response) {
            return ResponseDto::json("Amount update was successful!", 200);
        }
        return ResponseDto::json("We are unable to update the amountat this time. Please try again later!", 404);
    }

    function editPassword(string $email, string $password, string $oldPassword): string
    {
        $res = $this->model->fetchOn($email);
        if (is_array($res) && count($res) > 4) {
            $id = (int) $res["on_recovery_id"];
            $passwordHash = $res["on_password"];
            if (PasswordEncoder::decodePassword($oldPassword, $passwordHash) && $id > 0) {
                $response = $this->model->updatePassword($id, PasswordEncoder::encodePassword($password));
                if ($response) {
                    return ResponseDto::json("Account password has been updated successfully!", 200);
                }
            }
            return ResponseDto::json("Your password is not recognized!");
        }
        return ResponseDto::json("We are unable to update the account password at this time. Please try again later!", 404);
    }

    function deleteUser(string $slug): string
    {
        $response = $this->model->editRecovery($slug, 4);
        if ($response) {
            return ResponseDto::json("This user account has been deleted successfully!", 200);
        }
        return ResponseDto::json("We are unable to delete this user details at this time. Please try again later!", 404);
    }


    // setWR
    function setWR(array $data): string
    {
        // wr_user_id,  wr_slug, wr_amount, wr_address, wr_account_type

        $response = $this->model->createWR($data);
        if (is_bool($response)) {
            if ($response) {
                return ResponseDto::json("Withdraw request has been created.", 201);
            }
            return ResponseDto::json("An issue was encountered. Please try again later!");
        }
        return ResponseDto::json("Sorry, You have an unattended withdraw request. Please wait until that request is resolved! Thanks.");
    }

    // getWRRejected
    function getWRRejected(): string
    {
        $response = $this->model->fetchWRByStatus(4);
        return ResponseDto::json($response, 200);
    }

    // getWRAccepted
    function getWRAccepted(): string
    {
        $response = $this->model->fetchWRByStatus(1);
        return ResponseDto::json($response, 200);
    }

    // getWRNew
    function getWRNew(): string
    {
        $response = $this->model->fetchWRByStatus(0);
        return ResponseDto::json($response, 200);
    }

    function getUserWR(int $id): string
    {
        $response = $this->model->fetchWRByUserId($id);
        return ResponseDto::json($response, 200);
    }


    // editWR
    function rejectWR(string $message, string $slug): string
    {
        $payload = array(
            "wr_status" => 4, "wr_message" => $message,
            "wr_slug" => $slug
        );
        $response = $this->model->updateWRStatus($payload);
        if ($response) {
            return ResponseDto::json("Process update was successful!", 200);
        }
        return ResponseDto::json("We are unable to complete this process at the time. Please try again later!", 404);
    }

    // acceptRequest
    function acceptWR(int $user_id, string $slug, string $amount): string
    {
        $response = $this->model->updateAmountUserId($user_id, $amount);

        if ($response) {
            $payload = array(
                "wr_status" => 1,
                "wr_message" => "",
                "wr_slug" => $slug
            );
            $res = $this->model->updateWRStatusBySlug($payload);
            if ($res) {
                return ResponseDto::json("Process update was successful!", 200);
            }
        }
        return ResponseDto::json("We are unable to complete this process at the time. Please try again later!", 404);
    }
}
