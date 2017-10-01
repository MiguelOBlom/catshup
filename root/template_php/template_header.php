<div id="Header">
    <div id="HeaderWrapper">
        <h1>De header</h1>
        <?php
            if (isset($_SESSION["username"])){
                echo "Ingelogd als : <a href='/catshup/root/public/user/profile.php?u=".$_SESSION['username']."'>".$_SESSION['username']."</a>";
                echo '<a href="/catshup/root/public/user/logprotocol/logout.php">logout</a>';
            } else {
                echo '<a href="/catshup/root/public/user/logprotocol/login.php">login</a>
                      <a href="/catshup/root/public/user/logprotocol/register.php">register</a>';

            }
        ?>


    </div>
</div>