<?php

function clean($string) {
    return htmlentities($string);
}

function redirect($location) {
     header("location: {$location}");
}

function set_message($msg="") {
    if (!empty($msg)) {
        $_SESSION['Message'] = $msg;
    } else {
        $msg = "";
    }
}

// Display Message Function

function display_message() {
    if (isset($_SESSION['Message']))
    {
        echo $_SESSION['Message'];
        unset($_SESSION['Message']);
    }

}

// Generate Token

function Token_Generator()
{
    $token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    return $token;
}

function send_email($email, $sub, $msg, $header)
{
    return mail($email, $sub, $msg, $header);
}

//******** User Validation Function *******//

function user_validation()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $username  = clean($_POST['username']);
        $firstName = clean($_POST['first-name']);
        $lastName  = clean($_POST['last-name']);
        $email     = clean($_POST['email']);
        $pass      = clean($_POST['pass']);
        $cpass     = clean($_POST['cpass']);

        $Errors = [];
        $Max = 20;
        $Min = 03;

        if (strlen($firstName) < $Min)
        {
            $Errors[] = " First Name cannot be less than {$Min} Characters";
        }
        if (strlen($firstName) > $Max)
        {
            $Errors[] = " First Name cannot be more than {$Min} Characters";
        }

        if (strlen($lastName) < $Min)
        {
            $Errors[] = " Last Name cannot be less than {$Min} Characters";
        }

        if (strlen($lastName) > $Max)
        {
            $Errors[] = " Last Name cannot be more than {$Min} Characters";
        }

        if (! preg_match("/^[a-zA,0-9]*$/", $username))
        {
            $Errors[] = " Username cannot be accept Characters";
        }

        if (Email_exist($email))
        {
            $Errors[] = " Email Already exist";
        }

        if (User_exist($username))
        {
            $Errors[] = " Username Already exist";
        }

        if ($pass != $cpass)
        {
            $Errors[] = "password not matched";
        }


        if (! empty($Errors))
        {
            foreach ($Errors as $error) {
                echo Error_validation($error);
            }
        }
        else
        {
            if (user_registration($firstName, $lastName, $username, $email, $pass))
            {
                set_message('<p class="bg-success text-center lead">You have successfully registered Please check your activation link</p>');
                redirect("index.php");
            } else {
                set_message('<p class="bg-danger text-center lead">Your Account Not Registered Please try again</p>');
                redirect("index.php");

            }
        }


    }
}

function Email_exist($email)
{
    $sql = "SELECT * FROM users WHERE email = '{$email}'";
    $result = Query($sql);
    if (fetch_data($result))
    {
        return true;
    } else {
        return false;
    }

}

// User Exist Function

function User_exist($username)
{
    $sql = "SELECT * FROM users WHERE username = '{$username}'";
    $result = Query($sql);
    if (fetch_data($result))
    {
        return true;
    } else {
        return false;
    }

}

function user_registration($firstName, $lastName, $username, $email, $password)
{
    $firstName = escape($firstName);
    $lastName = escape($lastName);
    $username = escape($username);
    $email = escape($email);
    $password = escape($password);

    if (Email_exist($email))
    {
        return true;
    }
    elseif (User_exist($username))
    {
        return true;
    }
    else
    {
        $password = md5($password);
        $validation_code = md5($username . microtime());
        $sql = "INSERT INTO users (first_name, last_name, username, email, password, validation_code, active) VALUES 
                    ('$firstName', '$lastName', '$username', '$email', '$password', '$validation_code', '0')
                ";
        $result = Query($sql);
        confirm($result);
        $subject = " Active Your Account ";
        $msg = "Please Click the link Below to Active Your Account http://loginAndRegisteration/activate.php?email=$email&code=$validation_code";
        $header = "From No-Reply admin@online.com";
        send_email($email, $subject, $msg, $header);
        return true;
    }

}

//Activation function

function activation()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
         $email = $_GET['email'];
         $code = $_GET['validation_code'];
         $sql = " SELECT * FROM users WHERE email= '$email' AND validation_code = '$code'";
         $result = Query($sql);
         print_r($result);
         confirm($result);
         if (fetch_data($result))
         {
             $sqlquesry = " UPDATE users SET active = '1', validation_code= '0' WHERE email= '$email' AND validation_code= '$code'";
             $result2 = Query($sqlquesry);
             confirm($result2);
             set_message('<p class="bg-success text-center lead"> Your Account Successfully Activated</p>');
             redirect('login.php');
         } else {
             echo '<p class="bg-danger text-center lead"> Your Account Not Activated</p>';
         }
    }
}

// *************** USER VALIDATION FUNCTION  *************** \\

// Errors Function

function Error_validation($Error)
{
    return '<div class="alert alert-danger">'. $Error .'</div>';
}

function login_validation()
{
    $Errors = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $userEmail = clean($_POST['UEmail']);
        $userPass = clean($_POST['Upass']);
        $remember = isset($_POST['remember']);

        if (empty($userEmail))
        {
            $Errors[] = " Please Enter Your Email ";
        }
        if (empty($userPass))
        {
            $Errors[] = " Please Enter Your pass ";
        }
        if (!empty($Errors))
        {
            foreach ($Errors as $error)
            {
             echo Error_validation($error);
            }
        }
        else
        {
           if (user_login($userEmail, $userPass, $remember))
           {
               redirect("admin.php");
           }
           else
           {
               echo Error_validation("Please Enter Correct Email Or Password");
           }
        }

    }
}

// user login function

function user_login($UEmail, $Upass, $remember)
{
    $sql = " SELECT * FROM users WHERE email= '$UEmail' AND active = '1' ";
    $result = Query($sql);

    if ($row = fetch_data($result))
    {
        $db_pass = $row['password'];
        if (md5($Upass) == $db_pass)
        {
            if ($remember == true)
            {
                setcookie('email', $UEmail, time() + 86400);
            }
            $_SESSION['email'] = $UEmail;
            return true;
        }
        else
        {
            return false;
        }
    }

}

function logged_in()
{
    if (isset($_SESSION['email']) || isset($_COOKIE['email']))
    {
        return true;
    }
    else
    {
        return false;
    }

}

////////////////////////// RECOVER FUNCTION //////////////////////////

function recover_password()
{
    if ($_SERVER['REQUEST_METHOD'] == "POST")
    {
       if (isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token'])
       {
           $email = $_POST['UEmail'];
           if (Email_exist($email))
           {
               $code = md5($email . microtime());
               setcookie('temp_code', $code, time()+300);
               $sql = " UPDATE users SET validation_code = '$code' WHERE email = '$email'";
               Query($sql);
               $subject = " Please Reset The Password";
               $message = " Please follow on below link to reset the password http://localhost/loginAndRegisteration/code.php?email='$email'&code='$code'";
               $header = "From: noreply@online.com";

               if (send_email($email, $subject, $message, $header))
               {
                   echo '<div class="alert alert-success"> Please Check Your Email</div>';
               }
               else
               {
                   echo Error_validation(" Email Not Found.....");
               }

           }
           else
           {
              redirect("index.php");
           }
       }
    }

}

/////// VALIDATION CODE ////////////

function  validation_code()
{
   if (isset($_COOKIE['temp_code']))
   {
       if (isset($_GET['email']) && isset($_GET['code']))
       {
           redirect("index.php");
       }
       else if (empty($_GET['email']) && empty($_GET['code']))
       {
           redirect("index.php");
       }
       else
       {
           if (isset($_POST['recover_code']))
           {
               $code = $_POST['recover_code'];
               $email = $_POST['email'];

               $query = "SELECT * FROM users WHERE validation_code= '$code' AND email = '$email'";
               $result = Query($query);
               if (fetch_data($result))
               {
                   setcookie('temp_code', $code, time()+300);
                   redirect("reset.php?email=$email&code=$code");
               }
               else {
                   Error_validation("Query Failed");
               }
           }
       }
   }
   else
   {
       set_message('<div>Your Code Has been Expired ..</div>');
       redirect("recover.php");
   }
}

function reset_password()
{
    if (isset($_COOKIE['temp_code']))
    {
        if (isset($_GET['email']) && isset($_GET['code']))
        {
            if (isset($_SESSION['token']) && $_POST['token'])
            {
                if ($_SESSION['token'] == $_POST['token'])
                {
                   if($_POST['reset_pass'] === $_POST['reset_c_pass'])
                   {
                      $password = md5($_POST['reset_pass']);
                      $query2 = "UPDATE users SET password = '".$password."', validation_code = 0 WHERE email = '".$_GET['email']."'";
                      $result = Query($query2);
                      if ($result)
                      {
                          set_message('<div class="alert alert-success"> Your Password Has Been Updated</div>');
                          redirect("login.php");
                      }
                   }
                   else {
                       set_message('<div class="alert alert-danger"> Your Password Not Matched :)</div>');

                   }
                }

            }

        } else {
            set_message('<div class="alert alert-danger"> Your Code Or Your Email Has Not Matched :)</div>');
        }

    }
    else {
        set_message('<div class="alert alert-danger"> Your Time Period Has Been Expired</div>');

    }

}





?>