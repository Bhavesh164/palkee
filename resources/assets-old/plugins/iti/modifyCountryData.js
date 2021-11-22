var countryData = window.intlTelInputGlobals.getCountryData(),
  input = document.querySelector("#phone");

for (var i = 0; i < countryData.length; i++) {
  var country = countryData[i];
  country.name = country.name.replace(/.+\((.+)\)/,"$1");
}

window.intlTelInput(input, {
  utilsScript: "<?=ASSET_FOLDER?>js/iti/utils.js" // just for formatting/placeholders etc
});