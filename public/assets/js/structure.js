let titre;
let extension;
document.addEventListener("DOMContentLoaded", function () {
  let divs = document.querySelectorAll(".couleurs>div");
  titre = document
    .querySelector("#titre_robe")
    .textContent.trim()
    .toLowerCase()
    .replace(/ /g, "_");
  let image = document.querySelector("#image_robe");
  divs.forEach((div) => {
    let nomCouleur = div.textContent.trim().toLowerCase();
    // Fichier Json qui converti les noms de couleur fr => en
    fetch("/assets/json/couleurs.json")
      .then((response) => response.json())
      .then((data) => {
        div.querySelector("p").style.backgroundColor = data[nomCouleur];
      })

      .catch((error) => console.log(error));

    div.addEventListener("click", function () {
      divs.forEach((div) => {
        div.classList.remove("active");
      });
      div.classList.toggle("active");
      let imageName = titre + "_" + nomCouleur;
      let source = image.getAttribute("src");

      extension = source.split(".").pop().split("?")[0];
      image.setAttribute(
        "src",
        "/assets/images/" + imageName + "." + extension
      );
    });
  });
});
function addToCart(id) {
  let button = document.querySelector("#bouton_panier");
  let url = button.getAttribute("data-url");
  let divs = document.querySelectorAll(".couleurs>div");
  let selectedColor = null;
  let taille = document.querySelector("#optionsTailles").value;

  divs.forEach((div) => {
    if (div.classList.contains("active")) {
      selectedColor = div.id;
    }
  });
  console.log(titre + "_" + selectedColor);
  let image = titre + "_" + selectedColor + "." + extension;

  if (!selectedColor && taille == "null") {
    customAlert("Merci de choisir une Taille et une Couleur pour votre Robe !");
    return;
  } else if (!selectedColor) {
    customAlert("Merci de choisir une couleur pour votre robe !");
    return;
  } else if (taille == "null") {
    customAlert("Merci de choisir une taille pour votre robe !");
    return;
  }

  fetch(url, {
    method: "post",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      id: id,
      couleur: selectedColor,
      taille: taille,
      image: image,
    }),
  })
    .then((result) => {
      return result.json();
    })
    .then((result) => {
      let paragraphe = document.querySelector("#success_add");
      paragraphe.style.display = "block";
      // On récupère le paragraphe (où est affiché le nombre d'articles)
      let nb = document.querySelector("#session_nb");

      if (result.error) {
        paragraphe.textContent = result.error;
      } else {
        // On remplace le nombre actuel par le nouveau nombre envoyé par le serveur
        nb.textContent = result.nb;
        paragraphe.textContent = result.message;
        setTimeout(() => {
          paragraphe.style.display = "none";
        }, 3000);
      }
    })
    .catch((error) => console.log(error));
}
function removeFromCart(event, id, couleur, taille) {
  event.preventDefault(); // Empêche le rechargement de la page
  let data = document.querySelector("#data"); // Si besoin, référence au bouton actuel
  let url = data.getAttribute("data-url");

  if (!url) {
    console.error("L'URL pour retirer un article n'est pas définie.");
    return;
  }

  fetch(url, {
    method: "post", // Méthode POST pour retirer
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id: id, couleur: couleur, taille: taille }), // Passe l'ID au backend
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erreur réseau lors de la suppression de l'article.");
      }
      return response.json();
    })
    .then((result) => {
      let nb = document.querySelector("#session_nb");

      nb.textContent = result.nb;

      if (nb.textContent == 0) {
        nb.textContent = "";
      }
      // Affiche le message de confirmation
      let paragraphe = document.querySelector("#success_remove");
      if (paragraphe) {
        paragraphe.style.display = "block";
        paragraphe.textContent = result.message;
      }

      // On récupère la balise span du total du panier
      let span = document.querySelector("#total_panier");

      let total = parseFloat(span.textContent);

      // On remplace le total par le nouveau total (tofixed = limite à 2 chiffres après la décimale)
      span.textContent = result.total.toFixed(2);

      // On récupère l'element parent de celui sur lequel on click (le bouton supprimer)
      let li = event.target.parentElement;
      // On supprime cet élément
      let quantities =
        event.target.parentElement.querySelectorAll(".item-quantity");
      quantities
        .forEach((element) => {
          // console.log(parseInt(element.textContent.substring(0,element.textContent.indexOf('x'))));
          let quantity = parseInt(
            element.textContent.substring(0, element.textContent.indexOf("x"))
          );

          element.textContent = (quantity -= 1) + " x";

          if (result.total <= 0) {
            document.querySelector("#cart-container").innerHTML =
              '<h2 class="cart-title">Votre Panier</h2><p class="cart-empty">Votre panier est vide.</p>';
          }
          if (quantity < 1) {
            li.remove();
          }
        })
        .catch((error) =>
          console.error("Erreur lors de la suppression :", error)
        );
    });
}

// Fonction pour afficher la modale

function customAlert(message) {
  // Sélectionne les éléments de la boîte de dialogue
  const dialog = document.getElementById("custom-dialog");
  const dialogMessage = document.getElementById("dialog-message");
  const dialogOk = document.getElementById("dialog-ok");

  // Met à jour le message
  dialogMessage.textContent = message;

  // Affiche la boîte de dialogue
  dialog.classList.add("dialog");

  // Ajoute un gestionnaire d'événements au bouton "OK"
  dialogOk.onclick = function () {
    dialog.classList.remove("dialog");
  };

  // Ici je mets en place le javascript qui dynamise mon bouton hamburger
}
document.addEventListener("DOMContentLoaded", function () {
  const hamburgerMenu = document.getElementById("hamburgerMenu");
  const menuDiv = document.getElementById("menu_div");

  hamburgerMenu.addEventListener("click", function () {
    menuDiv.classList.toggle("show");
  });

  // Pour fermer le menu quand on clique à l'extérieur
  document.addEventListener("click", function (event) {
    if (
      !menuDiv.contains(event.target) &&
      !hamburgerMenu.contains(event.target)
    ) {
      menuDiv.classList.remove("show");
    }
  });
});
