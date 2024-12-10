// document.addEventListener("DOMContentLoaded", function () {
//   let produits = document.querySelectorAll(".produit");

//   produits.forEach(function (produit) {
//     let images = produit.querySelectorAll("img");

//     images.forEach(function (image) {
//       image.addEventListener("click", function () {
//         // Récupère les transformations actuelles définies par le style inline
//         let currentTransform = image.style.transform || "";

//         // Vérifie si "scaleX(-1)" est déjà présent
//         if (currentTransform.includes("scaleX(-1)")) {
//           // Si oui, on le retire
//           image.style.transform = currentTransform
//             .replace("scaleX(-1)", "")
//             .trim();
//         } else {
//           // Sinon, on l'ajoute en préservant les transformations existantes
//           image.style.transform = currentTransform + " scaleX(-1)";
//         }
//       });
//     });
//   });
// });
function addToCart(id) {
  let button = document.querySelector("#bouton_panier");
  let url = button.getAttribute("data-url");
  console.log(url);
  fetch(url, {
    method: "post",
    body: id,
  })
    .then((result) => {
      return result.json();
    })
    .then((result) => {})
    .catch((error) => console.log(error));
}
