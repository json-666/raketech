<?php



var_dump($features);
$row = '
<tr>
    <td class="text-center">
        <img src="' . $logo . '" style="width: 100%">
        <a href="' . $site_url . '/' . $brand_id . '">Review</a>
    </td>
    <td class="text-center table__stars">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <span class="fa fa-star checked"></span>
        <span class="fa fa-star checked"></span>
        <span class="fa fa-star checked"></span>
        <span class="fa fa-star-o"></span>
        <span class="fa fa-star-o"></span>
        <p>' . $bonus . '</p>
    </td>
    <td>
        <ul>
            ' . $features_text . '
        </ul>
    </td>
    <td class="text-center">
        <a href="' . $play_url . '" class="table__playnow">Play now</a>
        <p class="table__terms">' . $terms_and_conditions . '</p>
    </td>
</tr>
';
?>