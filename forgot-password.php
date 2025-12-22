<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<title>PasarKita - Forgot Password</title>
<link rel="icon" href="image/PasarKita_Logo.jpg">
<link rel = "stylesheet" href = "CSS/styles.css">

<style>
.reset-container
{
    text-align:center;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);

}

.form-group
{ 
	margin-bottom:15px;
	align-items:center;
}

.form-group label
{
	width:150px;
	text-align:left;
	margin-right:15px;
	font-weight:bold;
}

.form-input
{
	width:250px;
	padding:8px;
	border:1px solid #ccc;
	border-radius:6px;
}

.error-msg
{
    color:red;
    font-size:13px;
	margin-left:10px;
}

.input-error
{
    border:2px solid red;
}

.submit-btn
{
    padding:10px 20px;
    background:yellow;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

.back-link
{
    display:block;
    margin-top:15px;
    color:#0066cc;
    text-decoration:none;
}

</style>
</head>
<body>

<img src = "image/PasarKita_Logo.jpg" alt = "PasarKita" class = "website-logo" style="width:150px; display:block; margin:0 auto 15px;">
<h2>Forgot Password</h2>

<div class="reset-container">

<?php

$usernameError = $passwordError = $confirmError = "";
$username = "";
$securityError = "";
$securityAnswer = "";



$userFile = "users.json";
$users = file_exists($userFile)
    ? json_decode(file_get_contents($userFile), true)
    : []; // Load existing users

if ($_SERVER["REQUEST_METHOD"] === "POST") 
{

    $username = trim($_POST["username"]);
    $password = trim($_POST["new_password"]);
    $confirmPassword  = trim($_POST["confirm_password"]);
    $securityAnswer = strtoupper(trim($_POST["security_answer"])); // Normalize answer to uppercase

    // Username check
    if (!isset($users[$username])) 
	{
        $usernameError = "User not found";
    } else 
    {
        // Security answer check
        if ($users[$username]['security_answer'] !== $securityAnswer)
		{
            $securityError = "Incorrect answer";
        } 

    // Password format
    if (strlen($password) < 8 or !preg_match('/^[A-Z]/', $password)) 
	{
        $passwordError = "Min 8 chars, start with uppercase";
    }

    // Match check
    if ($password !== $confirmPassword) 
	{
        $confirmError = "Passwords do not match";
    }

    // Update password
    if ($usernameError === "" && $passwordError === "" && $confirmError === ""&& $securityError === "") 
	{
        $users[$username]["password"] = password_hash($password, PASSWORD_DEFAULT);
        file_put_contents($userFile, json_encode($users, JSON_PRETTY_PRINT));
        
		echo "<p style='color:green;'>
        Password updated successfully.<br>
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
}}
?>

<form method="POST" novalidate>

    <div class="form-group">
    <label for="username">Username</label>
    <input id="username" type="text" name="username" class="form-input"
           value="<?= htmlspecialchars($username ?? '') ?>">
    <span class="error-msg" id="username-error">
        <?= $usernameError ?? '' ?>
    </span>
</div> 

<div class="form-group">
    <label>Favourite Colour</label>
    <input id="security_answer" type="text" name="security_answer" class="form-input">
    <span id="security-error" class="error-msg"><?= $securityError ?? '' ?></span>
</div>


<div class="form-group">
    <label>Password</label>
    <input id="password" type="password" name="new_password" class="form-input">
    <span class="error-msg" id="password-error">
        <?= $passwordError ?? '' ?>
    </span>
</div>

<div class="form-group">
    <label>Re-enter Password</label>
    <input id="confirm_password" type="password" name="confirm_password" class="form-input">
    <span class="error-msg" id="confirm-error">
        <?= $confirmError ?? '' ?>
    </span>
</div>

<button type="submit" class="submit-btn">Reset Password</button>
<a href="login.php" class="back-link">Back to Login</a>

</form>
</div>

<script>
const form = document.querySelector("form");

const username = document.getElementById("username");
const password = document.getElementById("password");
const confirmPassword  = document.getElementById("confirm_password");
const securityAnswer = document.querySelector('input[name="security_answer"]');

const usernameError = document.getElementById("username-error");
const passwordError = document.getElementById("password-error");
const confirmError  = document.getElementById("confirm-error");
const securityError = document.getElementById("security-error");

form.addEventListener("submit", function (e) 
{

    e.preventDefault(); // ALWAYS stop first (same as register.php)
    let hasError = false;

	// Clear previous JS errors

	if (usernameError.dataset.js === "true") usernameError.textContent = "";
    if (passwordError.dataset.js === "true") passwordError.textContent = "";
    if (confirmError.dataset.js === "true") confirmError.textContent = "";
    if (securityError.dataset.js === "true") securityError.textContent = "";

    // 1️⃣ ALL FIELDS EMPTY (same logic as register.php)
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
        confirmError.textContent  = "Required";
        securityError.textContent = "Required";

        usernameError.dataset.js =
        passwordError.dataset.js =
        securityError.dataset.js =
        confirmError.dataset.js = "true"; 

        username.classList.add("input-error");
        password.classList.add("input-error");
        securityAnswer.classList.add("input-error");
        confirmPassword.classList.add("input-error");

        return; // STOP submit
    }

    // 2️⃣ Individual empty checks
    if (username.value.trim() === "") 
	{
        usernameError.textContent = "Required";
        usernameError.dataset.js = "true";
        username.classList.add("input-error");
        hasError = true;
    }

    if (password.value.trim() === "") 
	{
        passwordError.textContent = "Required";
        passwordError.dataset.js = "true";
        password.classList.add("input-error");
        hasError = true;
    }

    if (confirmPassword.value.trim() === "") 
	{
        confirmError.textContent = "Required";
        confirmError.dataset.js = "true";
        confirmPassword.classList.add("input-error");
        hasError = true;
    }
    if (securityAnswer.value.trim() === "") 
    {
        securityError.textContent = "Required";
        securityError.dataset.js = "true";
        securityAnswer.classList.add("input-error");
        hasError = true;
    }

    // 3️⃣ Submit to PHP only if JS is clean
    if (!hasError) 
	{
        form.submit();
    }
});
</script>


</body>
</html>
