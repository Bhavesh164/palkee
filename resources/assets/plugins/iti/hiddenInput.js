var input = document.querySelector("#phone");
window.intlTelInput(input, {
  hiddenInput: "full_phone",
  utilsScript: "<?=ASSET_FOLDER?>js/iti/utils.js" // just for formatting/placeholders etc
});
