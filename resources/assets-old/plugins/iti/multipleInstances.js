var inputHome = document.querySelector("#home");
var inputMobile = document.querySelector("#mobile");

window.intlTelInput(inputHome, {
  initialCountry: 'gb',
  placeholderNumberType: 'FIXED_LINE',
  utilsScript: "<?=ASSET_FOLDER?>js/iti/utils.js" // just for formatting/placeholders etc
});
window.intlTelInput(inputMobile, {
  initialCountry: 'gb',
  placeholderNumberType: 'MOBILE',
  utilsScript: "<?=ASSET_FOLDER?>js/iti/utils.js"
});
