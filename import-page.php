<?php
$message = '';
$target_file = dirname(__FILE__) . "/rss.xml";
$uploadOk = 0;
if (isset($_POST["submit"])) {
    $nomOrigine = $_FILES['fileToUpload']['name'];
    $extensionsAutorisees = array("xml");
    $elementsChemin = pathinfo($nomOrigine);
    $extensionFichier = strtolower($elementsChemin['extension']);
    if (!(in_array($extensionFichier, $extensionsAutorisees))) {
        $message.= __("Le fichier n'est pas en xml", "alt-import-drupal");
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            if (file_exists($target_file)) {
                $xml = simplexml_load_file($target_file);
                if ($xml != FALSE) {
                    foreach ($xml->channel->item as $item) {
                        $pubDate = $item->pubDate;
                        $pubDate = strftime("%Y-%m-%d %H:%M:%S", strtotime($pubDate));

                        $post = array(
                            'post_content' => $item->description,
                            'post_name' => sanitize_title($item->title),
                            'post_title' => $item->title,
                            'post_status' => 'publish',
                            'post_type' => 'post',
                            'post_author' => 1,
                            'post_date' => $pubDate,
                            'post_date_gmt' => $pubDate,
                            'comment_status' => 'closed'
                        );
                        wp_insert_post($post);
                    }
                    $message.=__('Impot terminer, voir vos articles <a href="/wp-admin/edit.php">Mes articles</a>', "alt-import-drupal");
                } else {
                    $message.=__('Erreur au chargement de votre fichier', "alt-import-drupal");
                }
            } else {
                $message.=__('Vous devez uploader votre fichier xml', "alt-import-drupal");
            }
        } else {
         $message.=__("Erreur lors de l'upload de votre fichier xml", "alt-import-drupal");
        }
    }
}
?>
<?php _e("Dans votre Drupal<br/>
<br/>
Rendez vous sur  structure => views   /admin/structure/views<br/>
Recherchez Flux RSS <br/>
Activez le s'il ce n'est pas déjà le cas puis cliquez sur modifier<br/>
Choisissiez la pagination  en mode \"complet\" et \"éléments par page 0\" puis enregistrez<br/>
Rendez vous ensuite sur votre page rss  votre-site.fr/rss.xml<br/>
Enregistrez le flux rss sous forme rss.xml<br/>", "alt-import-drupal"); ?>
<br/>
<form  method="post" enctype="multipart/form-data">
    <?php _e("Fichier .xml à uploader", "alt-import-drupal"); ?>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="<?php _e("Lancer la récuparation", "alt-import-drupal"); ?>" name="submit">
</form>
<br/>
<?php echo ($message != '') ? $message : ''; ?>