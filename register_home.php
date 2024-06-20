<html>
<head>
    <title>Register home</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

<style> 
body {
            color:white;

            margin: 0;
            padding: 0;
            background-image: url('images/background_register.jpg'); 
            background-size: cover;
            background-repeat: no-repeat;
        }

h2 { 
    font-size:50px;
    
}

p {
   font-size:30px;
} 


.content {
     width: 40%; 
     text-align: center;
     padding: 20px;
}

.button {
       display: inline-block;
       padding: 10px 20px;
       margin: 10px;
       color: #fff;
       text-decoration: none;
       border-color: black;
       border-radius: 5px;
       cursor: pointer;
       font-size: 16px;
}

.button.signup {
       background-color: white;
       color:black;
}

.button.login {
    background-color: white;
    color:black;

}

footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 20px 0;
}
.logo img {
    width: 100px;
    margin-left: 20px;
}

nav ul {
    list-style: none;
}

nav ul li {
    display: inline-block;
    margin-right: 20px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
}

nav ul ul {
    display: none;
    position: absolute;
    background-color: #333;
    padding: 10px;
}

nav ul ul li {
    display: block;
}

nav ul li:hover > ul {
    display: block;
}
header {
    background-color: #333;
    color: #fff;
    padding: 20px 0;
}
.backg{
    background-color: #f2f2f2;
    color:#000;
    width: 60%;
    border-radius:20px;
    margin: 20px;
}
</style>
</head>
<body>
<header>
        <div class="container">
            <div class="logo">
                <img src="images/logo.png" alt="NewsWave Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="categories.php">Explore News</a>

                    <li><a href="register_home.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>

                </ul>
            </nav>
        </div>
    </header>
  <br>
<div class="backg">
        <h2 class="content">Welcome to NewsWave</h2>
        <p class="content">Please register here to create your account as an Contributer</p> <br>
</div>
<div class="content" class="button-container">
        
        <a href="adminlogin.php" class="button login">Login as Admin</a>
        <a href="contributer_register.php" class="button login">Contributer</a>
        <a href="register.php" class="button login">Employee</a>


   
</div>
</body>
</html>