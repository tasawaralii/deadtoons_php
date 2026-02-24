<?php
function check_login()
{

    if (!isset($_COOKIE['dt'])) {
        header("Location: login.php");
    }
}

function telegram($note, $name, $photo, $link, $ep = 0, $movie = false)
{

    echo "<br>" . $photo;

    $update = "";

    if (!$movie) {
        $update = "\n \n [$ep Added] ";
    }

    $msj = "🌟||$name|| $update \n \n • Link: \n $link";
    $botToken = '7071121072:AAHiGKQEf2AmGyUStg9B_qAzPIymTfy8TZY';
    $privateChatId = '-1002145183427';
    $telegramApiUrl = 'https://api.telegram.org/bot' . $botToken . '/sendPhoto';

    // Prepare the post fields for the photo and caption
    $postFields = [
        'chat_id' => $privateChatId,
        'photo' => $photo,
        'caption' => $msj,
        'parse_mode' => 'HTML',
    ];

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
    curl_setopt($ch, CURLOPT_URL, $telegramApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

    // Execute cURL request
    $result = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
}



function slugify($string)
{
    // Convert the string to lowercase
    $slug = strtolower($string);

    // Replace non-alphanumeric characters with hyphens
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

    // Remove leading and trailing hyphens
    $slug = trim($slug, '-');

    // Remove consecutive hyphens
    $slug = preg_replace('/-+/', '-', $slug);

    return $slug;
}
