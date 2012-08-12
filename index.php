<html>

<head>
    <meta charset="UTF-8" />
</head>

<body>
<?php
    
    set_time_limit(0);

    # Include RedBean library.
    include("rb.php");

    # Include the Simple Html Dom library.
    include("simple_html_dom.php");

    # Include the NewsModel class.
    include("NewsModel.php");

    # ------------------------------------- #

    # RedBean configuration:
    R::setup('mysql:host=localhost;dbname=noticia','root','');

    $parsedNews = array();

    # Download the HTML for the UVM website (page 1) and parse it.
    $blogHtml = file_get_html('http://www.uvm.cl/noticias_mas.shtml');
    parse_page_for_news($blogHtml, $parsedNews);
    
    # Download the HTML and parse for the remaining blog pages (263 pages).
    for ($i = 2; $i <= 20; $i++) {
        $url = "http://www.uvm.cl/noticias_mas.shtml?AA_SL_Session=34499aef1fc7a296fb666dcc7b9d8d05&scrl=1&scr_scr_Go=" . $i;
        $page = file_get_html($url);
        parse_page_for_news($page, $parsedNews);
    }

    echo "<h2>Proceso Completo.</h2><p>Noticias encontradas:" . count($parsedNews) . "</p>";

    # Save every parsed item to the database.
    foreach($parsedNews as &$tmpNews) {
        $noticia = R::dispense('noticia');
        $noticia->imagen = $tmpNews->get_image();
        $noticia->fecha = $tmpNews->get_fechanoticia();
        $noticia->titulo = $tmpNews->get_title();
        $noticia->url = $tmpNews->get_sourceurl();
        $noticia->descripcion = $tmpNews->get_description(); 
        $id = R::store($noticia);          
        echo $tmpNews->get_description();
        echo "<br />";
    }

    # Disconnect from the database.
    R::close();


    # ------------------------------------- #


    # Fuction receives an HTML Dom object, and the library works agianst that single HTML object.
    function parse_page_for_news ($page, &$parsedNews) {

        foreach($page->find('#cont2 p') as $element) {

            $newItem = new NewsModel;

            // Parse the news item's thumbnail image.
            foreach ($element->find('img') as $image) {
                $newItem->set_image($image->src);
                //echo $newItem->get_image() . "<br />";
            }

            // Parse the news item's post date.
            foreach ($element->find('span.fechanoticia') as $fecha) {
                $newItem->set_fechanoticia($fecha->innertext);
                //echo $newItem->get_fechanoticia() . "<br />";
            }

            // Parse the news item's title.
            foreach ($element->find('a') as $title) {
                $newItem->set_title(iconv("ISO-8859-1", "UTF-8", $title->innertext));
                //echo $newItem->get_title() . "<br />";
            }

            // Parse the news item's source URL link.
            foreach ($element->find('a') as $sourceurl) {
                $newItem->set_sourceurl("http://www.uvm.cl/" . $sourceurl->href);
            }

            // Parse the news items' description text.
            foreach ($element->find('a') as $link) {
                $link->outertext = '';
            }

            foreach ($element->find('span') as $link) {
                $link->outertext = '';
            }

            foreach ($element->find('img') as $link) {
                $link->outertext = '';
            }

            $newItem->set_description(iconv("ISO-8859-1", "UTF-8", $element->innertext));

            # Add the newly parsed item to the global parsedNews collection.
            $parsedNews[] = $newItem;
        }
    } 
?>

</body>
</html>