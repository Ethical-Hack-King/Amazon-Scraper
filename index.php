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
                // echo $search;
            
                if (!empty($search)) {
                    $curl = curl_init();
                    // $search_string = 'ps4+games';
                    // $url = "https://www.amazon.in/s?k=$search_string";
                    $url = "https://www.amazon.in/s?k=$search";
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                    $result = curl_exec($curl);
                    /*  
                        https://m.media-amazon.com/images/I/51DtuAM0HsL._AC_UY218_.jpg
                        https://m.media-amazon.com/images/I/71UneM84G7L._AC_UY218_.jpg
                        https://m.media-amazon.com/images/I/713vSco+MPL._AC_UL320_.jpg
                        [0] => https://m.media-amazon.com/images/I/51ndxZcdt+L._AC_UY218_.jpg
                        [1] => https://m.media-amazon.com/images/I/51ndxZcdt+L._AC_UY327_QL65_.jpg
                    */
                    // preg_match_all("!https://m.media-amazon.com/images/I/[^/s]*?._AC_UY218_.jpg!",$result,$product_image); //old
                    preg_match_all("!https://m.media-amazon.com/images/I/[^/s]*?._AC_[^/s]*_.jpg!",$result,$product_image);
                    preg_match_all('!a-size-medium a-color-base a-text-normal" dir="auto">[^/s]*?.</span>!',$result,$product_name);
                    // print_r($product_name);
                    $images = array_values(array_unique($product_image[0]));
                    // echo "<pre>";
                    // print_r($product_name);
                    // echo "</pre>";

                    echo '<div class="row"> ';
                    for ($i=0; $i<count($images); $i++) { 
                        if (strlen($images[$i]) == 62) {
                            echo '<div class="column">';
                            echo "<img src='$images[$i]'>";
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                    curl_close($curl);
                }
            }
        ?>
    </div>
</body>
</html>