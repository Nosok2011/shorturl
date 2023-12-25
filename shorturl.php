<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ShortURL</title>
    </head>
    <body>
        <h1>Сокращатель URL</h1>
        <?php
        $db = mysqli_connect("localhost", "root", "", "shorturl", 3306);
        if ($db) {
        ?>
        <p>Введите URL, и он будет сокращён</p>
        <?php
        if (isset($_GET["url"])) {
            $url = $_GET["url"];
            $find = mysqli_query($db, "SELECT orig FROM `urls` WHERE short=\"$url\"");
            $isFound = mysqli_num_rows($find);
            if ($isFound) {
                $to = mysqli_fetch_assoc($find)["orig"];
                header("Location: " . $to);
            } else {
                echo("Такой URL не найден в базе данных");
            }
        }
        if (isset($_POST["submit"])) {
            $orig = $_POST["orig_url"];
            $check = mysqli_query($db, "SELECT short FROM `urls` WHERE orig=\"$orig\"");
            $isShortened = mysqli_num_rows($check);
            if ($isShortened) {
                if (empty($_SERVER["HTTPS"])) {
                    $shortenedURL = "http";
                } else {
                    $shortenedURL = "https";
                }
                $code = mysqli_fetch_assoc($check)["short"];
                $shortenedURL = $shortenedURL . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . "?url=" . $code;
                ?>
                <p>URL уже сокращён. Вот он: <a href="<?= $shortenedURL ?>"><?= $shortenedURL ?></a></p>
                <?php
            }
            $chars = "0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz";
            $charsShuffled = str_shuffle($chars);
            $code = substr($charsShuffled, 0, 10);
            if (!$isShortened) {
                $shortenSuccess = mysqli_query($db, "INSERT INTO `urls` (orig, short) VALUES (\"$orig\", \"$code\")");
                if ($shortenSuccess) {
                    if (empty($_SERVER["HTTPS"])) {
                        $shortenedURL = "http";
                    } else {
                        $shortenedURL = "https";
                    }
                    $shortenedURL = $shortenedURL . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . "?url=" . $code;
                ?>
                    <p>Ваш URL успешно сокращён! Вот он: <a href="<?= $shortenedURL ?>"><?= $shortenedURL ?></a></p>
                    <?php
                } else {
                    ?>
                    <p>Возникла ошибка при сокращении вашего URL. Попробуйте ещё раз позднее.</p>
                    <?php
                }
            }
        }
        ?>
        <form method="POST">
            <input maxlength="2048" type="text" name="orig_url" required>
            <input type="submit" value="Сократить" name="submit">
        </form>
        <?php
        } else {
            echo("Ошибка подключения к базе данных");
        }
        ?>
    </body>
</html>