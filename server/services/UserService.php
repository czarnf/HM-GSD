<?php

namespace app\services;

use app\config\MysqlDBH;
use app\config\PasswordConfig;
use app\dto\ResponseDto;
use app\model\User;
use app\services\impl\UserServiceImpl;
use app\utils\Helper as UtilsHelper;
use app\utils\PasswordEncoder;
use Helper;

class UserService implements UserServiceImpl
{

    private $model;
    private $helper;
    private $mailerService;

    function __construct()
    {
        $mysqlConnector = new MysqlDBH();
        $this->model = new User($mysqlConnector);
        $this->helper = new UtilsHelper();
        $this->mailerService = new MailerService();
    }

    function setUser(array $data): string
    {
        $response = $this->model->createUser($data);
        if (is_bool($response)) {
            if ($response) {
                // send in email
                return ResponseDto::json(" New user account registration was successful. <br/> We have sent you an email!", 201);
            }
            return ResponseDto::json("An error was encountered while trying to register user details!", 500);
        }
        return ResponseDto::json("The user detail already exist in our system!", 422);
    }

    function getUsers(): string
    {
        $response = $this->model->fetchUsers();
        return ResponseDto::json($response);
    }


    function getUsersByRole(string $role = "investor"): string
    {
        $response = $this->model->fetchUserByRole($role);
        return ResponseDto::json($response, 200);
    }

    function getNewestUsers(): string
    {
        $response = $this->model->fetchRecentUsers();
        return ResponseDto::json($response, 200);
    }


    function userAuthentication(string $username, string $password): string
    {
        $response = $this->model->fetchUserByUsername($username);
        if (count($response) > 4) {
            $passwordHash = $response["user_password"];
            if (PasswordEncoder::decodePassword($password, $passwordHash)) {
                if ($response["user_status"] != 4) {
                    $this->model->editUserLogInStatus($response["user_slug"], 1);
                    $response["user_password"] = "_____";
                    return ResponseDto::json("Login was successful", 200, $response);
                }
                return ResponseDto::json("This account has been deleted. Please contact the admin of this platform for further enquires. Thank you!");
            }
            return ResponseDto::json("Your password is not recognized!");
        }
        return ResponseDto::json("Your username is not recognized!");
    }


    function userForgotPasswordAuth(string $username): string
    {
        $response = $this->model->fetchUserByUsername($username);
        if (count($response) > 4) {
            $otp = $this->helper->getEmailOTP();
            $response["user_password"] = "_______";
            if (strlen($otp) >= 5) {
                $response["otp"] = $otp;
                $this->mailerService->sendOTPNotification($otp, $response["user_email"], $response["user_username"]);
                return ResponseDto::json("Password recovery email sent successfully. Check your email for the OTP.", 200, $response);
            }
            return ResponseDto::json("Oooops, We had an issue sending OTP to your email ( " . $response["user_email"] . " ). Please try again after 24 hours or contact us. Thanks!", 412);
        }
        return ResponseDto::json("Your username is not recognized!");
    }


    function userLogout(string $slug): string
    {
        $response = $this->model->fetchUserBySlug($slug);
        if (count($response) > 4) {
            if ($this->model->editUserLogInStatus($response["user_slug"], 0)) {
                return ResponseDto::json("Logged out successfully", 200);
            }
        }
        return ResponseDto::json("This credential is not recognized!");
    }

    function editUserPassword(array $payload): string
    {
        $response = $this->model->updateUserPassword($payload);
        if ($response) {
            return ResponseDto::json("User password was updated successfully", 200);
        }
        return ResponseDto::json("We are unable to update your password. Please try again!");
    }


    function getTotalUsers(): int
    {
        $response = $this->model->fetchUserByRole("investor");
        return count($response);
    }


    function editSetting(array $payload): string
    {
        $response = $this->model->updateSetting($payload);
        if ($response) {
            return ResponseDto::json("App Contact Setting was updated successfully", 200);
        }
        return ResponseDto::json("We are unable to update contact setting at this point. Please try again!");
    }

    function getSettings(): string
    {
        $response = $this->model->fetchSettings();
        return ResponseDto::json($response, 200);
    }
}
