let titre;
let extension;
document.addEventListener("DOMContentLoaded", function () {
  // On récupère les div qui contient la couleur dans la div qui a la class .couleur
  let divs = document.querySelectorAll(".couleurs>div");

  // titre = .l'element html qui a l'id titre_robe .le contenu de l'element (le texte) .on supprime les espaces avant et après (trim()) .on change le texte en minuscule(toLowerCase()) .on remplace les espaces entre les mots par _ (replace).

  /* Exemple : titre de la robe = Robe de mariée magnifique . Resultat = robe_de_mariée_magnifique */
  titre = document
    .querySelector("#titre_robe")
    .textContent.trim()
    .toLowerCase()
    .replace(/ /g, "_");

  // On récupère l'element HTML qui a l'id image_robe
  let image = document.querySelector("#image_robe");
  // On récupère le lien de la balise <a> pour le zoom de l'image
  let ancre = document.querySelector("#lien_img");

  // foreach = pour chacunes des divs qu'on a récupérées (pour la couleur) (div) représente chaque div individuelle
  divs.forEach((div) => {
    // nomCouleur = chaque div .le texte qu'elles contiennent .les espaces supprimés avant et après .tout en minuscule (traduction: on récupère le texte dans chacune des div et on le change en minuscule en supprimant les espaces)
    let nomCouleur = div.textContent.trim().toLowerCase();

    // Fichier Json qui converti les noms de couleur fr => en, requête Ajax sur ce fichier
    fetch("/assets/json/couleurs.json")
      .then((response) => response.json())
      .then((data) => {
        // la requête renvoie un tableau de couleurs qui nous traduira notre couleur FR en EN
        div.querySelector("p").style.backgroundColor = data[nomCouleur];
        console.log(data[nomCouleur]);

        if (data[nomCouleur] == "white" || data[nomCouleur] == "ivory") {
          div.querySelector("p").style.color = "black";
        }
      })

      .catch((error) => console.log(error));

    // on ajoute un écouteur d'evenement de type clique sur chaque DIV
    div.addEventListener("click", function () {
      // Pour chacune des divs on supprime la classe "active" pour être sûr qu'on ne se retrouve pas avec plusieurs div ayant la classe active
      divs.forEach((div) => {
        div.classList.remove("active");
      });
      // add:  crée la classe active
      div.classList.add("active");
      // imageName = variable titre + '_' + variable nomCouleur (concaténation)
      let imageName = titre + "_" + nomCouleur;
      // On récupère l'attribut src de la balise image
      let source = image.getAttribute("src");

      // extension = la valeur de l'attribut image (le chemin) .split (il créé un tableau qui sera séparé lorsqu'il rencontrera un point'.' exemple : image/image.jpg Resultat : ['image/image','jpg'])
      // .pop('supprime le dernier element d'un tableau et renvoie sa valeur') Depart : 'image/quelquechose/sous-dossier/image.extension' Resultat : extension
      extension = source.split(".").pop();
      // On envoie le nouveau chemin dans l'attribut src de l'image (on lui change l'image si elle existe !)
      image.setAttribute(
        "src",
        "/assets/images/" + imageName + "." + extension
      );
      ancre.setAttribute(
        "href",
        "/assets/images/" + imageName + "." + extension
      );
    });
  });
});

// Function d'ajout au panier (fetch)
function addToCart(id) {
  // Sélectionne le bouton "Panier" via son id (#bouton_panier)
  let button = document.querySelector("#bouton_panier");

  // Récupère l'URL associée au bouton "Panier" à partir de l'attribut 'data-url' du bouton
  let url = button.getAttribute("data-url");

  // Sélectionne tous les éléments div à l'intérieur de l'élément avec la classe "couleurs"
  let divs = document.querySelectorAll(".couleurs>div");

  // Variable pour stocker la couleur sélectionnée initialisée à null
  let selectedColor = null;

  // Récupère la valeur sélectionnée pour la taille dans un élément avec l'id "optionsTailles"
  let taille = document.querySelector("#optionsTailles").value;

  // Parcours chaque div pour vérifier si une couleur a été sélectionnée (en fonction de la classe "active")
  divs.forEach((div) => {
    if (div.classList.contains("active")) {
      // Si la div a la classe "active", la couleur sélectionnée correspond à l'id de cette div
      selectedColor = div.id;
    }
  });

  // Vérifie si aucune couleur ni taille n'a été sélectionnée
  if (!selectedColor && taille == "null") {
    // Affiche une alerte personnalisée demandant à l'utilisateur de sélectionner à la fois une couleur et une taille
    customAlert("Merci de choisir une Taille et une Couleur pour votre Robe !");
    return;
  } else if (!selectedColor) {
    // Si seule la couleur n'est pas sélectionnée, on affiche une alerte pour choisir une couleur
    customAlert("Merci de choisir une couleur pour votre robe !");
    return;
  } else if (taille == "null") {
    // Si seule la taille n'est pas sélectionnée, on affiche une alerte pour choisir une taille
    customAlert("Merci de choisir une taille pour votre robe !");
    return;
  }

  // Récupère l'URL de l'image de la robe via l'élément avec l'id "image_robe"
  let image = document.querySelector('#image_robe').getAttribute('src');

  // Envoie les données au serveur via une requête POST utilisant Fetch
  fetch(url, {
    method: "post", // Méthode POST pour envoyer les données
    headers: {
      "Content-Type": "application/json", // Indique que le corps de la requête est en JSON
    },
    body: JSON.stringify({
      // Les données envoyées au serveur sous forme d'objet JSON
      id: id,                 // L'identifiant unique du produit à ajouter au panier
      couleur: selectedColor, // La couleur sélectionnée pour le produit (obtenue via l'interface utilisateur)
      taille: taille,         // La taille sélectionnée pour le produit (récupérée à partir du menu déroulant de taille)
      image: image,           // L'URL de l'image associée au produit, créée à partir du titre et de la couleur
    }),
    
  })
    .then((result) => {
      // Quand la réponse arrive, elle est convertie en JSON
      return result.json();
    })
    .then((result) => {
      // Une fois la réponse reçue et traitée
      let paragraphe = document.querySelector("#success_add");
      paragraphe.style.display = "block"; // Affiche un paragraphe de succès

      // Sélectionne l'élément où le nombre d'articles est affiché
      let nb = document.querySelector("#session_nb");

      // Vérifie si une erreur a été renvoyée par le serveur
      if (result.error) {
        paragraphe.textContent = result.error; // Affiche le message d'erreur
      } else {
        // Si tout est ok, on met à jour le nombre d'articles dans le panier
        nb.textContent = result.nb;
        paragraphe.textContent = result.message; // Affiche un message de succès
        setTimeout(() => {
          paragraphe.style.display = "none"; // Cache le message après 3 secondes
        }, 3000);
      }
    })
    .catch((error) => console.log(error)); // En cas d'erreur, affiche l'erreur dans la console
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
  // Pour fermer le menu quand on clique à l'extérieur
  document.addEventListener("click", function (event) {
    if (
      !menuDiv.contains(event.target) &&
      !hamburgerMenu.contains(event.target)
    ) {
      menuDiv.classList.remove("show");
    } else {
      menuDiv.classList.add("show");
    }
  });
});
