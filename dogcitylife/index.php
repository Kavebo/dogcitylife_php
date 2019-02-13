<?php include "header.php"; ?>
    <div class="container">
        <h2 class="doporucujeme"><?php _e('Doporučujeme'); ?></h2>
        <?php $doporucujeme = $db->fetch_all("SELECT * from zarizeni WHERE active = 1 AND lang LIKE '" . get_current_lang() . "' AND doporucujeme=1 ORDER BY RAND() LIMIT 6"); ?>
        <div class="zarizeni_list_third">
            <?php foreach($doporucujeme as $zarizeni): ?>
                <?php include("templates/zarizeni_box.php"); ?>
            <?php endforeach; ?>
        </div>
        <div class="clear"></div>
        <div class="register_banner">
            <div class="register_banner_div">
                <div id="a"><img src="<?php echo get_front_url(). "img/register_banner_logo_dcl.png"; ?>" alt="DCL" class="register_banner_dcl"/></div>
                <div id="b"><a class="btn_border register_fancybox" href="http://eshop.yoggies.cz" target="_blank">Zdraví začíná v misce <sup>&reg;</sup></a></div>
                <div id="c"><img src="<?php echo get_front_url() . "img/register_banner_logo_yoggies.png"; ?>" class="register_banner_yoggies"/></div>
            </div>
            <div class="clear"></div>
        </div>
        <h2 class="best"><?php _e('Nejlépe hodnoceno'); ?></h2>
        <?php $doporucujeme = $db->fetch_all("SELECT zarizeni.ID,zarizeni.name,zarizeni.address, zarizeni.kavarna, zarizeni.restaurace, zarizeni.cvicak, zarizeni.hotel, zarizeni.hriste, zarizeni.permalink from zarizeni LEFT JOIN reviews ON zarizeni.ID=reviews.zarizeni_ID WHERE active = 1 AND lang LIKE '" . get_current_lang() . "' GROUP BY zarizeni.ID");

            if($doporucujeme){
                foreach ($doporucujeme as $key => $value) {
                    $hodnoceni_avg = $db->fetch("SELECT avg(obsluha),avg(dog_friendly),avg(jidlo),avg(prostredi) FROM reviews WHERE zarizeni_ID=" . $value['ID']);
                    $all_hodnoceni = $db->fetch("SELECT count(ID) FROM reviews WHERE zarizeni_ID=" . $value['ID']);
                    if($all_hodnoceni){
                        $all_hodnoceni = $all_hodnoceni['count(ID)'];
                    }else{
                        $all_hodnoceni = 0;
                    }

                    $doporucujeme[$key]["all_hodnoceni"] = $all_hodnoceni;

                    $average = 0;
                    if($hodnoceni_avg){
                        foreach ($hodnoceni_avg as $value) {
                            $average += $value;
                        }

                        $average = round($average/count($hodnoceni_avg));
                    }

                    $doporucujeme[$key]["average"] = $average;

                }

                usort($doporucujeme, function($a, $b) {
                    return $b['average'] - $a['average'];
                });

                $doporucujeme = array_slice($doporucujeme, 0, 6);
            }
        ?>
        <div class="zarizeni_list_third">
            <?php foreach($doporucujeme as $zarizeni): ?>
                <?php include("templates/zarizeni_box.php"); ?>
            <?php endforeach; ?>
        </div>
        <div class="clear"></div>
        <div class="pes_hp"><img src="<?php echo get_front_url() . "img/hp_botom.png"; ?>"></div>
        <div class="instagram"><a class="" href="https://www.instagram.com/dogcitylife_cz/"><h2><?php _e('Instagram'); ?> </h2></a><span>#dogcitylifecz</span></div>
        <?php

            $insta = file_get_contents("instagram.json");
            $insta = json_decode($insta);
        ?>

        <div class="insta_photos">
            <?php foreach($insta as $img): ?>
                <a target="_blank" href="<?php echo $img->link; ?>"><img src="<?php echo $img->img; ?>" alt="<?php echo $img->text; ?>">
            <?php endforeach; ?>
        </div>
        <div class="clear"></div>
    </div>
<?php include "footer.php"; ?>