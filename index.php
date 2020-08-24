<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Amazon Scraper</title>
</head>
<body>
    <div class="container">        
        <h1>Amazon Scraper</h1>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <input type="text" name="search" id="search" placeholder="Search Term">
            <input type="submit" name="scrap" value="Scrap">
        </form>
        <?php
            if (isset($_GET['scrap'])) {
                $search = $_GET['search'];
                $search = str_replace(' ','+', $search);
            
                if (!empty($search)) {
                    $curl = curl_init();
                    // $search_string = 'ps4+games';
                    // $url = "https://www.amazon.in/s?k=$search_string";
                    $url = "https://www.amazon.in/s?k=$search";
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                    $result = curl_exec($curl);
                    // preg_match_all("!https://m.media-amazon.com/images/I/[^/s]*?._AC_UY218_.jpg!",$result,$product_image); //old
                    preg_match_all("!https://m.media-amazon.com/images/I/[^/s]*?._AC_[^/s]*_.jpg!",$result,$product_image);
                    // preg_match_all('!a-size-medium a-color-base a-text-normal" dir="auto">[^/s]*?.</span>!',$result,$product_name); //WIP
                    $images = array_values(array_unique($product_image[0]));

                    echo '<div class="row"> ';
                    for ($i=0; $i<count($images); $i++) { 
                        if (strlen($images[$i]) == 62) {
                            echo '<div class="column">';
                            echo "<img src='$images[$i]'>";
                            echo '</div>';
                        }
                        // Download Images
                        $img_url = $images[$i];
                        $dir = 'images/';
                        $filename = basename($img_url);
                        $complete_save_loc = $dir.$filename;
                        file_put_contents($complete_save_loc,file_get_contents($img_url));
                    }
                    echo '</div>';
                    // Pagination - WIP
                    //https://www.amazon.in/s?k=ps4+games&ref=nb_sb_ss_i_1_6
                    //https://www.amazon.in/s?k=ps4+games&page=2&qid=1598251818&ref=sr_pg_2
                    curl_close($curl);
                }
            }
        ?>
    </div>
</body>
</html>