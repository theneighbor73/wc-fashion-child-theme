function custom_print_shop_openCity(evt, cityName) {
    var custom_print_shop_i, custom_print_shop_tabcontent, tablinks;
    custom_print_shop_tabcontent = document.getElementsByClassName("tabcontent");
    for (custom_print_shop_i = 0; custom_print_shop_i < custom_print_shop_tabcontent.length; custom_print_shop_i++) {
        custom_print_shop_tabcontent[custom_print_shop_i].style.display = "none";
    }
    custom_print_shop_tablinks = document.getElementsByClassName("tablinks");
    for (custom_print_shop_i = 0; custom_print_shop_i < custom_print_shop_tablinks.length; custom_print_shop_i++) {
        custom_print_shop_tablinks[custom_print_shop_i].className = custom_print_shop_tablinks[custom_print_shop_i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}