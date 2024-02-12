function toggleMenu() {
    $("#navbar").toggleClass("open");
    $("#menu-box").slideToggle(300);
}

function toggleItemModal(item = null) {
    console.log(item);
    const modal = $("#item-modal");

    if (modal[0].open) {
        modal[0].close();
        modal.removeClass("in");
    } else {
        modal[0].showModal();
        modal.addClass("in");
    }
}