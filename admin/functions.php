<?php


function postcode($id, $pdo) {
        $r = $pdo->query("SELECT * FROM Animes Where anime_id = $id")->fetch();
        
        $date = new DateTime($r['anime_rel_date']);
        $year = $date->format('Y');
        $postcode = '<p>✅';
        
        $postcode .= 'Dowload <b>'.$r['anime_name'].'</b> ('.$year.') Multi Audio <strong>[Hindi-Tamil-Telugu-Kannada-Malayalam-Marathi]</strong>. It was released on <b>'.$r['anime_rel_date'].'</b>. It is based on <b>Animation, Family, Comedy, Action</b>. This '. ($r['type'] == "tv" ? "Series" : "Movie") .' is now available in <strong>Hindi Dubbed at Deadtoons.</strong></p>
      <hr>
      <h3>'. ($r['type'] == "tv" ? "Series" : "Movie") .' Info:</h3>
      <ul>
<li>Full Name: <strong>'.$r['anime_name'].'</strong></li>
<li>Rating: <strong>'.$r['rating'].'</strong></li>
<li>Year: <strong>'.$year.'</strong></li>
<li>Language:&nbsp;<strong>(Hindi-Tamil-Telugu-Kannada-Malayalam-Marathi)</strong></li>
<li>Subtitles: <strong>N/A</strong></li>
<li>Runtime : <strong>'.$r['duration'].' minutes</strong></li>
<li>Release Date: <strong>'.$r['anime_rel_date'].'</strong></li>
<li>Format:&nbsp;<strong>Mkv</strong></li>
</ul>
      <h2>Storyline:</h2>
<hr>
      <div style="font-weight:bold;">'.$r['overview'].'</div><hr>
<p><strong>Deadtoons </strong><em>is The Best Website/Platform For Japanese And Hollywood HD Animes. We Provide Direct Google Drive Download Links For Fast And Secure Downloading. Just Click On the Download Button And Follow the Steps To Download And Watch Movies Online For Free.</em></p>
<hr>
<h3 style="text-align:center;">'.$r['anime_name'].' ('.$year.') - DeadToons</h3>
      <hr>
  <div style="width: 200px; margin: auto;">
  <img src="'.image($r,"poster", "mid").'" alt="'.$r['anime_name'].' thumbnail" width="200" style="border-radius:10px;">
</div>
<hr>
      <h2 style="text-align:center;">Download Links</h2>
<p>We provide Multiple Links with multiple qualities like 480p 720p 1080p for each episode.All links are very fast.</p>
      <hr>
       [deadbase animeid="'.$id.'" ' . ($r['type'] == "tv" ? 'season="01"' : "") .'type="'.$r['type'].'"]
       <hr>
       ';
        
        return $postcode;
}



function image($a,$type,$res) {
    
    $array = array(
        '1' => array(
            'poster_min' => 'w92',
            'poster_low' => 'w154',
            'poster_mid' => 'w342',
            'poster_high' => '',
            'backdrop_low' => 'w185',
            'backdrop_mid' => 'w300',
            'backdrop_high' => 'w780',
            )
        );
    
    
    if($type == "poster") {
        if($a['poster_source'] == 1) {
            $img = "https://image.tmdb.org/t/p/".$array['1']['poster_'.$res].$a['poster_img'];
        } elseif($a['poster_source'] == 3) {
            $img = "https://deadtoonsindia.cc/content/".$a['poster_img'];
        } elseif ($a['poster_source'] == 2) {
            $img = $a['poster_img'];
        }
    } else {
        if($a['backdrop_source'] == 1) {
            $img = "https://image.tmdb.org/t/p/".$array['1']['backdrop_'.$res].$a['backdrop_img'];
        } elseif($a['backdrop_source'] == 3) {
            $img = "https://deadtoonsindia.cc/content/".$a['backdrop_img'];
        }
    }
    
    return $img;
}

function check_login() {
    
    if(!isset($_COOKIE['dt'])) {
        header("Location: login.php");
        }
}

function telegram($note, $name, $photo ,$link,$ep = 0, $movie = false) {
    
    echo "<br>" . $photo;
    
    $update = "";
    
    if(!$movie) {
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

 
 
function slugify($string) {
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
