<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASS', '');
define('DB_NAME', 'contact_form');
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed");
}
$firstName = $lastName = $phoneNumber = $email = $city = $state = $country = $message = '';
$errors = [];

if (isset($_POST['submit'])) {
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $phoneNumber = htmlspecialchars($_POST['phoneNumber']);
    $email = htmlspecialchars($_POST['email']);
    $city = htmlspecialchars($_POST['city']);
    $state = htmlspecialchars($_POST['state']);
    $zipCode = htmlspecialchars($_POST['zipCode']);
    $country = htmlspecialchars($_POST['country']);
    $message = htmlspecialchars($_POST['message']);


    if (empty($firstName)) {
        $errors['firstName'] = 'Please enter your first name';
    }

    if (empty($lastName)) {
        $errors['lastName'] = 'Please enter your last name';
    }

    function validatePhoneNumber($phoneNumber)
    {
        $regex = '/^\d{10,13}$|^\+(?:\d{1,4}[\s.-]?)?\(?\d{1,5}\)?[\s.-]?\d{1,8}[\s.-]?\d{10,13}$/';
        return preg_match($regex, $phoneNumber);
    }

    if (empty($phoneNumber)) {
        $errors['phoneNumber'] = 'Please enter your phone number';
    } elseif (!validatePhoneNumber($phoneNumber)) {
        $errors['phoneNumber'] = 'Please enter a valid phone number';
    }

    if (empty($email)) {
        $errors['email'] = 'Please enter your email address';
    }

    if (empty($city)) {
        $errors['city'] = 'Please enter your city';
    }

    if (empty($state)) {
        $errors['state'] = 'Please enter your state';
    }


    function validateZipCode($zipCode)
    {
        if (preg_match('#[0-9]{5}#', $zipCode))
            return true;
        else
            return false;
    }

    if (empty($zipCode)) {
        $errors['zipCode'] = 'Please enter your zip code';
    }

    if (empty($country)) {
        $errors['country'] = 'Please enter your country';
    }

    if (empty($message)) {
        $errors['message'] = 'Please feel free to write us here!';
    }




    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sqlquery = "INSERT INTO contact_forms (`firstName`, `lastName`, `phoneNumber`, `Email`, `City`, `State`, `zipCode`, `Country`, `Message`) VALUES ('$firstName', '$lastName', '$phoneNumber', '$email', '$city', '$state', '$zipCode', '$country', '$message')";

    if ($conn->query($sqlquery) === TRUE) {
        $successMessage = "";
    } else {
        echo "Error: " . $sqlquery . "<br>" . $conn->error;
    }

    $sql = "SELECT * FROM Contacts ORDER BY id DESC LIMIT 1";
    $latestRecord = $conn->query($sql)->fetch_assoc();

    $record = "Name: " . $latestRecord['firstName'] . " " . $latestRecord['lastName'] . "\n";
    $record .= "Phone: " . $latestRecord['phoneNumber'] . "\n";
    $record .= "Email: " . $latestRecord['Email'] . "\n";
    $record .= "City: " . $latestRecord['City'] . "\n";
    $record .= "State: " . $latestRecord['State'] . "\n";
    $record .= "zipCode: " . $latestRecord['zipCode'] . "\n";
    $record .= "Country: " . $latestRecord['Country'] . "\n";
    $record .= "Message: " . $latestRecord['Message'] . "\n\n";

    $toEmail = "info@xyz.com";
    $subject = "Latest Contact Form Submission";
    $headers = "From: webmaster@xyz.com";

    if (mail($toEmail, $subject, $record, $headers)) {
        $successMessage .= "Form submitted successfully!";
    } else {
        echo "Email could not be sent.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact us</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js">

    </script>

    <!--CSS Styles for Home Page-->
    <link rel="stylesheet" href="include/styles.css">






    <!--//script for automatic language detection & translaiton according to the local browser language goes here//-->

    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
        function detectAndTranslateUserLanguage() {
            const userLanguage = window.navigator.language || window.navigator.userLanguage;
            if (userLanguage.startsWith('ur')) {
                googleTranslateElementInit();
            }
        }
        window.onload = detectAndTranslateUserLanguage;
    </script>
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>


    <style>
        body {
            padding-top: 3rem;
            padding-bottom: 0;
            color: #000;
        }

        .custom-input {
            /* width: 100%; */
        }
    </style>



</head>

<body>


    <div class="translate" id="google_translate_element"></div>
    <header id="header" class="d-flex flex-column justify-content-center" lang="en">
        <div class="container">
            <div class="row mt-5">
                <div class="col-md-8 mx-auto mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h1>Contact Us</h1>
                            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="firstName" class="form-label">First Name</label>
                                        <input type="text" class="form-control custom-input <?php echo isset($errors['firstName']) ? 'is-invalid' : '' ?>" placeholder="Enter your first name" value="<?php echo $firstName ?>">
                                        <?php if (isset($errors['firstName'])) { ?>
                                            <div class="text-danger"><?php echo $errors['firstName'] ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="col">
                                        <label for="lastName" class="form-label">Last Name</label>
                                        <input type="text" class="form-control custom-input <?php echo isset($errors['lastName']) ? 'is-invalid' : '' ?>" placeholder="Enter your last name" value="<?php echo $lastName ?>">
                                        <?php if (isset($errors['lastName'])) { ?>
                                            <div class="text-danger"><?php echo $errors['lastName'] ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="phoneNumber" class="form-label">Phone Number</label>
                                        <input type="tel" id="phoneNumber" name="phoneNumber" class="form-control custom-input <?php echo isset($errors['phoneNumber']) ? 'is-invalid' : '' ?>" placeholder="Enter your phone number" value="<?php echo $phoneNumber ?>">
                                        <?php if (isset($errors['phoneNumber'])) { ?>
                                            <div class="text-danger"><?php echo $errors['phoneNumber'] ?></div>
                                        <?php } ?>
                                    </div>

                                    <div class="col">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email" class="form-control custom-input <?php echo isset($errors['email']) ? 'is-invalid' : '' ?>" placeholder="Enter your email" value="<?php echo $email ?>">
                                        <?php if (isset($errors['email'])) { ?>
                                            <div class="text-danger"><?php echo $errors['email'] ?></div>
                                        <?php } ?>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" id="city" name="city" class="col-auto form-control custom-input <?php echo isset($errors['city']) ? 'is-invalid' : '' ?>" placeholder="Enter your city" value="<?php echo $city ?>">
                                            <?php if (isset($errors['city'])) { ?>
                                                <div class="text-danger"><?php echo $errors['city'] ?></div>
                                            <?php } ?>
                                        </div>
                                        <div class="col mb-3">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" id="state" name="state" class="col-auto form-control custom-input <?php echo isset($errors['state']) ? 'is-invalid' : '' ?>" placeholder="Enter your state" value="<?php echo $state ?>">
                                            <?php if (isset($errors['state'])) { ?>
                                                <div class="text-danger"><?php echo $errors['state'] ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="zipCode" class="form-label">Zip Code</label>
                                            <input type="text" id="zipCode" name="zipCode" class="col-3 form-control custom-input <?php echo isset($errors['zipCode']) ? 'is-invalid' : '' ?>" placeholder="Enter your zip code" value="<?php echo $state ?>">
                                            <?php if (isset($errors['zip code'])) { ?>
                                                <div class="text-danger"><?php echo $errors['zipCode'] ?></div>
                                            <?php } ?>
                                        </div>
                                        <div class="col">
                                            <label for="country" class="form-label">Country</label>
                                            <input type="text" id="country" name="country" class="col-3 form-control custom-input <?php echo isset($errors['country']) ? 'is-invalid' : '' ?>" placeholder="Enter your country" value="<?php echo $country ?>">
                                            <?php if (isset($errors['country'])) { ?>
                                                <div class="text-danger"><?php echo $errors['country'] ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label for="message" class="form-label">Write us here</label>
                                        <textarea id="message" name="message" class="form-control custom-input <?php echo isset($errors['message']) ? 'is-invalid' : '' ?>" placeholder="Please feel free to write us here"><?php echo $message ?></textarea>
                                        <?php if (isset($errors['message'])) { ?>
                                            <div class="text-danger"><?php echo $errors['message'] ?></div>
                                        <?php } ?>
                                    </div>


                                    <div class="mb-3">
                                        <label for="subscription" class="form-label">Do you want to subscribe our newsletter?</label> <br>

                                        <?php if (isset($errors['subscription'])) { ?>
                                            <div class="text-danger"><?php echo $errors['subscription'] ?></div>

                                        <?php } ?>
                                        <input type="radio" name="myrdo" value="Y" /> Yes
                                        <input type="radio" name="myrdo" value="N" /> No
                                        <br>
                                    </div>

                                    <div>
                                        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                                        <input type="reset" name="reset" value="Reset" class="btn btn-primary">

                                        <?php if (isset($successMessage)) { ?>
                                            <div class="alert alert-success" role="alert">
                                                <?php echo $successMessage; ?>
                                            </div>
                                        <?php } ?>
                                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>












        </body>

</html>