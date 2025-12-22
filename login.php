<?php
session_start(); // start session

// If already logged in, redirect to buying_page.php
if (isset($_SESSION["username"])) 
{
    header("Location: buying_page.php");
    exit();
}

// Load registered users from file (look for users.json in current folder)
$userFile = "users.json";
$registeredUsers = [];
if (file_exists($userFile)) 
	{
    	$registeredUsers = json_decode(file_get_contents($userFile), true);
	}

$phpError = ""; // To hold PHP error messages
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    // Retrieve and sanitize input
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

	// Check if user exists
    if (isset($registeredUsers[$username])) 
    {
        // Verify password against stored hash
        $storedHash = is_array($registeredUsers[$username]) && isset($registeredUsers[$username]['password']) ? $registeredUsers[$username]['password'] : $registeredUsers[$username];
        if (password_verify($password, $storedHash)) 
        {
             $_SESSION["username"] = $username; // Store username in session
			 $_SESSION["last_activity"] = time(); // Store last time user was active
	
			header("Location: buying_page.php"); // Redirect to protected page
			exit;
        } 
        else 
        {
            $phpError = "Invalid username or password."; 
			//echo "<p style='color:red;'>Invalid password.</p>";
        }
    } 
        else 
        {
            $phpError = "Invalid username or password.";
		    //echo "<p style='color:red;'>User not found.</p>";
        }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PasarKita - Login</title>
    <link rel="icon" type="image/x-icon" href="image/PasarKita_Logo.jpg">
    <link rel="stylesheet" href="CSS/styles.css">

    <style>
        .login-container {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            margin: 20px auto;
        }

        .form-link {
            display: block;
            margin-top: 10px;
            color: #0066cc;
            text-decoration: none;
            font-size: 14px;
        }

        .form-link:hover {
            text-decoration: underline;
            color: orange;
        }

        .form-group {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin: 0 auto 15px;
            max-width: 450px;
        }

        .form-group label {
            width: 150px;
            text-align: left;
            margin-right: 15px;
        }

        .form-input {
            padding: 8px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        #client-error {
            color: red;
            margin-top: 12px;
        }
    </style>
</head>

<body>

    <img src="image/PasarKita_Logo.jpg" alt="PasarKita" class="website-logo" style="width:150px; display:block; margin:0 auto 15px;">
    <h2 style="text-align:center">PasarKita - Login</h2>

    <div class="login-container">

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" novalidate>

            <div class="form-group">
                <label for="username">Username:</label>
                <input id="username" type="text" name="username" placeholder="Enter your username..." required class="form-input" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input id="password" type="password" name="password" placeholder="Enter your password..." required class="form-input">
            </div>

            <button type="submit" id="login-btn" style="padding: 10px 20px; background: yellow; color: black; border: none; border-radius: 5px; cursor: pointer;"> Login</button>
            <a href="forgot-password.php" class="form-link">Forgot password?</a>
            <a href="register.php" class="form-link">Register</a>

            <p id="client-error"><?php if (!empty($phpError)) echo htmlspecialchars($phpError); ?></p>

        </form>

    </div>

    <script>
        // Client-side validation
        const form = document.querySelector("form");
        const usernameEl = document.getElementById("username");
        const passwordEl = document.getElementById("password");
        const errorMsg = document.getElementById("client-error");

        form.addEventListener("submit", function (e) {
            // Clear old error message
            // errorMsg.textContent = "";

            if (usernameEl.value.trim() === "" && passwordEl.value.trim() === "") {
                errorMsg.textContent = "Please enter both username and password.";
                e.preventDefault();
                return;
            }

            if (usernameEl.value.trim() === "") {
                errorMsg.textContent = "Username cannot be empty.";
                e.preventDefault();
                return;
            }

            if (passwordEl.value.trim() === "") {
                errorMsg.textContent = "Password cannot be empty.";
                e.preventDefault();
                return;
            }
        });
    </script>

</body>

</html>