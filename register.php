<!DOCTYPE html>
<html lang= "en">

<head>

    <meta charset = "utf-8">
	<title>PasarKita - Registration</title>
	<link rel = "icon" type = "image/x-icon" href = "image/PasarKita_Logo.jpg">
	<link rel = "stylesheet" href = "CSS/styles.css">
    
    <style>
    .error-msg
{
    color: red;
    font-size: 13px;
    margin-left: 10px;
}

 .input-error /*JS error style */
{
    border: 2px solid red;
}
    
    .register-container 
    {
       
    text-align: center;
	background: white;
	padding: 30px;
	border-radius: 10px;
	box-shadow: 0 2px 10px rgba(0,0,0,0.1);
	
    }

    .form-group 
    {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin: 0 auto 15px;
        max-width: 450px; /* constrains the row inside the centered white box */
    }

.form-group label 
    {
        width: 150px;               
        text-align: left;           
        margin-right: 15px;         
        /* font-weight: bold; */
    }

.form-input 
    {
        width: 250px;               
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .form-link 
    {
        display: block;
        margin-top: 10px;
        color: #0066cc;
        text-decoration: none;
        font-size: 14px;
    }

    .form-link:hover 
    {
        text-decoration: underline;
        color: orange;
    }

    </style>
</head>
<body>

<img src = "image/PasarKita_Logo.jpg" alt = "PasarKita" class = "website-logo" style="width:150px; display:block; margin:0 auto 15px;">
<h2>PasarKita - Register</h2>

<div class="register-container">


<?php

$usernameError = $passwordError = $confirmError = "";
$username = "";
$securityError = "";
$securityAnswer = "";

session_start(); 

// Path to user storage file
$userFile = "users.json";

// Load existing users
$users = [];
if (file_exists($userFile)) 
    {
        $users = json_decode(file_get_contents($userFile), true);
    }

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirm_password"]);
    $securityAnswer = strtoupper(trim($_POST["security_answer"])); // Normalize answer to uppercase

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirm_password"]);

if (strlen($username) < 5) 
    {
        $usernameError = "Min 5 characters";
    }

    if (isset($users[$username])) 
    {
        $usernameError = "Username already exists";
    }

    if (strlen($password) < 8 || !preg_match('/^[A-Z]/', $password)) 
    {
        $passwordError = "Min 8 chars, start with uppercase";
    }

    if ($password !== $confirmPassword) 
    {
        $confirmError = "Passwords do not match";
    }

    if ($securityAnswer === "") 
    {
        $securityError = "Required";
    }


    // Save ONLY if no PHP errors
    if ($usernameError === "" && $passwordError === "" && $confirmError === "" && $securityError === "") 
    {
        $users[$username] = [
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "security_answer" => $securityAnswer
        ];

        // $users[$username] = password_hash($password, PASSWORD_DEFAULT);
        file_put_contents($userFile, json_encode($users, JSON_PRETTY_PRINT));
        // echo "<p style='color:green;'>Registration successful! You can now <a href='login.php'>login</a>.</p>";

        echo "<p style='color:green;'>
        You have registered successfully.<br>
        Redirecting to login page in 3 seconds...
    	</p>";

		echo "<script>
        setTimeout(function() 
		{
        	window.location.href = 'login.php';
        }, 3000);
        </script>";

		// Clear form values
		$username = "";

    }
}

?>

<form action="register.php" method="POST" novalidate>

    <div class="form-group">
    <label for="username">Username:</label>
    <input id="username" type="text" name="username" required placeholder="Enter your username..." class="form-input"
           value="<?= htmlspecialchars($username ?? '') ?>">
    <span class="error-msg" id="username-error">
        <?= $usernameError ?? '' ?>
    </span>
</div>

<div class="form-group">
    <label for="password">Password:</label>
    <input id="password" type="password" name="password" required placeholder="Enter your password..." class="form-input">
    <span class="error-msg" id="password-error">
        <?= $passwordError ?? '' ?>
    </span>
</div>

<div class="form-group">
    <label for="confirm_password">Re-enter Password:</label>
    <input id="confirm_password" type="password" name="confirm_password" required placeholder="Re-enter your password..." class="form-input">
    <span class="error-msg" id="confirm-error">
        <?= $confirmError ?? '' ?>
    </span>
</div>

<div class="form-group">
    <label for="security_answer">Favourite Colour:</label>
    <input id="security_answer" type="text" name="security_answer" required placeholder="Enter your favourite colour..." class="form-input"
           value="<?= htmlspecialchars($securityAnswer ?? '') ?>">
    <span id="security-error" class="error-msg"><?= $securityError ?? '' ?></span>
</div>


    <button type="submit" style="padding: 10px 20px; background: yellow; color: black; border: none; border-radius: 5px; cursor: pointer;">Register</button>
    <a href="login.php" class="form-link">Back to Login</a>
</form>

<script>
const form = document.querySelector("form");

const username = document.getElementById("username");
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirm_password");
const securityAnswer = document.querySelector('input[name="security_answer"]');

const usernameError = document.getElementById("username-error");
const passwordError = document.getElementById("password-error");
const confirmError = document.getElementById("confirm-error");
const securityError = document.getElementById("security-error");

form.addEventListener("submit", function (e) 
{

    e.preventDefault(); // ALWAYS stop first

    let hasError = false;

    // Only clear JS-generated errors
    if (usernameError.dataset.js === "true") usernameError.textContent = "";
    if (passwordError.dataset.js === "true") passwordError.textContent = "";
    if (confirmError.dataset.js === "true") confirmError.textContent = "";
    if (securityError.dataset.js === "true") securityError.textContent = "";

    if 
    (
        username.value.trim() === "" &&
        password.value.trim() === "" &&
        confirmPassword.value.trim() === "" &&
        securityAnswer.value.trim() === ""
    ) 
    {
        usernameError.textContent = "Required";
        passwordError.textContent = "Required";
        confirmError.textContent = "Required";
        securityError.textContent = "Required";

        usernameError.dataset.js =
        passwordError.dataset.js =
        confirmError.dataset.js =
        securityError.dataset.js = "true";

        username.classList.add("input-error");
        password.classList.add("input-error");
        confirmPassword.classList.add("input-error");
        securityAnswer.classList.add("input-error");

        return; // STOP submit
    } // all empty

    // Username empty
    if (username.value.trim() === "") 
    {
        usernameError.textContent = "Required";
        usernameError.dataset.js = "true";
        username.classList.add("input-error");
        hasError = true;
    }

    // Password empty
    if (password.value.trim() === "") 
    {
        passwordError.textContent = "Required";
        passwordError.dataset.js = "true";
        password.classList.add("input-error");
        hasError = true;
    }

    // Confirm empty
    if (confirmPassword.value.trim() === "") 
    {
        confirmError.textContent = "Required";
        confirmError.dataset.js = "true";
        confirmPassword.classList.add("input-error");
        hasError = true;
    }

    // Security answer empty
    if (securityAnswer.value.trim() === "")
    {
        securityError.textContent = "Required";
        securityError.dataset.js = "true";
        securityAnswer.classList.add("input-error");
        hasError = true;
    }

    // submit if no errors
    if (!hasError) 
    {
        form.submit();
    }
});
</script>

</div>
</body>
</html>
