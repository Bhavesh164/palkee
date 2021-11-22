var input = document.querySelector("#phone");
var statusElement = document.querySelector("#status");

var iti = window.intlTelInput(input, {
  utilsScript: "<?=ASSET_FOLDER?>js/iti/utils.js",
});
iti.promise.then(function() {
  statusElement.innerHTML = "Initialised!";
});
