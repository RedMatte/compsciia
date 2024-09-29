<!-- PHP INCLUDES -->

<?php

include "./admin/connect.php";
include 'Includes/functions/functions.php';
include "Includes/templates/header.php";
include "Includes/templates/navbar.php";


//Getting website settings

$stmt_web_settings = $con->prepare("SELECT * FROM website_settings");
$stmt_web_settings->execute();
$web_settings = $stmt_web_settings->fetchAll();

$restaurant_name = "";
$restaurant_email = "";
$restaurant_address = "";
$restaurant_phonenumber = "";

foreach ($web_settings as $option) {
    if ($option['option_name'] == 'restaurant_name') {
        $restaurant_name = $option['option_value'];
    } elseif ($option['option_name'] == 'restaurant_email') {
        $restaurant_email = $option['option_value'];
    } elseif ($option['option_name'] == 'restaurant_phonenumber') {
        $restaurant_phonenumber = $option['option_value'];
    } elseif ($option['option_name'] == 'restaurant_address') {
        $restaurant_address = $option['option_value'];
    }
}

?>

<!-- HOME SECTION -->

<section class="home-section" id="home">
    <div class="container">
        <div class="row" style="flex-wrap: nowrap;">
            <div class="col-md-12 home-left-section">
                <div style="padding: 100px 0px; color: white;" class="text-center">
                    <h1> VINCENT PIZZA.</h1>
                    <h2>
                        MAKING PEOPLE HAPPY
                    </h2>
                    <hr>
                    <p>
                        Italian Pizza With Cherry Tomatoes and Green Basil
                    </p>
                    <div style="display: flex; justify-content:space-between;">
                        <a href="order_food.php" class="button_secondary"
                            style="margin-right: 10px; display: flex;justify-content: center;align-items: center;">
                            Order Now
                        </a>
                        <a href="#menus" class="button_primary"
                            style="display: flex;justify-content: center;align-items: center;">
                            VIEW MENU
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- OUR QUALITIES SECTION -->

<section class="our_qualities">
    <div class="container">
        <div class="row">
            <div class="col-md-4 px-5">
                <div class="our_qualities_column">
                    <div class="caption">
                        <h3>
                            Quality Foods
                        </h3>
                        <p>
                            Sit amet, consectetur adipiscing elit quisque eget maximus velit,
                            non eleifend libero curabitur dapibus mauris sed leo cursus aliquetcras suscipit.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 px-5">
                <div class="our_qualities_column">
                    <div class="caption">
                        <h3>
                            Best Packing
                        </h3>
                        <p>
                            Sit amet, consectetur adipiscing elit quisque eget maximus velit,
                            non eleifend libero curabitur dapibus mauris sed leo cursus aliquetcras
                            suscipit.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 px-5">
                <div class="our_qualities_column">
                    <div class="caption">
                        <h3>
                            Home Delivery
                        </h3>
                        <p>
                            Sit amet, consectetur adipiscing elit quisque eget maximus velit,
                            non eleifend libero curabitur dapibus mauris sed leo cursus aliquetcras suscipit.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- OUR MENUS SECTION -->
<section class="our_menus" id="menus">
    <div class="container">
        <h2 style="text-align: center;padding-bottom: 30px">DISCOVER OUR MENUS</h2>
        <div class="menus_tabs">
            <div class="menus_tabs_picker">
                <ul style="text-align: center;padding-bottom: 10px">
                    <?php
                    $stmt = $con->prepare("Select * from menu_categories");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    $count = $stmt->rowCount();
                    $x = 0;
                    foreach ($rows as $row) {
                        if ($x == 0) {
                            echo "<li class = 'menu_category_name tab_category_links active_category' onclick=showCategoryMenus(event,'" . str_replace(' ', '', $row['category_name']) . "')>";
                            echo $row['category_name'];
                            echo "</li>";
                        } else {
                            echo "<li class = 'menu_category_name tab_category_links' onclick=showCategoryMenus(event,'" . str_replace(' ', '', $row['category_name']) . "')>";
                            echo $row['category_name'];
                            echo "</li>";
                        }

                        $x++;
                    }
                    ?>
                </ul>
            </div>

            <div class="menus_tab">
                <?php
                $stmt = $con->prepare("Select * from menu_categories");
                $stmt->execute();
                $rows = $stmt->fetchAll();
                $count = $stmt->rowCount();

                $i = 0;

                foreach ($rows as $row) {

                    if ($i == 0) {

                        echo '<div class="menu_item  tab_category_content" id="' . str_replace(' ', '', $row['category_name']) . '" style=display:block>';

                        $stmt_menus = $con->prepare("Select * from menus where category_id = ?");
                        $stmt_menus->execute(array($row['category_id']));
                        $rows_menus = $stmt_menus->fetchAll();

                        if ($stmt_menus->rowCount() == 0) {
                            echo "<div style='margin:auto'>No Available Menus for this category!</div>";
                        }

                        echo "<div class='row'>";
                        foreach ($rows_menus as $menu) {
                            ?>

                            <div class="col-md-4 col-lg-3 menu-column">
                                <div class="thumbnail" style="cursor:pointer">
                                    <?php $source = "admin/Uploads/images/" . $menu['menu_image']; ?>

                                    <div class="menu-image">
                                        <div class="image-preview">
                                            <div style="background-image: url('<?php echo $source; ?>');"></div>
                                        </div>
                                    </div>

                                    <div class="caption">
                                        <h5>
                                            <?php echo $menu['menu_name']; ?>
                                        </h5>
                                        <p>
                                            <?php echo $menu['menu_description']; ?>
                                        </p>
                                        <span class="menu_price">
                                            <?php echo "$" . $menu['menu_price']; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                        echo "</div>";

                        echo '</div>';
                    } else {

                        echo '<div class="menus_categories  tab_category_content" id="' . str_replace(' ', '', $row['category_name']) . '">';

                        $stmt_menus = $con->prepare("Select * from menus where category_id = ?");
                        $stmt_menus->execute(array($row['category_id']));
                        $rows_menus = $stmt_menus->fetchAll();

                        if ($stmt_menus->rowCount() == 0) {
                            echo "<div class = 'no_menus_section'>No Available Menus for this category!</div>";
                        }

                        echo "<div class='row'>";
                        foreach ($rows_menus as $menu) {
                            ?>
                            <div class="col-md-4 col-lg-3 menu-column">
                                <div class="thumbnail" style="cursor:pointer">
                                    <?php $source = "admin/Uploads/images/" . $menu['menu_image']; ?>
                                    <div class="menu-image">
                                        <div class="image-preview">
                                            <div style="background-image: url('<?php echo $source; ?>');"></div>
                                        </div>
                                    </div>
                                    <div class="caption">
                                        <h5>
                                            <?php echo $menu['menu_name']; ?>
                                        </h5>
                                        <p>
                                            <?php echo $menu['menu_description']; ?>
                                        </p>
                                        <span class="menu_price">
                                            <?php echo "$" . $menu['menu_price']; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                        echo "</div>";

                        echo '</div>';
                    }

                    $i++;
                }

                echo "</div>";

                ?>
            </div>
        </div>
    </div>
</section>
<!-- IMAGE GALLERY -->

<section class="image-gallery" id="gallery">
    <div class="container">
        <h2 style="text-align: center;margin-bottom: 30px">IMAGE GALLERY</h2>
        <?php
        $stmt_image_gallery = $con->prepare("Select * from image_gallery");
        $stmt_image_gallery->execute();
        $rows_image_gallery = $stmt_image_gallery->fetchAll();
        echo "<div class = 'row'>";
        foreach ($rows_image_gallery as $row_image_gallery) {
            echo "<div class = 'col-md-4 col-lg-3' style = 'padding: 15px;'>";
            $source = "admin/Uploads/images/" . $row_image_gallery['image'];
            ?>
            <div class="image-gallery-item"
                style="background-image: url('<?php echo $source; ?>') !important;background-repeat: no-repeat;background-position: 50% 50%;background-size: cover;background-clip: border-box;box-sizing: border-box;overflow: hidden;height: 230px;">
            </div>
            <?php
            echo "</div>";
        }

        echo "</div>";
        ?>
    </div>
</section>

<!-- CONTACT US SECTION -->

<section class="contact-section" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 sm-padding">
                <div class="contact-info">
                    <h2>
                        Get in touch with us &
                        <br>send us message today!
                    </h2>
                    <p>
                        Saasbiz is a different kind of architecture practice. Founded by LoganCee in 1991, we’re an
                        employee-owned firm pursuing a democratic design process that values everyone’s input.
                    </p>
                    <h3>
                        <?php echo $restaurant_address; ?>
                    </h3>
                    <h4>
                        <span>Email:</span>
                        <?php echo $restaurant_email; ?>
                        <br>
                        <span>Phone:</span>
                        <?php echo $restaurant_phonenumber; ?>
                    </h4>
                </div>
            </div>
            <div class="col-lg-6 sm-padding">
                <div class="contact-form">
                    <div id="contact_ajax_form" class="contactForm">
                        <div class="form-group colum-row row">
                            <div class="col-sm-6">
                                <input type="text" id="contact_name" name="name"
                                    oninput="document.getElementById('invalid-name').innerHTML = ''"
                                    onkeyup="this.value=this.value.replace(/[^\sa-zA-Z]/g,'');" class="form-control"
                                    placeholder="Name">
                                <div class="invalid-feedback" id="invalid-name" style="display: block">

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <input type="email" id="contact_email" name="email"
                                    oninput="document.getElementById('invalid-email').innerHTML = ''"
                                    class="form-control" placeholder="Email">
                                <div class="invalid-feedback" id="invalid-email" style="display: block">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" id="contact_subject" name="subject"
                                    oninput="document.getElementById('invalid-subject').innerHTML = ''"
                                    onkeyup="this.value=this.value.replace(/[^\sa-zA-Z]/g,'');" class="form-control"
                                    placeholder="Subject">
                                <div class="invalid-feedback" id="invalid-subject" style="display: block">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea id="contact_message" name="message"
                                    oninput="document.getElementById('invalid-message').innerHTML = ''" cols="30"
                                    rows="5" class="form-control message" placeholder="Message"></textarea>
                                <div class="invalid-feedback" id="invalid-message" style="display: block">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button id="contact_send" class="button_primary">Send Message</button>
                            </div>
                        </div>
                        <div id="sending_load" style="display: none;">Sending...</div>
                        <div id="contact_status_message"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
include "Includes/templates/footer.php";
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#contact_send').click(function () {
            var contact_name = $('#contact_name').val();
            var contact_email = $('#contact_email').val();
            var contact_subject = $('#contact_subject').val();
            var contact_message = $('#contact_message').val();

            var flag = 0;

            if ($.trim(contact_name) == "") {
                $('#invalid-name').text('This is a required field!');
                flag = 1;
            } else {
                if (contact_name.length < 5) {
                    $('#invalid-name').text('Length is less than 5 letters!');
                    flag = 1;
                }
            }

            if (!ValidateEmail(contact_email)) {
                $('#invalid-email').text('Invalid e-mail!');
                flag = 1;
            }

            if ($.trim(contact_subject) == "") {
                $('#invalid-subject').text('This is a required field!');
                flag = 1;
            }

            if ($.trim(contact_message) == "") {
                $('#invalid-message').text('This is a required field!');
                flag = 1;
            }

            if (flag == 0) {
                $('#sending_load').show();

                $.ajax({
                    url: "Includes/php-files-ajax/contact.php",
                    type: "POST",
                    data: {
                        contact_name: contact_name,
                        contact_email: contact_email,
                        contact_subject: contact_subject,
                        contact_message: contact_message
                    },
                    success: function (data) {
                        $('#contact_status_message').html(data);
                    },
                    beforeSend: function () {
                        $('#sending_load').show();
                    },
                    complete: function () {
                        $('#sending_load').hide();
                    },
                    error: function (xhr, status, error) {
                        alert("Internal ERROR has occured, please, try later!");
                    }
                });
            }

        });
    });
</script>