function addToCart(id) {
  let button = document.querySelector("#bouton_panier");
  let url = button.getAttribute("data-url");

  fetch(url, {
    method: "post",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id: id }),
  })
    .then((result) => {
      return result.json();
    })
    .then((result) => {
      console.log(result.nb);
      let paragraphe = document.querySelector("#success_add");
      paragraphe.style.display = "block";
      paragraphe.textContent = result.message;
    })
    .catch((error) => console.log(error));
}

function removeFromCart(event, id) {
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
    body: JSON.stringify({ id: id }), // Passe l'ID au backend
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erreur réseau lors de la suppression de l'article.");
      }
      return response.json();
    })
    .then((result) => {
      // Affiche le message de confirmation
      let paragraphe = document.querySelector("#success_remove");
      if (paragraphe) {
        paragraphe.style.display = "block";
        paragraphe.textContent = result.message;
      }

      // On récupère la balise span du total du panier
      let span = document.querySelector("#total_panier");

      let total = parseFloat(span.textContent);

      // On récupère aussi le prix de la robe qu'on veut supprimer
      let prixRobe = parseFloat(
        event.target.parentElement.getAttribute("data-prix")
      );

      // On calcule le total - le prix de la robe supprimée
      let totalFinal = total - prixRobe;

      // On remplace le total par le nouveau total (tofixed = limite à 2 chiffres après la décimale)
      span.textContent = totalFinal.toFixed(2);

      // On récupère l'element parent de celui sur lequel on click (le bouton supprimer)
      let li = event.target.parentElement;
      // On supprime cet élément
      li.remove();

      if (totalFinal <= 0) {
        window.location.reload();
      }

      // Met à jour le compteur d'articles si présent
      let cartCount = document.querySelector("#cart_count");
      if (cartCount && result.nb !== undefined) {
        cartCount.textContent = result.nb;
      }
    })
    .catch((error) => console.error("Erreur lors de la suppression :", error));
}
