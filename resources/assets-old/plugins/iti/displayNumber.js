var input = document.querySelector("#phone");
window.intlTelInput(input, {
  utilsScript: "<?=ASSET_FOLDER?>js/iti/utils.js" // just for formatting/placeholders etc
});
